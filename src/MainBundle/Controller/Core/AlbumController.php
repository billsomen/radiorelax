<?php

namespace MainBundle\Controller\Core;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XS\AfrobankBundle\Document\EntityAccount;

class AlbumController extends Controller
{
  public function showAction($namespace, $id)
  {
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $album = $dm->getRepository("MainBundle:Album")->findOneBy(array(
      'id' => $id
    ));

    $album->setAccount(new EntityAccount());
    $dm->persist($album);
    $dm->flush();
    return $this->render('@Main/Core/Album/show.html.twig', array(
      'album' => $album,
      'artist' => $album->getArtist()->getProfiles()->getArtist()
    ));
  }
}