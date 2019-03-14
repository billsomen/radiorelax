<?php

namespace MainBundle\Controller\Core;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArtistController extends Controller
{
  public function indexAction()
  {
//    All the artists to Manage
    $dm = $this->get("doctrine_mongodb");
    $users = $dm->getRepository("XSUserBundle:User")->findAll();
    return $this->render("MainBundle:Core/Artist:all.html.twig",
      array(
        'users' => $users,
      )
    );
  }

  public function showAction($namespace)
  {
    //    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      'profiles.artist.namespace' => $namespace
    ));
    if(!empty($user)){
      return $this->render('MainBundle:Core/Artist:show.html.twig', array(
        'user' => $user,
      ));
    }

    $this->addFlash('error', "Artiste inexistant");
    return $this->redirectToRoute('radio_relax_core_artists_homepage');
  }
}
