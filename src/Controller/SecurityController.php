<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Security\LoginFormAuthenticator;
use App\Service\basket;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(SessionInterface $session, AuthenticationUtils $authenticationUtils)
    {
        //IF LOGGED IN GO TO CHECKOUT
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($user != "anon."){
            return $this->redirectToRoute('checkout');
        }

        //GET LOGIN ERROR
        $error = $authenticationUtils->getLastAuthenticationError();
        //GET LAST USERNAME
        $lastUsername = $authenticationUtils->getLastUsername();

        //HEADER
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);
        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('security/login.html.twig', [
            'test'=>'testString',
            'promotions'=> $promotions,
            "basketQuantity" => $basketQuantity,
            "last_username" => $lastUsername,
            "error" => $error
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){
    }

    /**
     * @Route ("/register", name="register")
     */
    public function register(MailerInterface $mailer, SessionInterface $session, UserPasswordEncoderInterface $passwordEncoder, Request $request, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator){

        //Create new account
        if ($request->isMethod('POST')) {
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setFirstName($request->request->get('firstName'));
            $user->setLastName($request->request->get('lastName'));
            $user->setAddress($request->request->get('address'));
            $user->setPostcode($request->request->get('postcode'));

            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $request->request->get('password')
            ));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //Send registration email
            $email = (new TemplatedEmail())
                ->from(new Address('no-reply@thet24store.com', 'The T-24 Team'))
                ->to(new Address($user->getEmail(), $user->getFirstName()))
                ->subject('Welcome!')
                ->htmlTemplate('email/welcome.html.twig');

            $mailer->send($email);

            //Redirect
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        //Header
        $basket = new basket(0);
        $basketQuantity = $basket->getBasketQuantity($session);
        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('security/register.html.twig',[
            'promotions'=> $promotions,
            "basketQuantity" => $basketQuantity,
            ]);
    }
}
