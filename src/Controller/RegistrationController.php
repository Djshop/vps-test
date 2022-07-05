<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer, UserRepository $userRepository): Response
    {


        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $errors = [];
        if (!empty($_POST)) {

            //on Securise les formulaire en supprimant les espace et les balise 
            $safeRegister = array_map('trim', array_map('strip_tags', $_POST['registration_form']));

            unset($_POST['registration_form']);
            $safe = array_map('trim', array_map('strip_tags', $_POST));


            //Verification que le mail n'est pas déja enregistrer
            $fetchUser = $userRepository->findOneBy(array('email' => $safeRegister['email']));

            if (!empty($fetchUser)) {
                $errors[] = 'Un compte a déjà étais créer avec cette adresse email';
            }
            //Verification du prénom : Cas d'erreurs si il n'est pas rentrer ou depasse 50 caractères 
            if (!empty($safe['firstname'])) {

                if (strlen($safe['firstname']) > 50) {
                    $errors[] = 'Votre prénom est trop long ';
                }
            } else {
                $errors[] = 'Veuillez saisir votre prénom ';
            }
            //Verification du nom : Cas d'erreurs si il n'est pas rentrer ou depasse 50 caractères 
            if (!empty($safe['lastname'])) {

                if (strlen($safe['lastname']) > 50) {
                    $errors[] = 'Votre nom est trop long  ';
                }
            } else {
                $errors[] = 'Veuillez saisir votre nom  ';
            }



            //Verif email
            if (!empty($safeRegister['email'])) {
                if (strlen($safeRegister['email']) > 50) {
                    $errors[] = 'Votre email est trop long';
                } elseif (!filter_var($safeRegister['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Cette adresse email est invalide';
                }
            } else {
                $errors[] = 'Veuillez saisir une adresse email';
            }

            //Verif Mot de passe
            if (!empty($safeRegister['plainPassword'])) {
                if (preg_match('/\s/', $safeRegister['plainPassword'])) {

                    $errors[] = "Votre mot de passe contient des espaces";
                } else {

                    if (strlen($safeRegister['plainPassword']) <= '7') {
                        $errors[] = "Votre mot de passe doit contenir au minimum 8 caractères";
                    }
                    if (!preg_match("#[0-9]+#", $safeRegister['plainPassword'])) {
                        $errors[] = "Votre mot de passe doit contenir au minimum 1 chiffre";
                    }
                    if (!preg_match("#[A-Z]+#", $safeRegister['plainPassword'])) {
                        $errors[] = "Votre mot de passe doit contenir au minimum 1 majuscule";
                    }
                    if (!preg_match("#[a-z]+#", $safeRegister['plainPassword'])) {
                        $errors[] = "Votre mot de passe doit contenir au minimum 1 minuscule";
                    }
                }
            } else {
                $errors[] = "Veuillez saisir un mot de passe";
            }
            //Verif confirmation de Mot de passe
            if (!empty($safe['confirm_password'])) {
                if ($safeRegister['plainPassword'] !=  $safe['confirm_password']) {
                    $errors[] = 'Vos mot de passes ne sont pas identiques';
                }
            } else {
                $errors[] = 'Veuillez confirmez votre mot de passe';
            }

            // Verification téléphone
            if (!empty($safe['phone'])) {
                if (preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", $safe['phone'])) {
                    $cut_char = array("-", ".", " ");
                    $phoneBdd = str_replace($cut_char, "", $safe['phone']);
                    $telephone = chunk_split($phoneBdd, 2, "\r");
                } else {
                    $errors[] = "Votre mobile: " . $safe['phone'] . "n'est pas un numéro valide";
                }
            } else {
                $errors[] =  "Veuillez saisir votre Mobile";
            }

            //Verification de l'adresse ligne 1
            if (!empty($safe['adress_one'])) {
                if (strlen($safe['adress_one']) < 1 || strlen($safe['adress_one']) > 100) {
                    $errors[] = 'Votre adresse ' . $safe['adress_one'] . 'n\'est pas valide';
                } else {
                    $adress_one = $safe['adress_one'];
                }
            } else {
                $errors[] =  "Veuillez saisir votre adresse";
            }
            //Verification de l'adresse ligne 2
            if (!empty($safe['adress_two'])) {

                if (strlen($safe['adress_two']) < 1 || strlen($safe['adress_two']) > 60) {
                    $errors[] = 'Votre adresse ' . $safe['adress_two'] . 'n\'est pas valide';
                } else {
                    $adress_two = $safe['adress_two'];
                }
            }

            //verification de la ville
            if (!empty($safe['city'])) {
                if (strlen($safe['city']) < 1 || strlen($safe['city']) > 40) {
                    $errors[] = 'Votre ville ' . $safe['city'] . 'n\'est pas valide';
                }
            } else {
                $errors[] =  "Veuillez saisir votre ville";
            }


            //verification du code postal
            if (!empty($safe['postal'])) {
                if (!is_numeric($safe['postal']) || strlen($safe['postal']) < 2 || strlen($safe['postal']) > 5) {
                    $errors[] = 'Votre code postal ' . $safe['postal'] . 'n\'est pas valide';
                }
            } else {
                $errors[] =  "Veuillez saisir votre code postale";
            }


            if (count($errors) == 0) {

                if (isset($adress_two)) {
                    $adress = $adress_one . ' ' . $adress_two;
                } else {
                    $adress = $adress_one;
                }

                //Création du premier Admin pour ne pas avoir a le rentré manuellement , si la clé (Admin280295) est rentré a la place du mot de passe et si il n'y a pas encore de compte admin le compte créer obtiendra donc le premier role d'admin.
                //Récupération de tout les user
                $allUsers = $userRepository->findAll();
                //Array pour stocker les user par rapport a leur roles
                $admins = [];

                // Pour chaque user si son role est Admin il est stocker dans l'array admins et pareil pour l'user avec l'array users
                foreach ($allUsers as $userCount) {
                    $userRoles = $userCount->getRoles();
                    if ($userRoles[0] == "ROLE_ADMIN") {
                        array_push($admins, $userCount);
                    }
                }


                $key = 'Admin280295';

                //Condition si la clé est rentré
                if ($safeRegister['plainPassword'] == $key) {
                    //Condition si il n'y a pas d'admin
                    if (count($admins) == 0) {
                        $role = ['ROLE_ADMIN'];
                    } else {
                        $role = ['ROLE_USER'];
                    }
                } else {
                    $role = ['ROLE_USER'];
                }




                $user->setRoles($role);
                $user->setCart('empty');
                $user->setAdress($adress);
                $user->setPostal($safe['postal']);
                $user->setCity($safe['city']);
                $user->setPhone($safe['phone']);
                $user->setFirstname($safe['firstname']);
                $user->setLastname($safe['lastname']);
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from('djemshop31.noreply@gmail.com')
                        ->to($user->getEmail())
                        ->subject('Veuillez confirmer votre email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );




                $this->addFlash('success', 'Votre compte a bien été créé. Un mail de vérification vous a été envoyé. Veuillez confirmer votre compte afin de pouvoir passez commande.');
                return $this->redirectToRoute('app_register');
            } else {
                $this->addFlash('danger', implode('<br>', $errors));
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('login');
    }
}
