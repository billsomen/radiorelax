<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
  public function allAction(){
//    Affichage de tous les utilisateurs par groupe :
    $dm = $this->get("doctrine.odm.mongodb.document_manager");
//    1. Auditeurs (non artistes)
    $query = $dm->createQueryBuilder("XSUserBundle:User");

    $query
      ->field('roles')
      ->in(["ROLE_USER"])
      ->notIn(["ROLE_ARTIST"])
    ;
    $listeners = $query->getQuery()->execute();

//    2. Artistes
    $artists = $dm->getRepository("XSUserBundle:User")->findBy(array(
      'roles' => "ROLE_ARTIST"
    ));
//    3. Demandeurs de requête artiste
    $requesters = $dm->getRepository("XSUserBundle:User")->findBy(array(
      'artist_request' => "ROLE_USER"
    ));

    return $this->render("@Main/Admin/User/all.html.twig", array(
      "listeners" => $listeners,
      "artists" => $artists,
      "requesters" => $requesters
    ));
  }

  /**
   * @param $id string The id of the user to make artist
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function makeArtistAction($id)
  {
//    Make the user artist
    $dm = $this->get("doctrine.odm.mongodb.document_manager");
    $user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      "id" => $id
    ));
    if(!empty($user)){
      $user->makeArtist();
//    Envoyer un mail :)
      $receiver = $user->getUsername();
      $sender = $this->getParameter("mailer_user");
      $message = \Swift_Message::newInstance()
        ->setSubject("Profil artiste validé.")
//              ->setFrom([$sender => $this->getParameter("app_name")])
        ->setFrom([$sender => $this->getParameter("app_name")])
        ->setTo(array($receiver))
        ->setBody(
          "Votre profil artiste est activé. Connectez-vous à www.radiorelax.io pour en profiter."
          ,
          'text/html'
        )
      ;

      try{
        $r = $this->get('mailer')->send($message);
        $this->addFlash("notice", "L'utilisateur est maintenant artiste");
        //      On enregistre le çompte
        $dm->persist($user);
        $dm->flush();
      }catch (\Exception $exception){
        $this->addFlash("error", "Erreur lors de l'envoi du mail à l'utilisateur.");
      }
    }
    else{
      $this->addFlash("error", "L'utilisateur n'existe pas!");
    }

    return $this->redirectToRoute("admin_users_homepage");
  }

  /**
   * @param $id string The id of the user to make artist
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function removeArtistAction($id)
  {
//    Make the user artist
    $dm = $this->get("doctrine.odm.mongodb.document_manager");
    $user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      "id" => $id
    ));
    if(!empty($user)){
      $user->removeArtist();
//    Envoyer un mail :)
      $receiver = $user->getUsername();
      $sender = $this->getParameter("mailer_user");
      $message = \Swift_Message::newInstance()
        ->setSubject("Accès artiste retiré.")
//              ->setFrom([$sender => $this->getParameter("app_name")])
        ->setFrom([$sender => $this->getParameter("app_name")])
        ->setTo(array($receiver))
        ->setBody(
          "Votre accès au profil artiste a été supprimé. Contactez-nous s'il s'agit d'une erreur."
          ,
          'text/plain'
        )
      ;

      try{
        $r = $this->get('mailer')->send($message);
        $this->addFlash("notice", "L'accès artiste à été retiré à l'utilisateur");
        //      On enregistre le çompte
        $dm->persist($user);
        $dm->flush();
      }catch (\Exception $exception){
        $this->addFlash("error", "Erreur lors de l'envoi du mail à l'utilisateur.");
      }
    }
    else{
      $this->addFlash("error", "L'utilisateur n'existe pas!");
    }

    return $this->redirectToRoute("admin_users_homepage");
  }

  /**
   * @param $id string The id of the user to cancel request
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function removeRequestArtistAction($id)
  {
//    remove artist request
    $dm = $this->get("doctrine.odm.mongodb.document_manager");
    $user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      "id" => $id
    ));
    if(!empty($user)){
      $user->removeArtist();
//    Envoyer un mail :)
      $receiver = $user->getUsername();
      $sender = $this->getParameter("mailer_user");
      $message = \Swift_Message::newInstance()
        ->setSubject("Demande de profil artiste retirée
        .")
//              ->setFrom([$sender => $this->getParameter("app_name")])
        ->setFrom([$sender => $this->getParameter("app_name")])
        ->setTo(array($receiver))
        ->setBody(
          "Votre demande d'accès au profil artiste a été rejetée. Contactez-nous s'il s'agit d'une erreur."
          ,
          'text/plain'
        )
      ;

      try{
        $r = $this->get('mailer')->send($message);
        $this->addFlash("notice", "La demande d'accès au profil artiste à été supprimé");
        //      On enregistre le çompte
        $dm->persist($user);
        $dm->flush();
      }catch (\Exception $exception){
        $this->addFlash("error", "Erreur lors de l'envoi du mail à l'utilisateur.");
      }
    }
    else{
      $this->addFlash("error", "L'utilisateur n'existe pas!");
    }

    return $this->redirectToRoute("admin_users_homepage");
  }
}
