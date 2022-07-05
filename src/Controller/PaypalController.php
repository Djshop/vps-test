<?php

namespace App\Controller;





use App\Entity\Xbox;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Cart;
use App\Form\XboxType;
use App\Repository\XboxRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;




class PaypalController extends AbstractController
{
    private $managerRegistry;
    private $requestStack;
    private $security;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack, Security $security)
    {
        $this->managerRegistry = $managerRegistry;
        $this->requestStack = $requestStack;
        $this->security = $security;
    }

    

    //CART
    #[Route('/cart', name: 'app_cart')]
    public function cart(Request $request, XboxRepository $xboxRepository, UserRepository $userRepository, CartRepository $cartRepository): Response
    {




        //Récuperation de l'id user
        $user = $this->getUser();


        //Condition si l'user est connecté sinon on redirige vers la connexion
        if (!empty($user)) {
            $userId = $user->getId();



            //Récuperation du panier correspondant a l'id user
            $cart = $cartRepository->findOneBy(array('userId' => $userId));

            $cartFetchId = $cart->getId();

            //Explode des la chaine string ProductsId pour avoir les differents id des produis stocker dans le panier 

            $cartProductsId = explode('/', $cart->getProductsId());

            $cartProductsIdFetch = [];
            foreach ($cartProductsId as $cartId) {
                array_push($cartProductsIdFetch, $xboxRepository->findOneBy(array('id' => $cartId)));
            }

            //Comptage du nombre d'article
            $numberArticles = count($cartProductsId);

            //Comptage du total €
            $priceFetch = [];
            foreach ($cartProductsIdFetch as $xboxId) {
                $priceFetch[] = $xboxId->getPrice();
            }

            $totalPrice = 0;
            foreach ($priceFetch as $cartPrice) {
                $totalPrice = $totalPrice + $cartPrice;
            }

            $orderPaypalName = $numberArticles . ' Console(s) Xbox 360 Custom Dj-Shop';

            //Envoi du total price en session
            $session = new Session();

            if($session ->get('totalPrice')){

                $session->remove('totalPrice');
            }
            $session->set('totalPrice', $totalPrice);


            //Verification qu'un article n'est pas vendu entre le moment ou il est rentrer dans le panier et le moment ou je choisis de finir mon paiement 
            $countSell = 0;
            $idToDeleteFromCart = [];
            foreach ($cartProductsId as $xboxId) {
                $fetchSell = $xboxRepository->findBy(array('sold' => 'SOLD'));

                foreach ($fetchSell as $xboxSold) {
                    if ($xboxSold->getId() == $xboxId) {
                        $countSell++;
                        array_push($idToDeleteFromCart, $xboxId);
                    }
                }
            }

            if ($countSell != 0) {
                foreach ($idToDeleteFromCart as $deleteId) {

                    $idNotDelete = [];

                    //Si l'id de formulaire est delete est differente de l'id fectch de la bdd on le stock dans array pour refaire un set avec les bon id
                    foreach ($cartProductsId as $cartIds) {
                        if ($deleteId != $cartIds) {
                            $idNotDelete[] = $cartIds;
                        }
                    }
                    if (!empty($idNotDelete)) {

                        $idsFinal = implode('/', $idNotDelete);
                        $cart->setProductsId($idsFinal);
                        $cartRepository->add($cart);
                        return $this->redirectToRoute('app_cart', [], Response::HTTP_SEE_OTHER);
                    } else {
                        $cartRepository->remove($cart);

                        $user->setCart('empty');
                        $userRepository->add($user);
                        return $this->redirectToRoute('app_xbox_index', [], Response::HTTP_SEE_OTHER);
                    }
                }
            }



            //Condition si le formulaire est submit
            if (!empty($_POST)) {
                $safe = array_map('trim', array_map('strip_tags', $_POST));


                //Si le bouton delete est cliqué
                if (isset($safe['deleteIdCart'])) {
                    $idNotDelete = [];

                    //Si l'id de formulaire est delete est differente de l'id fectch de la bdd on le stock dans array pour refaire un set avec les bon id
                    foreach ($cartProductsId as $cartIds) {



                        if ($safe['deleteIdCart'] != $cartIds) {
                            $idNotDelete[] = $cartIds;
                        }
                    }
                    if (!empty($idNotDelete)) {

                        $idsFinal = implode('/', $idNotDelete);
                        $cart->setProductsId($idsFinal);
                        $cartRepository->add($cart);
                        return $this->redirectToRoute('app_cart', [], Response::HTTP_SEE_OTHER);
                    } else {
                        $cartRepository->remove($cart);

                        $user->setCart('empty');
                        $userRepository->add($user);
                        return $this->redirectToRoute('app_xbox_index', [], Response::HTTP_SEE_OTHER);
                    }
                }
                if (isset($safe['formAdressHiden'])) {
                    if (isset($safe['newAdress'])) {





                        $errorsAdress = [];

                        //Verification du prénom : Cas d'erreurs si il n'est pas rentrer ou depasse 50 caractères 
                        if (!empty($safe['firstname'])) {

                            if (strlen($safe['firstname']) > 50) {
                                $errorsAdress[] = 'Votre prénom est trop long ';
                            } else {
                                $firstname = $safe['firstname'];
                            }
                        } else {
                            $errorsAdress[] = 'Veuillez saisir un prénom ';
                        }
                        //Verification du nom : Cas d'erreurs si il n'est pas rentrer ou depasse 50 caractères 
                        if (!empty($safe['lastname'])) {
                            if (strlen($safe['lastname']) > 50) {
                                $errorsAdress[] = 'Votre nom est trop long ';
                            } else {
                                $lastname = $safe['lastname'];
                            }
                        } else {
                            $errorsAdress[] = 'Veuillez saisir un nom ';
                        }


                        //Verification de l'adresse
                        if (!empty($safe['adress1'])) {
                            if (strlen($safe['adress1']) > 60) {
                                $errorsAdress[] = 'Votre adresse ' . $safe['adress1'] . 'n\'est pas valide';
                            } else {
                                $adress1 = $safe['adress1'];
                            }
                        } else {
                            $errorsAdress[] = 'Veuillez saisir une adresse ';
                        }
                        //Verification de l'adresse
                        if (!empty($safe['adress2'])) {

                            if (strlen($safe['adress2']) < 1 || strlen($safe['adress2']) > 60) {
                                $errorsAdress[] = 'Votre adresse ' . $safe['adress2'] . 'n\'est pas valide';
                            } else {
                                $adress2 = $safe['adress2'];
                            }
                        }

                        //verification de la ville
                        if (!empty($safe['city'])) {
                            if (strlen($safe['city']) > 40) {
                                $errorsAdress[] = 'Votre ville ' . $safe['city'] . 'n\'est pas valide';
                            } else {
                                $city = $safe['city'];
                            }
                        } else {
                            $errorsAdress[] = 'Veuillez saisir une ville ';
                        }


                        //verification du code postal
                        if (!empty($safe['postal'])) {
                            if (!is_numeric($safe['postal']) || strlen($safe['postal']) < 2 || strlen($safe['postal']) > 5) {
                                $errorsAdress[] = 'Votre code postal ' . $safe['postal'] . 'n\'est pas valide';
                            } else {
                                $postal = $safe['postal'];
                            }
                        } else {
                            $errorsAdress[] = 'Veuillez saisir un code postal ';
                        }

                        // Verification téléphone
                        if (!empty($safe['telephone'])) {
                            if (preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", $safe['telephone'])) {
                                $cut_char = array("-", ".", " ");
                                $telephoneBdd = str_replace($cut_char, "", $safe['telephone']);
                                $teletelephone = chunk_split($telephoneBdd, 2, "\r");
                            } else {
                                $errorsAdress[] = "Votre mobile: " . $safe['telephone'] . "n'est pas un numéro valide";
                            }
                        } else {
                            $errorsAdress[] = 'Veuillez saisir un numero de téléphone ';
                        }

                        if (count($errorsAdress) == 0) {


                            if (isset($safe['adress2'])) {
                                $finalAdress = $safe['adress1'] . ' ' . $safe['adress2'];
                            } else {
                                $finalAdress = $safe['adress1'];
                            }

                            //Ajout de l'adress modifié en bdd
                            $cartUpdate = $cartRepository->findOneBy(array('userId' => $userId));
                            $cartUpdate->setAdress($finalAdress);
                            $cartUpdate->setPostal($postal);
                            $cartUpdate->setCity($city);
                            $cartUpdate->setPhone($telephoneBdd);
                            $cartUpdate->setBuyerFirstname($firstname);
                            $cartUpdate->setBuyerLastname($lastname);
                            $cartRepository->add($cartUpdate);

                            return $this->redirectToRoute('app_cart_payement', [], Response::HTTP_SEE_OTHER);
                        } else {
                            $this->addFlash('danger', implode('<br>', $errorsAdress));
                        }
                    } else {
                        //Ajout de l'adress modifié 
                        $cartUpdate = $cartRepository->findOneBy(array('userId' => $userId));
                        $cartUpdate->setAdress($user->getAdress());
                        $cartUpdate->setPostal($user->getPostal());
                        $cartUpdate->setCity($user->getCity());
                        $cartUpdate->setPhone($user->getPhone());
                        $cartUpdate->setBuyerFirstname($user->getFirstname());
                        $cartUpdate->setBuyerLastname($user->getLastname());
                        $cartRepository->add($cartUpdate);

                        return $this->redirectToRoute('app_cart_payement', [], Response::HTTP_SEE_OTHER);
                    }
                }
                if (isset($safe['deleteCart'])) {
                    //Supression du panier
                    $cartRepository->remove($cart);

                    //Set empty colonne Cart table User
                    $user->setCart('empty');
                    $userRepository->add($user);

                    return $this->redirectToRoute('app_xbox_index', [], Response::HTTP_SEE_OTHER);
                }
            }




            return $this->render('user/cart.html.twig', [
                'xboxIds' => $cartProductsIdFetch,
                'numberArticles' => $numberArticles,
                'totalPrice' => $totalPrice,
                'orderPaypalName' => $orderPaypalName,
                'cartFetchId' => $cartFetchId,

            ]);
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }

    //CART Payment
    #[Route('/cart/payment', name: 'app_cart_payement')]
    public function cartPayment(Request $request, XboxRepository $xboxRepository, UserRepository $userRepository, CartRepository $cartRepository): Response
    {

        //Récuperation de l'id user
        $user = $this->getUser();

        //Récupération de la date du jour pour estimation de la livraison entre 4 et 6 jours
      
        
        $daysName = [
        0 => 'Dimanche',
        1 => 'Lundi',
        2 => 'Mardi',
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi'
    ];
        $month = [
        'Jan' => 'Janvier',
        'Feb' => 'Fevrier',
        'Mar' => 'Mars',
        'Apr' => 'Avril',
        'May' => 'Mai',
        'Jun' => 'Juin',
        'Jul' => 'Juillet',
        'Aug' => 'Aout',
        'Sep' => 'Septembre',
        'Oct' => 'Octobre',
        'Nov' => 'Novembre',
        'Dec' => 'Décembre'
    ];

    //Affichage de la date d'estimation la plus rapide
    $date_start = new \DateTime('now +4 days');
    //Affichage de la date d'estimation la plus lente
    $date_end = new \DateTime('now +6 days');

    //Création des date en chaine de caractere et afficher en francais

    //Date Start
    //Affichage du Mois en francais
    $dateStartMonth=$month[$date_start->format('M')];
    //Affichage du Jour de la semaine en francais
    $dateStartWeekDay=$daysName[$date_start->format('w')];
   //Affichage du Jour en francais
    $dateStartDay=$date_start->format('d');
    //Affichage de la date finale en francais
    $dateStart=$dateStartWeekDay.' '.$dateStartDay.' '.$dateStartMonth;

    //Date End
    //Affichage du Mois en francais
    $dateEndMonth=$month[$date_end->format('M')];
    //Affichage du Jour de la semaine en francais
    $dateEndWeekDay=$daysName[$date_end->format('w')];
    //Affichage du Jour en francais
    $dateEndDay=$date_end->format('d');
    //Affichage de la date finale en francais
    $dateEnd=$dateEndWeekDay.' '.$dateEndDay.' '.$dateEndMonth;



     

        //Condition si l'user est connecté sinon on redirige vers la connexion
        if (!empty($user)) {
            $userId = $user->getId();

            //Récuperation du panier correspondant a l'id user
            $cart = $cartRepository->findOneBy(array('userId' => $userId));

            //Explode des la chaine string ProductsId pour avoir les differents id des produis stocker dans le panier 

            $cartProductsId = explode('/', $cart->getProductsId());

            $cartProductsIdFetch = [];
            foreach ($cartProductsId as $cartId) {
                array_push($cartProductsIdFetch, $xboxRepository->findOneBy(array('id' => $cartId)));
            }

            //Comptage du nombre d'article
            $numberArticles = count($cartProductsId);

            // //Comptage du total €
            // $priceFetch = [];
            // foreach ($cartProductsIdFetch as $xboxId) {
            //     $priceFetch[] = $xboxId->getPrice();
            // }

            // $totalPrice = 0;
            // foreach ($priceFetch as $cartPrice) {
            //     $totalPrice = $totalPrice + $cartPrice;
            // }
            $session = $this->requestStack->getSession();
            $session->set('verifiedSuccess', 'verified');

            

            
            


            // set des valeur du cart
            $cartDeliveryFirstname = $cart->getBuyerFirstname();
            $cartDeliveryLastname = $cart->getBuyerLastname();
            $cartDeliveryAdress = $cart->getAdress();
            $cartDeliveryPostal = $cart->getPostal();
            $cartDeliveryCity = $cart->getCity();
            $cartDeliveryPhone = $cart->getPhone();



            $orderPaypalName = $numberArticles . ' Console(s) Xbox 360 Custom Dj-Shop';
            return $this->render('user/cart_payment.html.twig', [
                'totalPrice' => $session->get('totalPrice'),
                'orderPaypalName' => $orderPaypalName,
                'numberArticles' => $numberArticles,
                'xboxs' => $cartProductsIdFetch,
                'firstname' => $cartDeliveryFirstname,
                'lastname' => $cartDeliveryLastname,
                'adress' => $cartDeliveryAdress,
                'postal' => $cartDeliveryPostal,
                'city' => $cartDeliveryCity,
                'phone' => $cartDeliveryPhone,
                'dateStart' => $dateStart,
                'dateEnd' => $dateEnd,

            ]);
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }

    //PAYPAL Cart success
    #[Route('/success', name: 'app_success')]
    public function successCart(XboxRepository $xboxRepository, OrderRepository $orderRepository, CartRepository $cartRepository, UserRepository $userRepository, MailerInterface $mailer)
    {

        //Démarrage de la session
        $session = $this->requestStack->getSession();

      
        $verifiedSuccess = $session->get('verifiedSuccess');
        
        
        $user = $this->getUser();
        //Condition si l'user est connecté sinon on redirige vers la connexion
        if (!empty($user) && $verifiedSuccess == 'verified') {
            $session->remove('verifiedSuccess');
            $userId = $user->getId();

            $orderName = "";

            $item_price = $_GET['amt']; // montant reçu par Paypal
            $item_currency = $_GET['cc']; // Le type de devise reçu par Paypal  


            //Récuperation du panier correspondant a l'id user
            $cart = $cartRepository->findOneBy(array('userId' => $userId));

            //Explode des la chaine string ProductsId pour avoir les differents id des produis stocker dans le panier 

            $cartProductsId = explode('/', $cart->getProductsId());

            $cartProductsIdFetch = [];
            foreach ($cartProductsId as $cartId) {
                array_push($cartProductsIdFetch, $xboxRepository->findOneBy(array('id' => $cartId)));
            }

            //Comptage du nombre d'article
            $numberArticles = count($cartProductsId);

            //Comptage du total €
            $priceFetch = [];
            foreach ($cartProductsIdFetch as $xboxId) {
                $priceFetch[] = $xboxId->getPrice();
            }

            $totalPrice = 0;
            foreach ($priceFetch as $cartPrice) {
                $totalPrice = $totalPrice + $cartPrice;
            }



            $currency = 'EUR';

            // Revérifier le prix du produit et le type de la devise 
            if ($item_price != $totalPrice && $item_currency != $currency) {
                //Echec du paiement on rétabli la console comme non vendu


                $status = "denied";
            } else {
                $countSell = 0;
                foreach ($cartProductsId as $xboxId) {
                    $fetchSell = $xboxRepository->findBy(array('sold' => 'SOLD'));

                    foreach ($fetchSell as $xboxSold) {
                        if ($xboxSold->getId() == $xboxId) {
                            $countSell++;
                        }
                    }
                }
                //On verifie qu'il n'y est pas un article de vendu entre le moment ou je l'ai mis dans mon panier et le moment ou je valide l'achat
                if ($countSell === 0) {



                    $status = "granted";

                    //passage du produit en vendu
                    $titleFinalArray = [];
                    foreach ($cartProductsId as $xboxId) {
                        $xboxBDD = $xboxRepository->findOneBy(array('id' => $xboxId));
                        $xboxBDD->setSold('SOLD');
                        $xboxRepository->add($xboxBDD);
                        array_push($titleFinalArray, $xboxBDD->getTitle());
                    }

                    $finalTitle = implode('/', $titleFinalArray);


                    //Récuperation de l'adresse du panier pour la set a la commande



                    $cartUpdate = $cartRepository->findOneBy(array('userId' => $userId));
                    $cartFirstnameFetch = $cartUpdate->getBuyerFirstname();
                    $cartLastnameFetch = $cartUpdate->getBuyerLastname();
                    $cartAdressFetch = $cartUpdate->getAdress();
                    $cartPhoneFetch =  $cartUpdate->getPhone();
                    $cartCityFetch = $cartUpdate->getCity();
                    $cartPostalFetch = $cartUpdate->getPostal();







                    //Création de la commande
                    $order = new Order();

                    //creation du nom de la commande en choisisant 8 caractères au hasard
                    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    $orderName = substr(str_shuffle($chars), 0, 8);

                    $order->setOrderName($orderName);
                    $order->setBuyerId($userId);
                    $order->setShippingStatus('waiting');
                    $order->setProducts($finalTitle);
                    $order->setProductsId($cart->getProductsId());
                    $order->setTotal($totalPrice);
                    $order->setAdress($cartAdressFetch);
                    $order->setPostal($cartPostalFetch);
                    $order->setCity($cartCityFetch);
                    $order->setPhone($cartPhoneFetch);
                    $order->setFirstname($cartFirstnameFetch);
                    $order->setLastname($cartLastnameFetch);

                    $orderRepository->add($order);

                    //Suppression du panier de table cart
                    $cartBdd = $cartRepository->findOneBy(array('userId' => $userId));
                    $cartRepository->remove($cartBdd);

                    //Suppression du panier de la colonne user
                    $user->setCart('empty');
                    $userRepository->add($user);

                    //Envoi de mail au client 
                    $userMail = $user->getEmail();

                    $subject = 'Nous préparons votre commande #' . $orderName;
                    $subjectAdmin = 'Une nouvelle commande #' . $orderName;

                    //Envoi des donnees a la vue en session
                    $session = $this->requestStack->getSession();

                    if($session->get('cartFirstnameFetch')){
                        $session->remove('cartFirstnameFetch');
                        $session->remove('cartLastnameFetch');
                        $session->remove('orderName');
                        $session->remove('totalPrice');
                        $session->remove('products');
                    }
                  
                    $session->set('cartFirstnameFetch', $cartFirstnameFetch);
                    $session->set('cartLastnameFetch', $cartLastnameFetch);
                    $session->set('orderName', $orderName);
                    $session->set('totalPrice', $totalPrice);
                    $session->set('products', $finalTitle);


                    $emailUser = (new TemplatedEmail())
                        ->from('djemshop31.noreply@gmail.com')
                        ->to($userMail)
                        ->subject($subject)
                        ->htmlTemplate('emails/order_user.html.twig');

                    $emailAdmin = (new TemplatedEmail())
                        ->from('djemshop31.noreply@gmail.com')
                        ->to('djemshop31.noreply@gmail.com')
                        ->subject($subjectAdmin)
                        ->htmlTemplate('emails/order_admin.html.twig');



                    $mailer->send($emailUser);
                    $mailer->send($emailAdmin);
                } else {
                    $status = "denied";
                }
            }


            return $this->render('paypal/successCart.html.twig', [
                'status' => $status,
                'orderName' => $orderName,
            ]);
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }
    //PAYPAL Cart cancel
    #[Route('/cancel', name: 'app_cancel')]
    public function cancelCart(XboxRepository $xboxRepository, OrderRepository $orderRepository, CartRepository $cartRepository, UserRepository $userRepository, MailerInterface $mailer)
    {

        //Démarrage de la session

        $user = $this->getUser();
        //Condition si l'user est connecté sinon on redirige vers la connexion
        if (!empty($user)) {
           

            return $this->render('paypal/cancelCart.html.twig');
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }

   
}
