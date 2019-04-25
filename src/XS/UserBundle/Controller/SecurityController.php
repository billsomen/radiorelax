<?php /** Created by PhpStorm. User: SOMEN Diego Date: 21/08/2015 Time: 14:35
 */

namespace XS\UserBundle\Controller;

use MainBundle\Document\Node;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use XS\UserBundle\Document\User;
use XS\UserBundle\Form\UserType;

class SecurityController extends Controller{
  public function lockAction(){
    $ser = new \AppBundle\Service\User();
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      'username' => '695456180'
    ));
    $rawData = file_get_contents("php://input");
//    var_dump($rawData);
    $managerx10bot_token = $this->container->getParameter('telegram_edutoolsbot_token');
    $telegram = new Telegram($managerx10bot_token);
    $updates = $telegram->getData();
//    var_dump($updates);

    $config = array(
      'token' => $this->container->getParameter('orange_token')
    );

    $osms = new Osms($config);
    $osms->setVerifyPeerSSL(false);

    $senderAddress = 'tel:+237695456185';
    $receiverAddress = 'tel:+237695456185';
    $message = 'Edutool : Bonsoir! Votre code de confirmation est ';
    //                $response = $osms->sendSMS($senderAddress, $receiverAddress, $message, $senderName);
//    $response = $osms->sendSMS($senderAddress, $receiverAddress, $message);
//        Todo : methode de verouillage de la session de l'utlisateur.
    return $this->render('@XSUser/Security/lock.html.twig');
  }

  public function codeCheckAction(Request $request){
//        Verifier si l'utilisateur actuel a un compte valide
    $user = $this->getUser();
    if(!isset($user)){
      return $this->redirectToRoute('login');
    }
    else {
//                On s'assure que le compte n'est pas encore confirme
      $confirmed = $user->confirmed();
      if (!$confirmed) {
//                    Le compte n'est pas encore confirme
        $this->addFlash('error', "Votre compte n'a pas encore été validé. Merci d'utiliser le code qui vous a été envoyé par SMS");
        return $this->redirectToRoute('validate_user');
      }
      else {
//                    Le compte est deja confirme. On redirige l'utilisateur.
        $this->addFlash('notice', 'Votre compte a déjà été validé. Merci de vous connecter.');
        return $this->redirectToRoute('login');
      }
    }
  }

//    Ici la nouvelle methode de connexion, mode Google.
  public function loginAction(Request $request){
//    $user = new User();
    if($this->getUser() != null){
      return $this->redirectToRoute('radio_relax_coming_soon_homepage');
    }

    $dm = $this->get('doctrine.odm.mongodb.document_manager');

    /*$user = new User();
    $user->setUsername("somen@somen.io");

    $factory = $this->get('security.encoder_factory');
    $encoder = $factory->getEncoder($user);
    $user->getRoles()[] = "ROLE_ADMIN";
    $password = $encoder->encodePassword("somen", $user->getSalt());
    $user->setPassword($password);

    $dm->persist($user);
    $dm->flush();*/


    $session = $request->getSession();
    $session->set('token_login', null);
    $form = $this->createFormBuilder()
      ->add('username', TextType::class, array(
        'required' => true,
        'attr' => array(
          'autofocus' => 'autofocus',
//          'pattern' =>  '^((242|243)[0-9]{6})|((65|66|67|68|69)[0-9]{7})$',
        )
      ))
      ->getForm();

    if($request->isMethod('POST')){
      $form->handleRequest($request);
      if($form->isValid()){
        $data = $form->getData();
        $username = $data['username'];

        $user_checker = new \AppBundle\Service\User();
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $tmp = $user_checker->getUserAnyway($dm, $username);

//        if($telephone_checker->_is_cm_valide($username)){
        if($tmp){
          $session->set('username', $tmp->getId());
          if(!empty($tmp)){
//                    Le compte existe
            $confirmed = $tmp->confirmed();
//            if($confirmed){
            if(1){
//                        Le compte est deja confirme, on genere le token et on continue
              $generator = random_bytes(20);
              $token = md5($generator);
              $session->set('token_login', $token);
              return $this->redirectToRoute('login_end', array(
                'token' => $token
              ));
            }
            else{
//                        Le compte n'est pas encore confirme...
              $this->addFlash('error', "Votre compte n'a pas encore été validé. Merci d'utiliser le code qui vous a été envoyé par SMS. Assistance ? : Contactez-nous au : 695 45 61 85 / 673 00 78 88");
              return $this->redirectToRoute('validate_user');
            }
          }
          else{
//                    Le compte n'existe pas. Cliquer sur : 'Je n'ai pas de compte !'
            $this->addFlash('error', "Votre compte n'existe pas. Cliquez sur : 'Je n'ai pas de compte !' ");
          }
        }
        else{
          $this->addFlash('error', "Identifiant incorrect ou inexistant!");
        }
      }
    }

    $authUtils = $this->get('security.authentication_utils');
    $error = $authUtils->getLastAuthenticationError(true);
//    print_r($error);

    $generator = random_bytes(20);
    $token = md5($generator);
    $session->set('token_login', $token);
    if($request->isXmlHttpRequest())
    {
//                On up*ate juste le mo*ule général
      return $this->container->get('templating')->renderResponse('@XSUser/Security/_ajax_login.html.twig', array(
        'form' => $form->createView(),
        'token' => $token,
        'error' => $error
      ));
    }

    return $this->render('@XSUser/Security/login.html.twig', array(
      'form' => $form->createView(),
      'token' => $token,
      'page' => 'login',
      'error' => $error
    ));
  }

  public function loginEndAction(Request $request, $token){
    $session = $request->getSession();
    $tmp_token = $session->get('token_login');
    if(isset($tmp_token)) {
      if($tmp_token == $token) {
        $username = $session->get('username');
        if(isset($username)){
          $dm = $this->get('doctrine.odm.mongodb.document_manager');

          $user_checker = new \AppBundle\Service\User();
          $tmp = $user_checker->getUserAnyway($dm, $username);
          /*
           $nickname = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
             'username' => ''.$username
           ))->getNickname();*/
          $nickname = $tmp->getNickname();

          $form = $this->createFormBuilder()
            ->add('username', HiddenType::class, array(
              'required' => true,
            ))
            ->getForm()
          ;

          $form->handleRequest($request);
          if($form->isValid()) {
//            We send the Code and set it as Password...
            $data = $form->getData();
            $tmp_username = $data['username'];
            $tmp_user = $dm->getRepository('XSUserBundle:User')->findBy(array(
              'username' => $tmp_username
            ));
            if(isset($tmp_user) and isset($tmp_user[0])){
              $tmp_user = $tmp_user[0];
//              $code = rand(10000, 99999);
              $code = 99;
              $config = array(
                'token' => $this->getParameter('orange_token')
              );
              $osms = new Osms($config);
              $senderAddress = 'tel:+237695456185';
              $receiverAddress = 'tel:+237'.$tmp_username;
              $message = 'Bonsoir '.$tmp_user->getNickname().'! Votre nouveau mot de passe Edutool est '.$code.' !';
              try{
                $osms->sendSMS($senderAddress, $receiverAddress, $message);
//                if (empty($response['error'])) {
//                On modifie le mot de passe de l'utilisateur
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($tmp_user);
                $tmp_pass = $encoder->encodePassword($code, $tmp_user->getSalt());

                $deadline = $this->getParameter('password_rec_deadline');

                $tmp_deadline = new \DateTime('+'.$deadline.' minutes');
                $tmp_user->setTmpPass($tmp_pass);
                $tmp_user->setTmpDeadline($tmp_deadline);
//                    Le message est parti...
//                  On enregistre le flash bag :)
                $this->addFlash('notice', 'Merci de vous connecter en utilisant le mot de passe que vous venez de recevoir par SMS.');

//                On enregistre l'utilisateur...
                $dm->persist($tmp_user);
                $dm->flush();
                $generator = random_bytes(20);
                $token = md5($generator);
                $session->set('token_login', $token);
//                print_r($code);
                return $this->redirectToRoute('login_tmp', array(
                  'token' => $token
                ));
//                }
              }catch(\Exception $exception){
                $this->addFlash('error', "Votre numéro n'est pas valide. Merci d'utiliser un numéro camerounais.");
              }
            }
            else{
              $this->addFlash('error', "Ce compte n'existe pas. Merci de recommencer ou de vous inscrire !");
            }
          }


          if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBER')) {
            $user = $this->getUser();
            //            todo: On commence la verification... :)
            if ($user->confirmed() != null) {
              if ($user->confirmed() == true) {
                return $this->redirectToRoute('xs_user_edit');
              }
            } else {
              //                  On enregistre le flash bag :)
              $this->addFlash('notice', 'Merci de valider votre Compte avec le code qui vous a été envoyé par SMS');
            }
          }

          //On verifie s'il y a des erreurs
          $authUtils = $this->get('security.authentication_utils');
          $error = $authUtils->getLastAuthenticationError();

          $session->set('error_login', $error);

          return $this->render('XSUserBundle:Security:login_end.html.twig', array(
            'form_reset_password' => $form->createView(),
            'nickname' => $nickname,
            'username' => $username,
            'token' => $token,
            'error' => $error
          ));
        }
      }
    }
    $this->addFlash('error', 'Ereur de connexion, merci de recommencer ou de nous contacter au : 695 45 61 85 / 673 00 78 88');
    return $this->redirectToRoute('login');
  }

  public function loginTmpAction(Request $request, $token){
    $session = $request->getSession();
    $tmp_token = $session->get('token_login');
    if(isset($tmp_token)) {
      if($tmp_token == $token) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $this->createFormBuilder()
          ->add('username', TextType::class, array(
            'required' => true,
            'attr' => array(
              'autofocus' => 'autofocus',
              'pattern' =>  '^((242|243)[0-9]{6})|((65|66|67|68|69)[0-9]{7})$',
            )
          ))
          ->add('tmp_pass', PasswordType::class, array(
            'required' => true,
          ))
          ->getForm()
        ;

        $form->handleRequest($request);
        if($form->isValid()) {
          $data = $form->getData();
          $tmp_pass = $data['tmp_pass'];
          $username = $data['username'];
          $tmp_user = $dm->getRepository('XSUserBundle:User')->findBy(array(
            'username' => $username
          ));
          if(isset($tmp_user) and isset($tmp_user[0])){
            $tmp_user = $tmp_user[0];
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($tmp_user);
            $tmp_pass = $encoder->encodePassword($tmp_pass, $tmp_user->getSalt());
            $tmp_deadline_in = $tmp_user->getTmpDeadline();

            $generator = random_bytes(20);
            $token_login = md5($generator);
            if($tmp_deadline_in < new \DateTime()){

              $this->addFlash('error', 'Le mot de passe temporaire a expiré!');
              return $this->redirectToRoute('login_tmp', array(
                'token' => $token_login
              ));
            }
            if(hash_equals($tmp_user->getTmpPass(), $tmp_pass)){
//              C'est Ok, on peut logger l'utilisateur...
//            On MAJ la période de deadline et on élimine le tmp_pass
              $tmp_deadline_in = new \DateTime('+12 hours');
              $tmp_user->setTmpDeadline($tmp_deadline_in);
//              $tmp_user->setTmpPass(null);

              //Handle getting or creating the user entity likely with a posted form
              // The third parameter "main" can change according to the name of your firewall in security.yml
              $token = new UsernamePasswordToken($tmp_user, null, 'main', $tmp_user->getRoles());
              $this->get('security.token_storage')->setToken($token);

              // If the firewall name is not main, then the set value would be instead:
              // $this->get('session')->set('_security_XXXFIREWALLNAMEXXX', serialize($token));
              $this->get('session')->set('_security_main', serialize($token));

              // Fire the login event manually
              $event = new InteractiveLoginEvent($request, $token);
              $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

//              $dm->persist($tmp_user);
//              $dm->flush();
//              On sent l'utilisateur à la page d'accueil
              $this->addFlash('notice', 'Connexion réussie, merci de réinitialiser votre mot de passe dans les 12 heures.');
              $session->set('tmp_pass_token', $token_login);
              return $this->redirectToRoute('xs_education_my_profile', array(
                'tmp_pass_token' => $token_login
              ));
            }
            else{
              $this->addFlash('error', 'Les entrées saisies ne correspondent pas, ou le mot de passe temporaire a expiré!');
            }

          }
          else{
            $this->addFlash('error', 'Ce numéro est inconnu, merci de réessayer!');
            return $this->redirectToRoute('login_tmp', array(
              'token' => $token
            ));
          }
        }

        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();

        return $this->render('XSUserBundle:Security:login_tmp.html.twig', array(
          'form_reset_password' => $form->createView(),
          'token' => $token,
          'error' => $error
        ));

      }
    }

    $this->addFlash('error', 'Nous ne pouvons confirmer votre identité.');
    return $this->redirectToRoute('login');
  }

  public function passwword_forgottenAction(Request $request){
    $session = $request->getSession();
    //On verifie s'il y a des erreurs
    if($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
      $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    }
    else{
      $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
      $session->remove(SecurityContext::AUTHENTICATION_ERROR);
    }
    return $this->render('@XSUser/Security/password_forgotten.html.twig', array(
      'error' => $error
    ));
  }

  public function fbMessengerAction(){
    return $this->render('@XSUser/Security/fb_messenger.html.twig');
  }

  public function passwword_resetAction(Request $request){
    return $this->render('@XSUser/Security/password_reset.html.twig');
  }

  public function validateAction(Request $request){
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $session = $request->getSession();
    $username = $session->get('username');

    $user_name2 = null;

    if(isset($username)){
      $usert = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
        'username' => ''.$username
      ));
      if(!empty($usert)){
        $user_name2 = $username;
      }
    }


    $form = $this->createFormBuilder(array('username'=>$user_name2))
      ->add('username', TextType::class, array(
        'required' => true,
        'attr' => array(
          'autofocus' => 'autofocus',
          'pattern' =>  '^((242|243)[0-9]{6})|((65|66|67|68|69)[0-9]{7})$',
        )
      ))
      ->add('confirmationCode', TextType::class, array(
        'required' => true
      ))
      ->getForm();

    $form->handleRequest($request);
    if($form->isValid()){
      $data = $form->getData();
      $username = $data['username'];
      $confirmation = $data['confirmationCode'];
      $tmp = $dm->getRepository('XSUserBundle:User')->findOneByUsername($username);
      if(!empty($tmp)){
//                Le compte existe bel et bien.
//                On s'assure que le compte n'est pas encore confirme
        $confirmed = $tmp->confirmed();
        if(!$confirmed) {
//                    Le compte n'est pas encore confirme
          if ($tmp->getConfirmationCode() == $confirmation) {
//                    Le code correspond.
//                Ce n'est que dans ce cas, qu'on valide le compte et demande a l'utilisateur de se connecter pour commencer a utiliser l'application :)
            $tmp->confirm();
            $dm->persist($tmp);
            $dm->flush();
            $this->addFlash('notice', 'Votre compte a été validé avec succès ! Vous pouvez à présent vous connecter.');
            return $this->redirectToRoute('login');
//            return $this->redirectToRoute('login');
          }
          else {
//                    Le code ne correspond pas. On recharge la page.
            $this->addFlash('error', 'Le Code saisi ne correspond pas. Merci de recommencer.');
            return $this->redirectToRoute('validate_user');
          }
        }
        else{
//                    Le compte est deja confirme. On redirige l'utilisateur.
          $this->addFlash('notice', 'Votre compte a déjà été validé. Merci de vous connecter.');
          return $this->redirectToRoute('login');
        }
      }
      else{
//                On enregistre le flash bag :)
        $this->addFlash('error', 'Le Compte : '.$username.' est introuvable. Merci de créer un nouveau compte.');
        return $this->redirectToRoute('signin');
      }
    }

    return $this->render('@XSUser/Security/validate_user.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function isUserExist(User $user){
//    Vérifie si 'lutilisateur existe déjà dans la base de données
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    return false; empty(
      $dm->getRepository("XSUserBundle:User")->findOneBy(array(
        'username' => $user->getUsername()
      ))
    );
  }

  public function signinAction(Request $request, $_locale){
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $user = new User();

    $form = $this->createForm(UserType::class, $user);
    $status_message = null;
    if($request->isMethod('POST')){
      $form->handleRequest($request);
      if($form->isValid()){
        if(!$this->isUserExist($user)){
          $user->setConfirmed(false);
          $code = rand(10000, 99999);
          $user->setConfirmationCode($code);
          $user->setEmail($user->getUsername());

          $factory = $this->get('security.encoder_factory');
          $encoder = $factory->getEncoder($user);
          $password = $encoder->encodePassword($code, $user->getSalt());
          $user->setPassword($password);

//          We integrate the MLM node of the user
          $node = new Node();
          $user->setNode($node);

//          On gère les référençes
          if(!empty($user->getGodfatherNamespace())){
            $god_father = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
              "profiles.artist.namespace" => $user->getGodfatherNamespace(),
//              'roles' => "ROLE_ARTIST"
            ));
            if(!empty($god_father)){
//              Le parain existe :)
              $god_father->getNode()->addChild($node);
              $dm->persist($god_father);
            }
          }

          $dm->persist($user);
          $dm->persist($node);
          $dm->flush();

          $status = 'notice';
          $status_message = $this->get('translator')->trans('xs_user.signin.flashbag.mail.complete');
          $this->addFlash($status, $status_message);
          try{
//              On émet le Mail
            $receiver = $user->getUsername();
            $receiver = "ngongangsomen@gmail.com";
            $sender = $this->getParameter("mailer_user");
            $message = \Swift_Message::newInstance()
              ->setSubject($status_message)
              ->setFrom([$sender => $this->getParameter("app_name")])
              ->setTo(array($receiver, $this->getParameter("mailer_user")))
              ->setBody(
                $this->renderView(
                  '@Main/_messages/signin.html.twig',array(
                    'user' => $user,
                    '_locale' => $_locale,
                    'password' => $code
                  )
                ),
                'text/html'
              )
            ;
            $r = $this->get('mailer')->send($message);
//            Mail sent
            $status_message = $this->get('translator')->trans('flashbags.mail.success');
            $this->addFlash($status, $status_message);

            return $this->redirectToRoute("login");

          }catch(\Exception $exception){
            $status = 'error';
            $status_message = $this->get('translator')->trans('flashbags.mail.error');
            $this->addFlash($status, $status_message);
          }
        }
        else{
          $status = 'error';
          $status_message = $this->get('translator')->trans('xs_user.user.is.already_exist');
          $this->addFlash($status, $status_message);
        }
      }
      else{
        $status = 'error';
//        $status_message = $this->get('translator')->trans('flashbags.form.bad');
        $status_message = $this->get('translator')->trans('xs_user.user.is.already_exist');
        $this->addFlash($status, $status_message);
      }
    }

    $authUtils = $this->get('security.authentication_utils');
    $error = $authUtils->getLastAuthenticationError(true);

    return $this->render('@XSUser/Security/signin.html.twig', array(
      'form' => $form->createView(),
      'page' => 'signin',
      '_locale' => $_locale,
      'error' => $error
    ));
  }

}