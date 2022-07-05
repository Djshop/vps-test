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



class XboxController extends AbstractController
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

    //List Page Boutique
    #[Route('/', name: 'app_xbox_index')]
    public function index(XboxRepository $xboxRepository, UserRepository $userRepository, CartRepository $cartRepository): Response
    {
        $cartProducts = '';

        //Déclaration de l'user
        $user = $this->getUser();
        if (isset($user)) {

            $userId = $user->getId();


            //fetch du panier de l'user pour check si un article est déja dans le panier 
            $cartFetch = $cartRepository->findOneBy(array('userId' => $userId));
            if (isset($cartFetch)) {
                $cartProducts = explode('/', $cartFetch->getProductsId());
            }
        }
        $xboxes = $xboxRepository->findAll();

        //on check les filtre stocker en session a l'initialisation de la page on verifie quel sont les criteres séléctionner et tout les combinaisons de criteres possible pour effectuer le bon FindBy en BDD exemple si la generation de la console est choisi et le filtre en vente et le prix croissant est choisi cela donne ($xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'), array('price' => 'ASC')))
        $session = $this->requestStack->getSession();
        if ($session->get('genFat')) {

            if ($session->get('toSell')) {

                if ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'), array('price' => 'DESC'));
                } elseif ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'), array('price' => 'ASC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'));
                }
            } elseif ($session->get('priceLow')) {
                $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'DESC'));
            } elseif ($session->get('priceUp')) {
                $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'ASC'));
            } else {
                $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'));
            }
        } elseif ($session->get('genSlim')) {
            if ($session->get('toSell')) {

                if ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'), array('price' => 'DESC'));
                } elseif ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'), array('price' => 'ASC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'));
                }
            } elseif ($session->get('priceLow')) {
                $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'DESC'));
            } elseif ($session->get('priceUp')) {
                $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'ASC'));
            } else {
                $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'));
            }
        } elseif ($session->get('priceUp')) {
            
            if ($session->get('genFat')) {
                if ($session->get('toSell')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'), array('price' => 'ASC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'ASC'));
                }
            } elseif ($session->get('genSlim')) {
                if ($session->get('toSell')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'), array('price' => 'ASC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'ASC'));
                }
            }else{
                $xboxes = $xboxRepository->findBy(array(), array('price' => 'ASC'));
            }
        } elseif ($session->get('priceLow')) {
            
            if ($session->get('genFat')) {
                if ($session->get('toSell')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'), array('price' => 'DESC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'DESC'));
                }
            } elseif ($session->get('genSlim')) {
                if ($session->get('toSell')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'), array('price' => 'DESC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'DESC'));
                }
            }else{
                $xboxes = $xboxRepository->findBy(array(), array('price' => 'DESC'));
            }
        } elseif ($session->get('toSell')) {
            if ($session->get('genFat')) {
                if ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'),  array('price' => 'ASC'));
                } elseif ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'),  array('price' => 'DESC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'),);
                }
            } elseif ($session->get('genSlim')) {
                if ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'),  array('price' => 'ASC'));
                } elseif ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'),  array('price' => 'DESC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'),);
                }
            } elseif ($session->get('priceUp')) {
                $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'), array('price' => 'ASC'));
            } elseif ($session->get('priceLow')) {
                $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'), array('price' => 'DESC'));
            } else {
                $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'));
            }
        } else {
            $xboxes = $xboxRepository->findAll();
        }



        if (!empty($_POST)) {

            $safe = array_map('trim', array_map('strip_tags', $_POST));

            //Form filter


            //Condition pour tout les formulaire de critéres au clique on verifie si d'autre criteres on etait selectionné depuis la session et on effectue le bon findBy parmis tout les possibilite de combinaison de critere
            if (isset($safe['genFat'])) {

                if ($session->get('toSell')) {

                    if ($session->get('priceLow')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'), array('price' => 'DESC'));
                    } elseif ($session->get('priceUp')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'), array('price' => 'ASC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'));
                    }
                } elseif ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'DESC'));
                } elseif ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'ASC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'));
                }

                //Envoi des donnees a la vue en session
                $session = $this->requestStack->getSession();
                if ($session->get('genFat')  || $session->get('genSlim') ) {
                    $session->remove('genFat');
                    $session->remove('genSlim');
                }

                $session->set('genFat', 'genFat');
            }
            if (isset($safe['genFatDeselect'])) {
                if ($session->get('toSell')) {

                    if ($session->get('priceLow')) {
                        $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'), array('price' => 'DESC'));
                    } elseif ($session->get('priceUp')) {
                        $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'), array('price' => 'ASC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'));
                    }
                } elseif ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array(), array('price' => 'DESC'));
                } elseif ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array(), array('price' => 'ASC'));
                } else {
                    $xboxes = $xboxRepository->findAll();
                }
                //Envoi des donnees a la vue en session
                $session = $this->requestStack->getSession();
                if ($session->get('genFat')  || $session->get('genSlim') ) {
                    $session->remove('genFat');
                    $session->remove('genSlim');
                }

            }
            if (isset($safe['genSlim'])) {
                if ($session->get('toSell')) {

                    if ($session->get('priceLow')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'), array('price' => 'DESC'));
                    } elseif ($session->get('priceUp')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'), array('price' => 'ASC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'));
                    }
                } elseif ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'DESC'));
                } elseif ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'ASC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'));
                }
                //Envoi des donnees a la vue en session
                $session = $this->requestStack->getSession();
                if ($session->get('genFat')  || $session->get('genSlim') ) {
                    $session->remove('genFat');
                    $session->remove('genSlim');
                }

                $session->set('genSlim', 'genSlim');
            }
            if (isset($safe['genSlimDeselect'])) {
                if ($session->get('toSell')) {

                    if ($session->get('priceLow')) {
                        $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'), array('price' => 'DESC'));
                    } elseif ($session->get('priceUp')) {
                        $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'), array('price' => 'ASC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'));
                    }
                } elseif ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array(), array('price' => 'DESC'));
                } elseif ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array(), array('price' => 'ASC'));
                } else {
                    $xboxes = $xboxRepository->findAll();
                }
                //Envoi des donnees a la vue en session
                $session = $this->requestStack->getSession();
                if ($session->get('genFat')  || $session->get('genSlim') ) {
                    $session->remove('genFat');
                    $session->remove('genSlim');
                }

             
            }
            if (isset($safe['priceLow'])) {

                if ($session->get('genFat')) {

                    if ($session->get('toSell')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'), array('price' => 'DESC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'DESC'));
                    }
                } elseif ($session->get('genSlim')) {

                    if ($session->get('toSell')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'), array('price' => 'DESC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'DESC'));
                    }
                } else {
                    $xboxes = $xboxRepository->findBy(array(), array('price' => 'DESC'));
                }

                //Envoi des donnees a la vue en session
                $session = $this->requestStack->getSession();
                if ($session->get('priceLow')  || $session->get('priceUp') ) {
                    $session->remove('priceLow');
                    $session->remove('priceUp');
                    
                }

                $session->set('priceLow', 'priceLow');
            }
            if (isset($safe['priceLowDeselect'])) {

                if ($session->get('genFat')) {

                    if ($session->get('toSell')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'));
                    }
                } elseif ($session->get('genSlim')) {

                    if ($session->get('toSell')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'));
                    }
                } else {
                    $xboxes = $xboxRepository->findAll();
                }

                //Envoi des donnees a la vue en session
                $session = $this->requestStack->getSession();
                if ($session->get('priceLow')  || $session->get('priceUp') ) {
                    $session->remove('priceLow');
                    $session->remove('priceUp');
                    
                }

            
            }

            if (isset($safe['priceUp'])) {
                if ($session->get('genFat')) {

                    if ($session->get('toSell')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'), array('price' => 'ASC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'ASC'));
                    }
                } elseif ($session->get('genSlim')) {

                    if ($session->get('toSell')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'), array('price' => 'ASC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'ASC'));
                    }
                } else {
                    $xboxes = $xboxRepository->findBy(array(), array('price' => 'ASC'));
                }

                $session = $this->requestStack->getSession();
                if ($session->get('priceLow')  || $session->get('priceUp') ) {
                    $session->remove('priceLow');
                    $session->remove('priceUp');
                    
                }

                $session->set('priceUp', 'priceUp');
            }
            if (isset($safe['priceUpDeselect'])) {
                if ($session->get('genFat')) {

                    if ($session->get('toSell')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'));
                    }
                } elseif ($session->get('genSlim')) {

                    if ($session->get('toSell')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'));
                    }
                } else {
                    $xboxes = $xboxRepository->findAll();
                }


                $session = $this->requestStack->getSession();
                if ($session->get('priceLow')  || $session->get('priceUp') ) {
                    $session->remove('priceLow');
                    $session->remove('priceUp');
                    
                }

             
            }

            if (isset($safe['toSell'])) {
                if ($session->get('genFat')) {
                    if ($session->get('priceUp')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'),  array('price' => 'ASC'));
                    } elseif ($session->get('priceLow')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'),  array('price' => 'DESC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat', 'sold' => 'to sell'),);
                    }
                } elseif ($session->get('genSlim')) {
                    if ($session->get('priceUp')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'),  array('price' => 'ASC'));
                    } elseif ($session->get('priceLow')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'),  array('price' => 'DESC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim', 'sold' => 'to sell'),);
                    }
                } elseif ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'), array('price' => 'ASC'));
                } elseif ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'), array('price' => 'DESC'));
                } else {
                    $xboxes = $xboxRepository->findBy(array('sold' => 'to sell'));
                }
                $session = $this->requestStack->getSession();
                if ($session->get('toSell') ) {
                    $session->remove('toSell');
                }

                $session->set('toSell', 'toSell');
            }
            if (isset($safe['toSellDeselect'])) {
                if ($session->get('genFat')) {
                    if ($session->get('priceUp')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'ASC'));
                    } elseif ($session->get('priceLow')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'), array('price' => 'DESC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxFat'));
                    }
                } elseif ($session->get('genSlim')) {
                    if ($session->get('priceUp')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'ASC'));
                    } elseif ($session->get('priceLow')) {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'), array('price' => 'DESC'));
                    } else {
                        $xboxes = $xboxRepository->findBy(array('generation' => 'xboxSlim'));
                    }
                } elseif ($session->get('priceUp')) {
                    $xboxes = $xboxRepository->findBy(array('price' => 'ASC'));
                } elseif ($session->get('priceLow')) {
                    $xboxes = $xboxRepository->findBy(array('price' => 'DESC'));
                } else {
                    $xboxes = $xboxRepository->findAll();
                }
                $session = $this->requestStack->getSession();
                if ($session->get('toSell')) {
                    $session->remove('toSell');
                   
                }

                
            }

            if (isset($safe['cancelFilters'])) {
             
                $session = $this->requestStack->getSession();
                if ($session->get('toSell') || $session->get('genFat')|| $session->get('genSlim')|| $session->get('priceLow')|| $session->get('priceUp')) {
                    $session->remove('toSell');
                    $session->remove('genFat');
                    $session->remove('genSlim');
                    $session->remove('priceLow');
                    $session->remove('priceUp');
                }
                $xboxes = $xboxRepository->findAll();

                
            }
   



            //Ajout de l'id du panier a sur la table user une fois que le panier est créér
            if (isset($safe['addToCart'])) {

                //Vérification que le panier n'existe pas déjà si il existe on ajoute simplement les article
                $cartExist = $cartRepository->findOneBy(array('userId' => $userId));
                if (empty($cartExist)) {
                    $cart = new Cart();

                    $cart->setProductsId($safe['productsId']);
                    $cart->setUserId($userId);
                    $cart->setDate(time());
                    $cart->setAdress($user->getAdress());
                    $cart->setPostal($user->getPostal());
                    $cart->setCity($user->getCity());
                    $cart->setPhone($user->getPhone());
                    $cart->setBuyerFirstname($user->getFirstname());
                    $cart->setBuyerLastname($user->getLastname());



                    $cartRepository->add($cart);

                    //On ajout l'id du cart sur la table user
                    $cartAdded = $cartRepository->findOneBy(array('userId' => $userId));
                    $cartId = $cartAdded->getId();

                    $user->setCart($cartId);
                    $userRepository->add($user);

                    header("Refresh:0");
                } else {
                    //Ajout des article au panier seulement si il n'est pas déja dans le panier 
                    $cartOldProductsId = $cartExist->getProductsId();
                    $cartExplodeProductsId = explode('/', $cartOldProductsId);
                    $errorsCart = [];
                    foreach ($cartExplodeProductsId as $cartId) {
                        if ($safe['productsId'] === $cartId) {
                            $errorsCart[] = 'Cette article est déja dans votre panier';
                        }
                    }

                    if (count($errorsCart) == 0) {



                        //Arrays

                        $cartArrayProductsId = [];
                        $cartArrayPrice = [];

                        //Fetch et explode des colonne ProductsId 
                        $cartOldProductsId = $cartExist->getProductsId();
                        $cartExplodeProductsId = explode('/', $cartOldProductsId);
                        foreach ($cartExplodeProductsId as $cartId) {
                            array_push($cartArrayProductsId, $cartId);
                        }
                        array_push($cartArrayProductsId, $safe['productsId']);



                        $cartNewProductsId = implode('/', $cartArrayProductsId);


                        $cartExist->setProductsId($cartNewProductsId);

                        $cartExist->setDate(time());

                        $cartRepository->add($cartExist);
                    }
                }
                return $this->redirectToRoute('app_xbox_index', [], Response::HTTP_SEE_OTHER);
            }
            //Suppression de l'article du panier depuis la page shop
            if (isset($safe['deleteFromCart'])) {
                $cartDelete = $cartRepository->findOneBy(array('userId' => $userId));
                $cartProductsId = explode('/', $cartDelete->getProductsId());
                $idNotDelete = [];

                //On check tout les id stocker en bdd pour delete seulement celui qui correspond a l'id du form
                foreach ($cartProductsId as $cartIds) {



                    if ($safe['deleteIdCart'] != $cartIds) {
                        $idNotDelete[] = $cartIds;
                    }
                }
                if (!empty($idNotDelete)) {

                    $idsFinal = implode('/', $idNotDelete);
                    $cartDelete->setProductsId($idsFinal);
                    $cartRepository->add($cartDelete);
                } else {
                    $cartRepository->remove($cartDelete);

                    $user->setCart('empty');
                    $userRepository->add($user);
                }

                return $this->redirectToRoute('app_xbox_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('xbox/shop.html.twig', [
            'xboxes' => $xboxes,
            'cartProducts' => $cartProducts,
        ]);
    }

    //List All User
    #[Route('/user/list', name: 'app_users_list', methods: ['GET', 'POST'])]
    public function allUser(UserRepository $userRepository,  EntityManagerInterface $entityManager): Response
    {

        //Récuperation de l'user
        $user = $this->getUser();

        //Condition si l'user est connecté  sinon on redirige vers la connexion
        if (!empty($user)) {
            //Condition si le role est admin sinon on redirige vers la homepage
            if (in_array("ROLE_ADMIN", $user->getRoles())) {
                //Récupération de tout les user
                $allUsers = $userRepository->findAll();
                //Array pour stocker les user par rapport a leur roles
                $admins = [];
                $users = [];
                // Pour chaque user si son role est Admin il est stocker dans l'array admins et pareil pour l'user avec l'array users
                foreach ($allUsers as $user) {
                    $userRoles = $user->getRoles();
                    if ($userRoles[0] == "ROLE_ADMIN") {
                        array_push($admins, $user);
                    } else {
                        array_push($users, $user);
                    }
                }

                //On compte le nombre de compte correspondant au role qui nous permettra de ne pas afficher de liste User ou Admin si il n'y a pas de compte User ou Admin , et de pouvoir bloqué la possibilite de changer le role d'un admin si il est le dernier compte admin
                $countAdmins = count($admins);
                $countUsers = count($users);

                if (!empty($_POST)) {

                    $safe = array_map('trim', array_map('strip_tags', $_POST));

                    if (isset($safe['changeToAdmin'])) {
                        $userId = $userRepository->findOneBy(array('id' => $safe['changeToAdmin']));
                        $role = ['ROLE_ADMIN'];
                        $userId->setRoles($role);
                        $entityManager->persist($userId);
                        $entityManager->flush();
                        return $this->redirectToRoute('app_users_list', [], Response::HTTP_SEE_OTHER);
                    }
                    if (isset($safe['changeToUser'])) {
                        $userId = $userRepository->findOneBy(array('id' => $safe['changeToUser']));
                        $role = ['ROLE_USER'];
                        if ($countAdmins >= 2) {
                            $userId->setRoles($role);
                            $entityManager->persist($userId);
                            $entityManager->flush();
                            return $this->redirectToRoute('app_users_list', [], Response::HTTP_SEE_OTHER);
                        }
                    }
                }

                return $this->render('admin/user_list.html.twig', [
                    'users' => $allUsers,
                    'countAdmins' => $countAdmins,
                    'countUsers' => $countUsers,
                ]);
            } else {
                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }
    //List Stock
    #[Route('/list', name: 'app_xbox_list', methods: ['GET'])]
    public function list(XboxRepository $xboxRepository): Response
    {

        //Récuperation de l'user
        $user = $this->getUser();

        //Condition si l'user est connecté  sinon on redirige vers la connexion
        if (!empty($user)) {
            //Condition si le role est admin sinon on redirige vers la homepage
            if (in_array("ROLE_ADMIN", $user->getRoles())) {

                $xboxes = $xboxRepository->findAll();
                $countSold = 0;
                $countSell = 0;

                if (!empty($xboxes)) {


                    foreach ($xboxes as $xbox) {
                        if ($xbox->getSold() == 'to sell') {
                            $countSell++;
                        }
                        if ($xbox->getSold() == 'SOLD') {
                            $countSold++;
                        }
                    }
                }
                return $this->render('xbox/list.html.twig', [
                    'xboxes' => $xboxes,
                    'countSold' => $countSold,
                    'countSell' => $countSell,
                ]);
            } else {
                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }


    //CREATE
    #[Route('/xbox/new', name: 'app_xbox_new', methods: ['GET', 'POST'])]
    public function new(Request $request, XboxRepository $xboxRepository): Response
    {
        //Récuperation de l'user
        $user = $this->getUser();

        //Condition si l'user est connecté  sinon on redirige vers la connexion
        if (!empty($user)) {
            //Condition si le role est admin sinon on redirige vers la homepage
            if (in_array("ROLE_ADMIN", $user->getRoles())) {

                //Params
                $errorsAddXbox = [];


                // Array des différentes génération de console pour la vérification
                $gen = [
                    'Xbox 360 Fat' => 'Xbox 360 Fat',
                    'Xbox 360 Slim' => 'Xbox 360 Slim',

                ];
                $generation = [
                    'xboxFat' => 'xboxFat',
                    'xboxSlim' => 'xboxSlim',

                ];

                //Param pour les vérification de la photo
                $fileMaxSize = 1024 * 1024 * 10; // Taille maximale de l'image en octet
                $fileAllowedMimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']; // Les types mimes attendus
                $fileDirUpload = 'uploads/'; // Chemin de sauvegarde d'image, à partir de là ou je me trouve, 


                if (!empty($_POST)) {
                    // on supprime les espace et les balise pour sécuriser nos formulaires 
                    $safe = array_map('trim', array_map('strip_tags', $_POST));







                    //Verification du Titre: Cas d'erreurs si il n'est pas rentrer ou depasse 30 caractères 
                    if (!empty($safe['title'])) {
                        if (strlen($safe['title']) > 100) {
                            $errorsAddXbox[] = 'Le titre est trop long : 100 caracteres max';
                        }
                    } else {
                        $errorsAddXbox[] = 'Veuillez choisir un titre ';
                    }

                    // //Verification de la Description: Cas d'erreurs si elle n'est pas rentrer ou depasse 1000 caractères 
                    if (!empty($safe['description'])) {
                        if (strlen($safe['description']) < 1 || strlen($safe['description']) > 1000) {
                            $errorsAddXbox[] = 'La description doit contenir entre 1 et 1000 caractères ';
                        }
                    } else {
                        $errorsAddXbox[] = 'Veuillez choisir une description ';
                    }

                    //Verification du Prix: Cas d'erreurs si il n'est pas rentrer ou si il n'est pas dans la fourchette 50-500 
                    if (!empty($safe['price'])) {
                        if (!is_numeric($safe['price']) || $safe['price'] < 50 || $safe['price'] > 500) {
                            $errorsAddXbox[] = 'Le prix est invalide ';
                        }
                    } else {
                        $errorsAddXbox[] = 'Veuillez choisir un price ';
                    }

                    //Verification de la console Gen: Cas d'erreurs si il n'est pas séléctionner et si il n'existe pas dans l'array $gen
                    if (!empty($safe['generationFat']) || !empty($safe['generationSlim'])) {

                        if (isset($safe['generationFat'])) {

                            if (!in_array($safe['generationFat'], array_keys($generation))) {
                                $errorsAddXbox[] = 'La génération de la console invalide';
                            } else {

                                $generationBdd = $safe['generationFat'];
                            }
                        } elseif (isset($safe['generationSlim'])) {
                            if (!in_array($safe['generationSlim'], array_keys($generation))) {
                                $errorsAddXbox[] = 'La génération de la console invalide';
                            } else {
                                $generationBdd = $safe['generationSlim'];
                            }
                        }
                    } else {
                        $errorsAddXbox[] = 'Veuillez séléctionnez la génération de la console';
                    }



                    //Verification de la photo [PROVISOIRE]: Cas d'erreurs si 
                    if (!empty($_FILES) && isset($_FILES['picture'])) {

                        $picture = $_FILES['picture'];
                        foreach ($picture['error'] as $pictureError) {
                            if ($pictureError === UPLOAD_ERR_NO_FILE) {
                                $errorsAddXbox[] = 'Veuillez choisir au moins une photo'; // RENSEIGNER LE MESSAGE D'ERREUR ICI
                            }
                        }

                        foreach ($picture['type'] as $pictureType) {
                            if ($pictureError != UPLOAD_ERR_NO_FILE) {
                                if (!in_array($pictureType, $fileAllowedMimes)) {
                                    $errorsAddXbox[] = 'le type du fichier n\'est pas autorisé (jpg, gif, png)'; // RENSEIGNER LE MESSAGE D'ERREUR ICI
                                }
                            }
                        }

                        foreach ($picture['size'] as $pictureSize) {
                            if ($pictureSize > $fileMaxSize) {
                                $errorsAddXbox[] = 'l\'image est trop volumineux, taille maxi 10 Mo'; // RENSEIGNER LE MESSAGE D'ERREUR ICI
                            }
                        }
                    }


                    if (count($errorsAddXbox) == 0) {


                        // Permet de fabriquer automatiquement le dossier "uploads"
                        // la fonction is_dir verifie que un dossier / fichier est bien dossier
                        if (!is_dir($fileDirUpload)) {
                            mkdir($fileDirUpload, 0777); // make dir = fabrique le dossier
                        }

                        // Array pour envoyé en BDD le nom des photo avec un " / " les separant pour ensuite faire un explode de " / " et les affiché avec un foreach
                        $pictureNameArray = [];

                        // compteur avec une incrementation pour selection le ['tmp_name'][0] puis ['tmp_name'][1] etc
                        $count = 0;

                        foreach ($picture['name'] as $pictureName) {

                            // Permet de récupérer automatiquement l'extension du fichier téléchargé
                            $extPicture = pathinfo($pictureName, PATHINFO_EXTENSION);

                            // Créer un nom de fichier unique
                            $fileNamePicture = uniqid() . '.' . $extPicture;  // Donnera quelque de similaire à 4b3403665fea6.jpg


                            // Sauvegarde mon image
                            $finalFileNamePicture = '';

                            if (move_uploaded_file($picture['tmp_name'][$count], $fileDirUpload . $fileNamePicture)) { // $fileDirUpload.$fileName = "uploads/4b3403665fea6.jpg"
                                $finalFileNamePicture = $fileDirUpload . $fileNamePicture;
                                $pictureNameArray[] = $finalFileNamePicture;
                            }

                            $count++;
                        }
                        $pictureBDD = implode('_', $pictureNameArray);



                        $xbox = new Xbox();
                        $xbox->setTitle($safe['title']);
                        $xbox->setDescription($safe['description']);
                        $xbox->setPrice($safe['price']);
                        $xbox->setGeneration($generationBdd);
                        $xbox->setPicture($pictureBDD);
                        $xbox->setSold('to sell');
                        $xboxRepository->add($xbox);
                        $this->addFlash('success', 'La console a bien étais ajoutée');
                        // return $this->redirectToRoute('app_xbox_index', [], Response::HTTP_SEE_OTHER);
                    } else {
                        $this->addFlash('danger', implode('<br>', $errorsAddXbox));
                    }
                }

                return $this->render('xbox/new.html.twig', [
                    'gen' => $gen,
                ]);
            } else {
                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }

    //Order List Admin
    #[Route('/admin/order', name: 'app_order_admin')]
    public function admin(OrderRepository $orderRepository, XboxRepository $xboxRepository, UserRepository $userRepository, MailerInterface $mailer)
    {

        //Récuperation de l'user
        $user = $this->getUser();

        //Condition si l'user est connecté  sinon on redirige vers la connexion
        if (!empty($user)) {
            //Condition si le role est admin sinon on redirige vers la homepage
            if (in_array("ROLE_ADMIN", $user->getRoles())) {
                $orders = $orderRepository->findAll();
                $xboxs = $xboxRepository->findAll();
                //Comptage des commande en waiting
                $countWaiting = 0;
                foreach ($orders as $order) {
                    if ($order->getShippingStatus() == 'waiting') {
                        $countWaiting++;
                    }
                    if ($order->getShippingStatus() != 'waiting' && $order->getShippingStatus() != 'refund' && $order->getShippingStatus() != 'cancel') {
                        $countWaiting++;
                    }
                }

                //Comptage des commande en refund or cancel
                $countRefund = 0;
                foreach ($orders as $order) {
                    if ($order->getShippingStatus() == 'refund') {
                        $countRefund++;
                    }
                }
                //Comptage des commande en refund or cancel
                $countCancel = 0;
                foreach ($orders as $order) {
                    if ($order->getShippingStatus() == 'cancel') {
                        $countCancel++;
                    }
                }

                if (!empty($_POST)) {

                    $safe = array_map('trim', array_map('strip_tags', $_POST));

                    //Forumulaire N° Suivi
                    if (isset($safe['suiviForm'])) {


                        $errorsSuivi = [];


                        //Verification du N° de Suivi: Cas d'erreurs si il n'est pas rentrer ou depasse 30 caractères 
                        if (!empty($safe['suivi'])) {
                            if (strlen($safe['suivi']) < 1 || strlen($safe['suivi']) > 30) {
                                $errorsSuivi[] = "N° de suivi invalide";
                            }
                        } else {
                            $errorsSuivi[] = "Veuillez saisir un n° de suivi";
                        }



                        //Si il n'y a pas d'erreurs on procede a l'ajout du n° de suivi en bdd
                        if (count($errorsSuivi) == 0) {
                            $order = $orderRepository->findOneBy(array('id' => $safe['orderId']));

                            if (strval($order->getId()) === $safe['orderId']) {
                                $order->setShippingStatus($safe['suivi']);

                                $orderRepository->add($order);

                                //Envoi de mail a l'user pour prevenir de l'envoi de la console

                                $subject = 'La commande #' . $order->getOrderName() . ' a étais expédiée';

                                //Envoi des donnees a la vue en session
                                $session = $this->requestStack->getSession();
                                if ($session->get('firstnameFetch')) {
                                    $session->remove('firstnameFetch');
                                    $session->remove('lastnameFetch');
                                    $session->remove('orderName');
                                    $session->remove('suivi');
                                }

                                $session->set('firstnameFetch', $order->getFirstname());
                                $session->set('lastnameFetch', $order->getLastname());
                                $session->set('orderName', $order->getOrderName());
                                $session->set('suivi', $safe['suivi']);

                                $user = $userRepository->findOneBy(array('id' => $order->getBuyerId()));
                                $userMail = $user->getEmail();


                                //Envoi du mail a l'user
                                $emailUser = (new TemplatedEmail())
                                    ->from('djemshop31.noreply@gmail.com')
                                    ->to($userMail)
                                    ->subject($subject)
                                    ->htmlTemplate('emails/suivi_user.html.twig');

                                //Envoi du mail a l'admin
                                $emailAdmin = (new TemplatedEmail())
                                    ->from('djemshop31.noreply@gmail.com')
                                    ->to('djemshop31@gmail.com')
                                    //->cc($mailSender)
                                    //->bcc('bcc@example.com')
                                    //->replyTo('fabien@example.com')
                                    //->priority(Email::PRIORITY_HIGH)
                                    ->subject($subject)
                                    ->htmlTemplate('emails/suivi_admin.html.twig');



                                $mailer->send($emailUser);
                                $mailer->send($emailAdmin);
                                $this->addFlash('success', 'La commande est bien passé comme expediée, un mail recapitulatif vous a etais envoyez ainsi qu\'au client');
                                return $this->redirectToRoute('app_order_admin', [], Response::HTTP_SEE_OTHER);
                            }
                        } else {
                            $this->addFlash('danger', implode('<br>', $errorsSuivi));
                        }
                    }

                    //Formulaire N° de transaction paypal (refund)
                    if (isset($safe['refundForm'])) {
                        $errorsRefund = [];


                        //Verification du N° de Suivi: Cas d'erreurs si il n'est pas rentrer ou depasse 30 caractères 
                        if (!empty($safe['refund'])) {
                            if (strlen($safe['refund']) < 1 || strlen($safe['refund']) > 100) {
                                $errorsRefund[] = "N° de transaction paypal invalide";
                            }
                        } else {
                            $errorsRefund[] = "Veuillez saisir le n° de transaction Paypal";
                        }




                        //Si il n'y a pas d'erreurs on procede a l'ajout du n° de suivi en bdd
                        if (count($errorsRefund) == 0) {
                            $order = $orderRepository->findOneBy(array('id' => $safe['orderId']));

                            if (strval($order->getId()) === $safe['orderId']) {
                                $order->setShippingStatus('cancel');
                                $order->setRefundId($safe['refund']);

                                $orderRepository->add($order);
                                //Envoi des donnees a la vue en session
                                $session = $this->requestStack->getSession();
                                if ($session->get('firstnameFetch')) {
                                    $session->remove('firstnameFetch');
                                    $session->remove('lastnameFetch');
                                    $session->remove('orderName');
                                    $session->remove('refund');
                                }
                                $session->set('firstnameFetch', $order->getFirstname());
                                $session->set('lastnameFetch', $order->getLastname());
                                $session->set('orderName', $order->getOrderName());
                                $session->set('refund', $safe['refund']);

                                $user = $userRepository->findOneBy(array('id' => $order->getBuyerId()));




                                $subject = 'La commande ' . $order->getOrderName() . ' a étais remboursé';
                                $emailUser = (new TemplatedEmail())
                                    ->from('djemshop31.noreply@gmail.com')
                                    ->to($user->getEmail())
                                    ->subject($subject)
                                    ->htmlTemplate('emails/refund_user.html.twig');

                                $emailAdmin = (new TemplatedEmail())
                                    ->from('djemshop31.noreply@gmail.com')
                                    ->to('djemshop31.noreply@gmail.com')
                                    //->cc($mailSender)
                                    //->bcc('bcc@example.com')
                                    //->replyTo('fabien@example.com')
                                    //->priority(Email::PRIORITY_HIGH)
                                    ->subject($subject)
                                    ->htmlTemplate('emails/refund_admin.html.twig');



                                $mailer->send($emailUser);
                                $mailer->send($emailAdmin);
                                $this->addFlash('success', 'La commande est bien passé comme annulez remboursé, un mail recapitulatif vous a etais envoyez ainsi qu\'au client');
                                return $this->redirectToRoute('app_order_admin', [], Response::HTTP_SEE_OTHER);
                            }
                        } else {
                            $this->addFlash('danger', implode('<br>', $errorsRefund));
                        }
                    }

                    //Formulaire d'annulation de commande
                    if (isset($safe['cancelRefundForm'])) {
                        $errorsCancelRefund = [];


                        //Verification du N° de Suivi: Cas d'erreurs si il n'est pas rentrer ou depasse 150 caractères 
                        if (!empty($safe['cancelRefund'])) {

                            if (strlen($safe['cancelRefund']) > 150) {
                                $errorsCancelRefund[] = "Votre message est trop long : 150 caractere max";
                            }
                        } else {
                            $errorsCancelRefund[] = "Veuillez saisir la raison de l'annulation";
                        }



                        //Si il n'y a pas d'erreurs on procede a l'ajout du n° de suivi en bdd
                        if (count($errorsCancelRefund) == 0) {

                            $order = $orderRepository->findOneBy(array('id' => $safe['orderId']));

                            if (strval($order->getId()) === $safe['orderId']) {

                                $order->setShippingStatus('refund');
                                $orderRepository->add($order);

                                //Envoi des donnees a la vue en session
                                $session = $this->requestStack->getSession();
                                if ($session->get('firstnameFetch')) {
                                    $session->remove('firstnameFetch');
                                    $session->remove('lastnameFetch');
                                    $session->remove('orderName');
                                    $session->remove('reason');
                                }
                                $session->set('firstnameFetch', $order->getFirstname());
                                $session->set('lastnameFetch', $order->getLastname());
                                $session->set('orderName', $order->getOrderName());
                                $session->set('reason', $safe['cancelRefund']);

                                $user = $userRepository->findOneBy(array('id' => $order->getBuyerId()));




                                $subject = 'La commande ' . $order->getOrderName() . ' a étais annulez';
                                $emailUser = (new TemplatedEmail())
                                    ->from('djemshop31.noreply@gmail.com')
                                    ->to($user->getEmail())
                                    ->subject($subject)
                                    ->htmlTemplate('emails/cancel_admin_user.html.twig');

                                $emailAdmin = (new TemplatedEmail())
                                    ->from('djemshop31.noreply@gmail.com')
                                    ->to('djemshop31.noreply@gmail.com')

                                    ->subject($subject)
                                    ->htmlTemplate('emails/cancel_admin_admin.html.twig');



                                $mailer->send($emailUser);
                                $mailer->send($emailAdmin);
                                $this->addFlash('success', 'La commande est bien passé comme "a rembourser", un mail recapitulatif vous a etais envoyez ainsi qu\'au client');
                                return $this->redirectToRoute('app_order_admin', [], Response::HTTP_SEE_OTHER);
                            }
                        } else {
                            $this->addFlash('danger', implode('<br>', $errorsCancelRefund));
                        }
                    }
                }
                return $this->render('admin/order.html.twig', [
                    'orders' => $orders,
                    'xboxs' => $xboxs,
                    'countCancel' => $countCancel,
                    'countRefund' => $countRefund,
                    'countWaiting' => $countWaiting,
                ]);
            } else {
                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }
    //Order List User
    #[Route('/order', name: 'order')]
    public function order(XboxRepository $xboxRepository, OrderRepository $orderRepository, CartRepository $cartRepository, UserRepository $userRepository, MailerInterface $mailer)
    {
        //Recuperation de l'user et de son id si il est bien connecté
        $user = $this->getUser();
        if (!empty($user)) {
            $userId = $user->getId();
            //Récuperation du panier correspondant a l'id user

            $orders = $orderRepository->findBy(array('buyerId' => $userId));
            $xboxs = $xboxRepository->findAll();

            //Comptage des commande en waiting
            $countWaiting = 0;
            foreach ($orders as $order) {
                if ($order->getShippingStatus() == 'waiting') {
                    $countWaiting++;
                } else {
                    if ($order->getShippingStatus() != 'refund' && $order->getShippingStatus() != 'cancel') {
                        $countWaiting++;
                    }
                }
            }

            //Comptage des commande en refund or cancel
            $countRefund = 0;
            foreach ($orders as $order) {
                if ($order->getShippingStatus() == 'refund' || $order->getShippingStatus() == 'cancel') {
                    $countRefund++;
                }
            }

            if (!empty($_POST)) {
                $safe = array_map('trim', array_map('strip_tags', $_POST));

                if (isset($safe['cancelOrder'])) {
                    $order = $orderRepository->findOneBy(array('id' => $safe['cancelOrder']));
                    if (empty($order->getRefundId())) {
                        $order->setShippingStatus('refund');
                        $orderRepository->add($order);

                        //Envoi des donnees a la vue en session
                        $session = $this->requestStack->getSession();
                        if ($session->get('firstnameFetch')) {
                            $session->remove('firstnameFetch');
                            $session->remove('lastnameFetch');
                            $session->remove('orderName');
                        }
                        $session->set('firstnameFetch', $order->getFirstname());
                        $session->set('lastnameFetch', $order->getLastname());
                        $session->set('orderName', $order->getOrderName());


                        $subject = 'La commande ' . $order->getOrderName() . ' a étais annulez';
                        $emailUser = (new TemplatedEmail())
                            ->from('djemshop31.noreply@gmail.com')
                            ->to($user->getEmail())
                            ->subject($subject)
                            ->htmlTemplate('emails/cancel_user_user.html.twig');

                        $emailAdmin = (new TemplatedEmail())
                            ->from('djemshop31.noreply@gmail.com')
                            ->to('djemshop31.noreply@gmail.com')
                            //->cc($mailSender)
                            //->bcc('bcc@example.com')
                            //->replyTo('fabien@example.com')
                            //->priority(Email::PRIORITY_HIGH)
                            ->subject($subject)
                            ->htmlTemplate('emails/cancel_user_admin.html.twig');



                        $mailer->send($emailUser);
                        $mailer->send($emailAdmin);
                        $this->addFlash('success', 'Votre commande a bien étais annulez un mail de confirmation viens de vous etres envoyés');
                        return $this->redirectToRoute('order', [], Response::HTTP_SEE_OTHER);
                    } else {
                        $this->addFlash('danger', 'Une erreur est survenue ');
                    }
                }
            }






            return $this->render('user/order.html.twig', [
                'orders' => $orders,
                'xboxs' => $xboxs,
                'countWaiting' => $countWaiting,
                'countRefund' => $countRefund,

            ]);
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }



    //READ
    #[Route('/xbox/{id}', name: 'app_xbox_show')]
    public function show(Xbox $xbox, XboxRepository $xboxRepository, UserRepository $userRepository, CartRepository $cartRepository)
    {
        //Récuperation de toute les photos et explode du string en array
        $picturesFetchBdd = explode('_', $xbox->getPicture());


        $cartProducts = '';

        //Déclaration de l'user
        $user = $this->getUser();
        if (isset($user)) {

            $userId = $user->getId();


            //fetch du panier de l'user pour check si un article est déja dans le panier 
            $cartFetch = $cartRepository->findOneBy(array('userId' => $userId));
            if (isset($cartFetch)) {
                $cartProducts = explode('/', $cartFetch->getProductsId());
            }
        }

        if (!empty($_POST)) {

            $safe = array_map('trim', array_map('strip_tags', $_POST));






            //Ajout de l'id du panier a sur la table user une fois que le panier est créér
            if (isset($safe['addToCart'])) {

                //Vérification que le panier n'existe pas déjà si il existe on ajoute simplement les article
                $cartExist = $cartRepository->findOneBy(array('userId' => $userId));
                if (empty($cartExist)) {
                    $cart = new Cart();

                    $cart->setProductsId($safe['productsId']);
                    $cart->setUserId($userId);
                    $cart->setDate(time());
                    $cart->setAdress($user->getAdress());
                    $cart->setPostal($user->getPostal());
                    $cart->setCity($user->getCity());
                    $cart->setPhone($user->getPhone());
                    $cart->setBuyerFirstname($user->getFirstname());
                    $cart->setBuyerLastname($user->getLastname());



                    $cartRepository->add($cart);

                    //On ajout l'id du cart sur la table user
                    $cartAdded = $cartRepository->findOneBy(array('userId' => $userId));
                    $cartId = $cartAdded->getId();

                    $user->setCart($cartId);
                    $userRepository->add($user);
                } else {
                    //Ajout des article au panier seulement si il n'est pas déja dans le panier 
                    $cartOldProductsId = $cartExist->getProductsId();
                    $cartExplodeProductsId = explode('/', $cartOldProductsId);
                    $errorsCart = [];
                    foreach ($cartExplodeProductsId as $cartId) {
                        if ($safe['productsId'] === $cartId) {
                            $errorsCart[] = 'Cette article est déja dans votre panier';
                        }
                    }

                    if (count($errorsCart) == 0) {



                        //Arrays

                        $cartArrayProductsId = [];
                        $cartArrayPrice = [];

                        //Fetch et explode des colonne ProductsId 
                        $cartOldProductsId = $cartExist->getProductsId();
                        $cartExplodeProductsId = explode('/', $cartOldProductsId);
                        foreach ($cartExplodeProductsId as $cartId) {
                            array_push($cartArrayProductsId, $cartId);
                        }
                        array_push($cartArrayProductsId, $safe['productsId']);



                        $cartNewProductsId = implode('/', $cartArrayProductsId);


                        $cartExist->setProductsId($cartNewProductsId);

                        $cartExist->setDate(time());

                        $cartRepository->add($cartExist);
                    }
                }
                return $this->redirectToRoute('app_xbox_show', ['id' => $xbox->getId()], Response::HTTP_SEE_OTHER);
            }
            //Suppression de l'article du panier depuis la page shop
            if (isset($safe['deleteFromCart'])) {
                $cartDelete = $cartRepository->findOneBy(array('userId' => $userId));
                $cartProductsId = explode('/', $cartDelete->getProductsId());
                $idNotDelete = [];

                //On check tout les id stocker en bdd pour delete seulement celui qui correspond a l'id du form
                foreach ($cartProductsId as $cartIds) {



                    if ($safe['deleteIdCart'] != $cartIds) {
                        $idNotDelete[] = $cartIds;
                    }
                }
                if (!empty($idNotDelete)) {

                    $idsFinal = implode('/', $idNotDelete);
                    $cartDelete->setProductsId($idsFinal);
                    $cartRepository->add($cartDelete);
                } else {
                    $cartRepository->remove($cartDelete);

                    $user->setCart('empty');
                    $userRepository->add($user);
                }
            }
            return $this->redirectToRoute('app_xbox_show', ['id' => $xbox->getId()], Response::HTTP_SEE_OTHER);
        }



        return $this->render('xbox/show.html.twig', [
            'xbox' => $xbox,
            'cartProducts' => $cartProducts,
            'pictures' => $picturesFetchBdd,

        ]);
    }


    //UPDATE Picture
    #[Route('/xbox/{id}/edit/picture', name: 'app_xbox_edit_picture', methods: ['GET', 'POST'])]
    public function editPicture(Request $request, Xbox $xbox, XboxRepository $xboxRepository): Response
    {

        //Récuperation de l'user
        $user = $this->getUser();

        //Condition si l'user est connecté  sinon on redirige vers la connexion
        if (!empty($user)) {
            //Condition si le role est admin sinon on redirige vers la homepage
            if (in_array("ROLE_ADMIN", $user->getRoles())) {
                //Récuperation de toute les photos et explode du string en array
                $picturesFetchBdd = explode('_', $xbox->getPicture());

                //Params

                $notDeletedPicture = [];
                $errorsAddPicture = [];

                //Param pour les vérification de la photo
                $fileMaxSize = 1024 * 1024 * 10; // Taille maximale de l'image en octet
                $fileAllowedMimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']; // Les types mimes attendus
                $fileDirUpload = 'uploads/'; // Chemin de sauvegarde d'image, à partir de là ou je me trouve, 

                if (!empty($_POST)) {


                    //Je securise le formulaire contre les injections SQL en retirant les espace et balise html/php
                    $safe = array_map('trim', array_map('strip_tags', $_POST));


                    if (isset($safe['deletePicture'])) {
                        foreach ($picturesFetchBdd as $pic) {
                            if ($pic != $safe['deletePicture']) {
                                $notDeletedPicture[] = $pic;
                            }
                        }
                        $updatePicture = implode('_', $notDeletedPicture);
                        $xbox->setPicture($updatePicture);
                        $xboxRepository->add($xbox);
                        return $this->redirectToRoute('app_xbox_edit_picture', ['id' => $xbox->getId()], Response::HTTP_SEE_OTHER);
                    }

                    if (isset($safe['backButton'])) {

                        return $this->redirectToRoute('app_xbox_edit', ['id' => $safe['backButton']], Response::HTTP_SEE_OTHER);
                    }
                    if (isset($safe['addPicture'])) {

                        //Verification de la photo [PROVISOIRE]: Cas d'erreurs si 
                        if (!empty($_FILES) && isset($_FILES['picture'])) {

                            $picture = $_FILES['picture'];
                            foreach ($picture['error'] as $pictureError) {
                                if ($pictureError === UPLOAD_ERR_NO_FILE) {
                                    $errorsAddPicture[] = 'L\'affiche du film est obligatoire'; // RENSEIGNER LE MESSAGE D'ERREUR ICI
                                }
                            }

                            foreach ($picture['type'] as $pictureType) {
                                if ($pictureError != UPLOAD_ERR_NO_FILE) {
                                    if (!in_array($pictureType, $fileAllowedMimes)) {
                                        $errorsAddPicture[] = 'le type du fichier n\'est pas autorisé (jpg, gif, png)'; // RENSEIGNER LE MESSAGE D'ERREUR ICI
                                    }
                                }
                            }

                            foreach ($picture['size'] as $pictureSize) {
                                if ($pictureSize > $fileMaxSize) {
                                    $errorsAddPicture[] = 'l\'image est trop volumineux, taille maxi 10 Mo'; // RENSEIGNER LE MESSAGE D'ERREUR ICI
                                }
                            }
                        }
                        if (count($errorsAddPicture) == 0) {


                            // Permet de fabriquer automatiquement le dossier "uploads"
                            // la fonction is_dir verifie que un dossier / fichier est bien dossier
                            if (!is_dir($fileDirUpload)) {
                                mkdir($fileDirUpload, 0777); // make dir = fabrique le dossier
                            }

                            // Array pour envoyé en BDD le nom des photo avec un " / " les separant pour ensuite faire un explode de " / " et les affiché avec un foreach
                            $pictureNameArray = [];

                            // compteur avec une incrementation pour selection le ['tmp_name'][0] puis ['tmp_name'][1] etc
                            $count = 0;

                            foreach ($picture['name'] as $pictureName) {

                                // Permet de récupérer automatiquement l'extension du fichier téléchargé
                                $extPicture = pathinfo($pictureName, PATHINFO_EXTENSION);

                                // Créer un nom de fichier unique
                                $fileNamePicture = uniqid() . '.' . $extPicture;  // Donnera quelque de similaire à 4b3403665fea6.jpg


                                // Sauvegarde mon image
                                $finalFileNamePicture = '';

                                if (move_uploaded_file($picture['tmp_name'][$count], $fileDirUpload . $fileNamePicture)) { // $fileDirUpload.$fileName = "uploads/4b3403665fea6.jpg"
                                    $finalFileNamePicture = $fileDirUpload . $fileNamePicture;
                                    $pictureNameArray[] = $finalFileNamePicture;
                                }

                                $count++;
                            }

                            //Pour chaque photo séléctionner on le rajoute dans l'array  
                            foreach ($pictureNameArray as $pictureAdd) {
                                array_push($picturesFetchBdd, $pictureAdd);
                            }
                            //On transforme l'array en string pour l'envoi en BDD
                            $finalPictureAdd = implode('_', $picturesFetchBdd);

                            $xbox->setPicture($finalPictureAdd);
                            $xboxRepository->add($xbox);
                            return $this->redirectToRoute('app_xbox_edit_picture', ['id' => $xbox->getId()], Response::HTTP_SEE_OTHER);
                        } else {
                            $this->addFlash('danger', implode('<br>', $errorsAddPicture));
                        }
                    }
                }



                return $this->render('xbox/edit_picture.html.twig', [
                    'xbox' => $xbox,
                    'pictures' => $picturesFetchBdd,
                ]);
            } else {
                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }

    //UPDATE
    #[Route('/xbox/{id}/edit', name: 'app_xbox_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Xbox $xbox, XboxRepository $xboxRepository): Response
    {
        //Récuperation de l'user
        $user = $this->getUser();

        //Condition si l'user est connecté  sinon on redirige vers la connexion
        if (!empty($user)) {
            //Condition si le role est admin sinon on redirige vers la homepage
            if (in_array("ROLE_ADMIN", $user->getRoles())) {

                //Params Bdd
                $generationBdd = $xbox->getGeneration();
                $statusBdd = $xbox->getSold();


                //Params
                $generation = [
                    'xboxFat' => 'xboxFat',
                    'xboxSlim' => 'xboxSlim',

                ];




                //Params
                $errorsEditXbox = [];





                // Array des différentes génération de console pour la vérification
                $gen = [
                    'Xbox 360 Fat' => 'Xbox 360 Fat',
                    'Xbox 360 Slim' => 'Xbox 360 Slim',

                ];
                $status = [
                    'to sell' => 'to sell',
                    'SOLD' => 'SOLD',

                ];

                //Param pour les vérification de la photo
                $fileMaxSize = 1024 * 1024 * 10; // Taille maximale de l'image en octet
                $fileAllowedMimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']; // Les types mimes attendus
                $fileDirUpload = 'uploads/'; // Chemin de sauvegarde d'image, à partir de là ou je me trouve, 


                if (!empty($_POST)) {
                    // on supprime les espace et les balise pour sécuriser nos formulaires 
                    $safe = array_map('trim', array_map('strip_tags', $_POST));




                    //Verification du Titre: Cas d'erreurs si il n'est pas rentrer ou depasse 30 caractères 
                    if (!empty($safe['title'])) {
                        if (strlen($safe['title']) > 100) {
                            $errorsEditXbox[] = 'Le titre est trop long : 100 caracteres max ';
                        }
                    } else {
                        $errorsEditXbox[] = 'Veuillez saisir un titre ';
                    }

                    // //Verification de la Description: Cas d'erreurs si elle n'est pas rentrer ou depasse 1000 caractères 
                    if (!empty($safe['description'])) {
                        if (strlen($safe['description']) < 1 || strlen($safe['description']) > 1000) {
                            $errorsEditXbox[] = 'La description doit contenir entre 1 et 1000 caractères ';
                        }
                    } else {
                        $errorsEditXbox[] = 'Veuillez saisir une description ';
                    }
                    //Verification du Prix: Cas d'erreurs si il n'est pas rentrer ou si il n'est pas dans la fourchette 50-500 
                    if (!empty($safe['price'])) {
                        if (!is_numeric($safe['price']) || $safe['price'] < 50 || $safe['price'] > 500) {
                            $errorsEditXbox[] = 'Le prix est invalide ';
                        }
                    } else {
                        $errorsEditXbox[] = 'Veuillez saisir un prix ';
                    }
                    //Verification de la console Gen: Cas d'erreurs si il n'est pas séléctionner et si il n'existe pas dans l'array $gen
                    if (!isset($safe['generationFat']) &&  !isset($safe['generationSlim'])) {
                        $errorsEditXbox[] = 'Veuillez séléctionnez la génération de la console';
                    }

                    if (isset($safe['generationFat'])) {

                        if (!in_array($safe['generationFat'], array_keys($generation))) {
                            $errorsEditXbox[] = 'La génération de la console est invalide';
                        } else {

                            $generationBdd = $safe['generationFat'];
                        }
                    } elseif (isset($safe['generationSlim'])) {
                        if (!in_array($safe['generationSlim'], array_keys($generation))) {
                            $errorsEditXbox[] = 'La génération de la console est invalide';
                        } else {
                            $generationBdd = $safe['generationSlim'];
                        }
                    }

                    //Verification du statut de vente: Cas d'erreurs si il n'est pas séléctionner et si il n'existe pas dans l'array $status
                    if (!isset($safe['toSell']) &&  !isset($safe['sold'])) {
                        $errorsEditXbox[] = 'Veuillez séléctionnez le status de vente de la console';
                    }

                    if (isset($safe['toSell'])) {

                        if (!in_array($safe['toSell'], array_keys($status))) {
                            $errorsEditXbox[] = 'Le status de vente de la console est invalide';
                        } else {

                            $statusBdd = $safe['toSell'];
                        }
                    } elseif (isset($safe['sold'])) {
                        if (!in_array($safe['sold'], array_keys($status))) {
                            $errorsEditXbox[] = 'Le status de vente de la console est invalide';
                        } else {
                            $statusBdd = $safe['sold'];
                        }
                    }



                    if (count($errorsEditXbox) == 0) {




                        $xbox->setTitle($safe['title']);
                        $xbox->setDescription($safe['description']);
                        $xbox->setPrice($safe['price']);
                        $xbox->setGeneration($generationBdd);
                        $xbox->setSold($statusBdd);


                        $xboxRepository->add($xbox);
                        $this->addFlash('success', 'La Console a bien était édité');
                    } else {
                        $this->addFlash('danger', implode('<br>', $errorsEditXbox));
                    }
                }



                return $this->render('xbox/edit.html.twig', [
                    'xbox' => $xbox,
                    'gen' => $gen,
                    'generationBdd' => $generationBdd,
                    'statusBdd' => $statusBdd,

                ]);
            } else {
                return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }

    //Delete
    #[Route('/xbox/{id}/delete', name: 'app_xbox_delete')]
    public function delete(Request $request, Xbox $xbox, XboxRepository $xboxRepository): Response
    {

        //On est redirigé sur cette fonction quand un admin clique sur delete
        $xboxRepository->remove($xbox);
        return $this->redirectToRoute('app_xbox_list', [], Response::HTTP_SEE_OTHER);
    }
}
