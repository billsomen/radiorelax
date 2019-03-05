<?php

namespace RadioRelax\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AlbumController extends Controller
{
  public function showAction($namespace, $id)
  {
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $album = $dm->getRepository("RadioRelaxCoreBundle:Album")->findOneBy(array(
      'id' => $id
    ));
    return $this->render('@RadioRelaxCore/Album/show.html.twig', array(
      'album' => $album,
      'artist' => $album->getArtist()->getArtist()
    ));
  }
}
