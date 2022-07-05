<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Security\EmailVerifier;
use App\Entity\Xbox;
use App\Entity\User;
use App\Form\XboxType;
use App\Repository\XboxRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class HomeController extends AbstractController
{

    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }




    //Homepage
    // #[Route('/', name: 'home')]
    // public function home(XboxRepository $xboxRepository)
    // {
    //     //
    //     $user = $this->getUser();
    //     $userRole = '';

    //     //fetch des xbox pour affichage des dernierers sortit
    //     $xboxs = $xboxRepository->findAll();

    //     // Séléction des 3derniers console
    //     $product1 = array_key_last($xboxs);

    //     $product2Id = $product1 - 1;
    //     $product3Id = $product2Id - 1;
    //     $lastProduct = [];
    //     array_push($lastProduct, $product1, $product2Id, $product3Id);







    //     if (!empty($user)) {

    //         $userRole = $user->getRoles();
    //     }

    //     return $this->render('home/home.html.twig', [
    //         'xboxs' => $xboxs,
    //         'lastProduct' => $lastProduct,
    //     ]);
    // }


    //Contact 
    #[Route('/contact', name: 'app_contact')]
    public function contact(XboxRepository $xboxRepository, MailerInterface $mailer)
    {
        if (!empty($_POST)) {
            $safe = array_map('trim', array_map('strip_tags', $_POST));
          
            $errorsContact = [];

            $user = $this->getUser();



            //Vérification qu'un sujet est choisis
            if (!empty($safe['subject'])) {

                //Cas d'erreurs si il n'est pas rentrer ou depasse 100 caractères 
                if (strlen($safe['subject']) < 1 || strlen($safe['subject']) > 100) {
                    $errorsContact[] = 'Le sujet ne doit pas depasser 100 caractères ';
                }
            } else {
                $errorsContact[] = 'Veuillez choisir un sujet ';
            }

            //Verification sujet Autre
            if ($safe['subject'] == 'other') {
                if (!empty($safe['other'])) {
                    if (strlen($safe['other']) > 100) {
                        $errorsContact[] = 'Le sujet ne doit pas depasser 100 caractères ';
                    }
                } else {
                    $errorsContact[] = 'Veuillez saisir un sujet ';
                }
            }

            //Vérification du text
            //Cas d'erreurs si il n'est pas rentrer ou depasse 500 caractères 
            if (!empty($safe['textAreaContact'])) {
                if (strlen($safe['textAreaContact']) < 50 || strlen($safe['textAreaContact']) > 500) {
                    $errorsContact[] = 'Votre mail est invalide : longueur non respecter ';
                }
            } else {
                $errorsContact[] = 'Veuillez saisir un mail a envoyé ';
            }


            //Verification du nom de l'expediteur
            if (!empty($safe['contactName'])) {

                 //Cas d'erreurs si il n'est pas rentrer ou depasse 100 caractères 
                 if (strlen($safe['contactName']) < 1 || strlen($safe['contactName']) > 100) {
                    $errorsContact[] = 'Votre ne doit pas depasser 100 caractères ';
                } else {
                    $nameSender = $safe['contactName'];
                }
            }
            //Verification du mail de l'expediteur
            if (!empty($safe['emailSender'])) {

                if (!filter_var($safe['emailSender'], FILTER_VALIDATE_EMAIL)) {
                    $errorsContact[] = 'Cette adresse email est invalide';
                } else {
                    $mailSender = $safe['emailSender'];
                }
            }

            if (count($errorsContact) == 0) {



                if (isset($user)) {
                    $mailSender = $user->getEmail();
                    $userFirstname=$user->getFirstname();
                    $userLastname=$user->getLastname();
                    $nameSender = $userFirstname .' '. $userLastname;
                }
                if (empty($safe['other'])) {

                    $subject = $safe['subject'] . ' : ' .$nameSender .' '. $mailSender;
                } else {
                    $subject = $safe['other'] . ' : ' . $nameSender .' '.$mailSender;
                }


                $email = (new Email())
                    ->from('djemshop31.noreply@gmail.com')
                    ->to('djemshop31@gmail.com')
                    //->cc($mailSender)
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject($subject)
                    ->text($safe['textAreaContact']);


                $mailer->send($email);
                $this->addFlash('success', 'Votre mail a bien étais envoyé nous vous répondrons le plus rapidement possible !');
            } else {

                $this->addFlash('danger', implode('<br>', $errorsContact));
            }
        }


        return $this->render('home/contact.html.twig');
    }

    //About
    #[Route('/about', name: 'app_about')]
    public function about(XboxRepository $xboxRepository)
    {
      return $this->render('home/about.html.twig');
    }

    //RGH
    #[Route('/rgh', name: 'app_rgh')]
    public function rgh(XboxRepository $xboxRepository)
    {
      return $this->render('home/rgh.html.twig');
    }

    //Livraison
    #[Route('/livraisons', name: 'app_livraisons')]
    public function livraisons(XboxRepository $xboxRepository)
    {
      return $this->render('home/livraisons.html.twig');
    }

    //Paiement
    #[Route('/paiment', name: 'app_paiment')]
    public function paiment(XboxRepository $xboxRepository)
    {
      return $this->render('home/paiment.html.twig');
    }


    //User Profil
    #[Route('/account', name: 'app_account')]
    public function account(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        //
        $user = $this->getUser();

        //Condition si l'user est connecté sinon on redirige vers la connexion
        if (!empty($user)) {
            $userPhone = $user->getPhone();
            $userCity = $user->getCity();
            $userPostal = $user->getPostal();
            $userAdress = $user->getAdress();

            if (!empty($_POST)) {
                $safe = array_map('trim', array_map('strip_tags', $_POST));

                if (isset($safe['editAdress'])) {
                    $errorsAdress = [];


                    // Verification téléphone
                    if ($safe['phone'] != $userPhone) {
                        if (preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", $safe['phone'])) {
                            $cut_char = array("-", ".", " ");
                            $phoneBdd = str_replace($cut_char, "", $safe['phone']);
                            $telephone = chunk_split($phoneBdd, 2, "\r");
                        } elseif (empty($safe['phone'])) {
                            $errorsAdress[] =  "Veuillez saisir votre Mobile";
                        } else {
                            $errorsAdress[] = "Votre mobile: " . $safe['phone'] . "n'est pas un numéro valide";
                        }
                    }

                    //Verification de l'adresse 
                    if ($safe['adress'] != $userAdress) {
                        if (strlen($safe['adress']) < 1 || strlen($safe['adress']) > 100) {
                            $errorsAdress[] = 'Votre adresse ' . $safe['adress'] . 'n\'est pas valide';
                        }
                    }


                    //verification de la ville
                    if ($safe['city'] != $userCity) {
                        if (strlen($safe['city']) < 1 || strlen($safe['city']) > 40) {
                            $errorsAdress[] = 'Votre ville ' . $safe['city'] . 'n\'est pas valide';
                        }
                    }


                    //verification du code postal
                    if ($safe['postal'] != $userPostal) {
                        if (!is_numeric($safe['postal']) || strlen($safe['postal']) < 2 || strlen($safe['postal']) > 5) {
                            $errorsAdress[] = 'Votre code postal ' . $safe['postal'] . 'n\'est pas valide';
                        }
                    }

                    //Verification que au moins un champs est modifié
                    if ($safe['postal'] == $userPostal && $safe['city'] == $userCity && $safe['adress'] == $userAdress && $safe['phone'] == $userPhone) {
                        $errorsAdress[] = 'Veuillez saisir au moins un champs a modifié ';
                    }

                    //Verification qu'il n'y a pas d'erreurs pour mettre a jour l'adress de l'user en BDD
                    if (count($errorsAdress) == 0) {

                        //Set seulement des champs modifié

                        if ($safe['adress'] != $userAdress) {
                            $user->setAdress($safe['adress']);
                        }
                        if ($safe['phone'] != $userPhone) {
                            $user->setPhone($safe['phone']);
                        }
                        if ($safe['city'] != $userCity) {
                            $user->setCity($safe['city']);
                        }
                        if ($safe['postal'] != $userPostal) {
                            $user->setPostal($safe['postal']);
                        }

                        $entityManager->persist($user);
                        $entityManager->flush();

                        $this->addFlash('success', 'Votre adresse a bien été mise a jour.');
                    } else {
                        $this->addFlash('danger', implode('<br>', $errorsAdress));
                    }
                }
                if (isset($safe['resendVerified'])) {
                    $errorsResend = [];

                    if ($safe['resendVerified'] != 'resendVerified') {
                        $errorsResend[] = 'Une erreur est survenue veuillez rafraichir la page pour un nouvelle essai';
                    }

                    if (count($errorsResend) == 0) {
                        $user = $this->getUser();
                        $this->emailVerifier->sendEmailConfirmation(
                            'app_verify_email',
                            $user,
                            (new TemplatedEmail())
                                ->from('djemshop31.noreply@gmail.com')
                                ->to($user->getEmail())
                                ->subject('Veuillez confirmer votre email')
                                ->htmlTemplate('registration/confirmation_email.html.twig')
                        );




                        $this->addFlash('success', 'Votre mail de vérification vous a été envoyé. Veuillez confirmer votre compte afin de pouvoir passez commande.');
                    } else {
                        $this->addFlash('danger', implode('<br>', $errorsResend));
                    }
                }
                if (isset($safe['editPassword'])) {
                    $errorsPassword = [];

                    // $userPassword = $user->getPassword();
                    $plaintextPassword = $safe['oldPassword'];



                    //Verification que le mot de passe actuel correspond bien au mot de passe stocker en BDD en utilisant la method isPasswordValid en lui donnant comme parametre que le mot de passe stocker est hascher 
                    if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {
                        $errorsPassword[] = 'Votre mot de passe actuel n\'est pas valide ';
                    }

                    //Verif Mot de passe
                    if (preg_match('/\s/', $safe['newPassword'])) {

                        $errorsPassword[] = "Votre mot de passe contient des espaces";
                    } else {

                        if (strlen($safe['newPassword']) <= '8') {

                            $errorsPassword[] = "Votre mot de passe doit contenir au minimum 8 caractères";
                        }
                        if (!preg_match("#[0-9]+#", $safe['newPassword'])) {
                            $errorsPassword[] = "Votre mot de passe doit contenir au minimum 1 chiffre";
                        }
                        if (!preg_match("#[A-Z]+#", $safe['newPassword'])) {
                            $errorsPassword[] = "Votre mot de passe doit contenir au minimum 1 majuscule";
                        }
                        if (!preg_match("#[a-z]+#", $safe['newPassword'])) {
                            $errorsPassword[] = "Votre mot de passe doit contenir au minimum 1 minuscule";
                        }
                    }
                    //Verif confirmation de Mot de passe
                    if ($safe['newPassword'] !=  $safe['confirmPassword']) {
                        $errorsPassword[] = 'Vos mot de passes ne sont pas identiques';
                    }

                    //Si il n'y a pas d'erreurs on modifie le mot de passe de l'user
                    if (count($errorsPassword) == 0) {
                        $user->setPassword(
                            $passwordHasher->hashPassword(
                                $user,
                                $safe['newPassword']
                            )
                        );

                        $entityManager->persist($user);
                        $entityManager->flush();



                        return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
                        $this->addFlash('success', 'Votre mot de passe a bien été mise a jour.');
                    } else {
                        $this->addFlash('danger', implode('<br>', $errorsPassword));
                    }
                }
            }


            return $this->render('user/account.html.twig');
        } else {
            return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
        }
    }
}
