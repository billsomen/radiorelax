<?php

namespace MainBundle\Controller;

use RadioRelax\AdminBundle\Form\AlbumType;
use RadioRelax\AdminBundle\Form\ArtistType;
use RadioRelax\CoreBundle\Document\Album;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AlbumController extends Controller
{
  public function indexAction($name)
  {
    return $this->render('', array('name' => $name));
  }

  public function newAction($id_artist, Request $request)
  {
    //    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      'id' => $id_artist
    ));
    if(!empty($user)){
//      $album = $user->getAlbum();
      $album = new Album();
      $form = $this->createForm(AlbumType::class, $album);

      if($request->isMethod('post')){
        $form->handleRequest($request);
        if($form->isValid()){
//          On enregistre et push l'image
          $tmp_name = $_FILES['profile']['tmp_name'];
          $params_clnry = array(
            'api_key' => $this->getParameter('cloudinary_api_key'),
            'cloud_name' => $this->getParameter('cloudinary_cloud_name'),
            'api_secret' => $this->getParameter('cloudinary_api_secret'),
//            'preset' => $this->getParameter('cloudinary_preset'),
//            "resource_type" => "raw",
          );

          $res["public_id"] = 0;
          if(!empty($tmp_name)){
            try{
              $filename = time();
              \Cloudinary::config($params_clnry);
              $res = \Cloudinary\Uploader::upload($tmp_name, array(
                "resource_type" => "auto",
                "public_id" => "TEST_RR/".$user->getId()."/images/albums/".$filename
              ));
              unlink($tmp_name);
              $album->setProfile($res['public_id']);
            }
            catch(\Exception $exception){
              $this->addFlash("error", "CDN non Disponible!");
              return $this->redirectToRoute("radio_relax_admin_artists_show", array(
                "id" => $id_artist
              ));
            }
          }

          $album->setArtist($user);
          $user->getArtist()->getAlbums()->add($album);

          $this->addFlash("notice", "Mise à jour des informations de l'album terminée!");
          $dm->persist($user);
          $dm->persist($album);
          $dm->flush();
          return $this->redirectToRoute("radio_relax_admin_artists_show", array(
            "id" => $id_artist
          ));
        }
        else{
          $this->addFlash("error", "Formulaire mal défini");
        }
      }

      return $this->render('RadioRelaxAdminBundle:Album:new.html.twig', array(
        'artist' => $user,
        'form' => $form->createView()
      ));
    }

    $this->addFlash('error', "Artiste inexistant");
    return $this->redirectToRoute('radio_relax_admin_artists_homepage');
  }

  public function showAction($id, Request $request)
  {
    //    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $album = $dm->getRepository("RadioRelaxCoreBundle:Album")->findOneBy(array(
      'id' => $id
    ));

    if(!empty($album)){
      $user = $album->getArtist();
      if(!empty($user)) {
        $form = $this->createForm(AlbumType::class, $album);
        if($request->isMethod('post')){
          $form->handleRequest($request);
          if($form->isValid()){
//          On enregistre et push l'image
            $tmp_name = $_FILES['profile']['tmp_name'];
            $params_clnry = array(
              'api_key' => $this->getParameter('cloudinary_api_key'),
              'cloud_name' => $this->getParameter('cloudinary_cloud_name'),
              'api_secret' => $this->getParameter('cloudinary_api_secret'),
//            'preset' => $this->getParameter('cloudinary_preset'),
//            "resource_type" => "raw",
            );

            $res["public_id"] = 0;
            if(!empty($tmp_name)){
              try{
                $filename = time();
                \Cloudinary::config($params_clnry);
                $res = \Cloudinary\Uploader::upload($tmp_name, array(
                  "resource_type" => "auto",
                  "public_id" => "TEST_RR/".$user->getId()."/images/albums/".$filename
                ));
                unlink($tmp_name);
                $album->setProfile($res['public_id']);
              }
              catch(\Exception $exception){
                $this->addFlash("error", "CDN non Disponible!");
                return $this->redirectToRoute("radio_relax_admin_artists_show", array(
                  "id" => $user->getId()
                ));
              }
            }

            $this->addFlash("notice", "Mise à jour des informations de l'album terminée!");
            $dm->persist($user);
            $dm->persist($album);
            $dm->flush();
            return $this->redirectToRoute("radio_relax_admin_artists_show", array(
              "id" => $user->getId()
            ));
          }
          else{
            $this->addFlash("error", "Formulaire mal défini");
          }
        }
//        On update l'ordre des musiques de l'album
        $ordered_list = [];
        $order_key = 20;
        foreach ($album->getMusics() as $music){
          if(empty($music->getRank())){
            $music->setRank($order_key);
            $order_key++;
          }
          if(empty($ordered_list[$music->getRank()])){
            $ordered_list[$music->getRank()] = $music;
          }
          else{
            $ordered_list[] = $music;
          }
        }
        ksort($ordered_list);
        return $this->render('RadioRelaxAdminBundle:Album:show.html.twig', array(
          'artist' => $user,
          'album' => $album,
          'musics' => $ordered_list,
          'form' => $form->createView()
        ));
      }
      else{
        $this->addFlash('error', "Artiste inexistant");
        return $this->redirectToRoute('radio_relax_admin_artists_homepage');
      }
    }
    else{
      $this->addFlash('error', "Album inexistant");
    }

    return $this->redirectToRoute('radio_relax_admin_artists_homepage');
  }

  public function updateMusicIndexAction($id, Request $request)
  {
    //    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $album = $dm->getRepository("RadioRelaxCoreBundle:Album")->findOneBy(array(
      'id' => $id
    ));

    if(!empty($album)){
      $user = $album->getArtist();
      if(!empty($user)) {
        if($request->isMethod('post')){
          $indexes = $request->get("index");
          $ids = $request->get("ids");

          if(!empty($indexes)){
            foreach ($indexes as $key => $index){
              $music = $dm->getRepository("RadioRelaxCoreBundle:Music")->findOneBy(array(
                "id" => $ids[$key]
              ));
              $music->setRank($index);
              $dm->persist($music);
            }
            $dm->flush();

            $this->addFlash("notice", "Album musics indexes updated");
            return $this->redirectToRoute('radio_relax_admin_albums_show', array(
              'id' => $id
            ));
          }
          else{
            $this->addFlash("error", "Formulaire mal défini");
          }
        }
      }
      else{
        $this->addFlash('error', "Artiste inexistant");
        return $this->redirectToRoute('radio_relax_admin_artists_homepage');
      }
    }
    else{
      $this->addFlash('error', "Album inexistant");
    }

    return $this->redirectToRoute('radio_relax_admin_artists_homepage');
  }

  public function removeAction($id)
  {
    //    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $album = $dm->getRepository("RadioRelaxCoreBundle:Album")->findOneBy(array(
      'id' => $id
    ));

    if(!empty($album)){
      $user = $album->getArtist();
      if(!empty($user)) {
        $user->getArtist()->getAlbums()->removeElement($album);
        $dm->remove($album);

        $this->addFlash("notice", "Album supprimé !");
        $dm->persist($user);
        $dm->persist($album);
        $dm->flush();
        return $this->redirectToRoute("radio_relax_admin_artists_show", array(
          "id" => $user->getId()
        ));
      }
      else{
        $this->addFlash('error', "Artiste inexistant");
      }
    }

    $this->addFlash('error', "Album inexistant");

    return $this->redirectToRoute('radio_relax_admin_artists_homepage');
  }
}
