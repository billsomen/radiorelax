<?php

namespace MainBundle\Controller;

use RadioRelax\AdminBundle\Form\MusicType;
use RadioRelax\CoreBundle\Document\Music;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use wapmorgan\Mp3Info\Mp3Info;

class MusicController extends Controller
{
  public function indexAction($name)
  {
    return $this->render('', array('name' => $name));
  }

  public function newAction($id_album, Request $request)
  {
    //    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $album = $dm->getRepository("RadioRelaxCoreBundle:Album")->findOneBy(array(
      'id' => $id_album
    ));
    if(!empty($album)){
//      $album = $user->getAlbum();
      $user = $album->getArtist();
      $music = new Music();
      $form = $this->createForm(MusicType::class, $music);

      if($request->isMethod('post')){
        $form->handleRequest($request);
        if($form->isValid()){
//          On enregistre et push l'image
          $tmp_name = !empty($_FILES['profile'])?$_FILES['profile']['tmp_name']:null;
//          var_dump($_FILES);
//          m;
//          print_r($iii);

          $res["public_id"] = 0;
          if(!empty($tmp_name)){
            try{
              $params_clnry = array(
                'api_key' => $this->getParameter('cloudinary_api_key'),
                'cloud_name' => $this->getParameter('cloudinary_cloud_name'),
                'api_secret' => $this->getParameter('cloudinary_api_secret'),
              );

              $filename = time();
              \Cloudinary::config($params_clnry);
              $res = \Cloudinary\Uploader::upload($tmp_name, array(
                "resource_type" => "auto",
                "public_id" => "TEST_RR/".$user->getId()."/musics/".$filename
              ));
              unlink($tmp_name);
              $music->setSrc($res['public_id']);
              $music->setDuration(floor($res['duration']));
              $music->setBitRate($res['bit_rate']);
            }
            catch(\Exception $exception){
              $this->addFlash("error", "CDN non Disponible!");
              return $this->redirectToRoute("radio_relax_admin_albums_show", array(
                "id" => $id_album
              ));
            }
          }

          $music->setArtist($user);
          $music->setAlbum($album);
          $album->getMusics()->add($music);

          $this->addFlash("notice", "Mise à jour des informations de la musique terminée!");
          $dm->persist($music);
          $dm->persist($album);
          $dm->flush();
          return $this->redirectToRoute("radio_relax_admin_albums_show", array(
            "id" => $id_album
          ));
        }
        else{
          $this->addFlash("error", "Formulaire mal défini");
        }
      }

      return $this->render('RadioRelaxAdminBundle:Music:new.html.twig', array(
        'artist' => $user,
        'album' => $album,
        'form' => $form->createView()
      ));
    }

    $this->addFlash('error', "Album inexistant");
    return $this->redirectToRoute('radio_relax_admin_profile');
  }

  public function newMultipleAction($id_album, Request $request)
  {
    //    Add multiple
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $album = $dm->getRepository("RadioRelaxCoreBundle:Album")->findOneBy(array(
      'id' => $id_album
    ));
    if(!empty($album)){
//      $album = $user->getAlbum();
      $user = $album->getArtist();

      if($request->isMethod('post')){
//          On enregistre et push l'image
        $tmp_name = $_FILES['profiles'];

        $res["public_id"] = 0;
        if(!empty($tmp_name)){
          try{
            $params_clnry = array(
              'api_key' => $this->getParameter('cloudinary_api_key'),
              'cloud_name' => $this->getParameter('cloudinary_cloud_name'),
              'api_secret' => $this->getParameter('cloudinary_api_secret'),
            );

            $filename = time();
            \Cloudinary::config($params_clnry);

//            TODO: We loop here to upload multiple files
            for($i=0; $i<count($tmp_name['name']); $i++){
              $music = new Music();

              $res = \Cloudinary\Uploader::upload($tmp_name['tmp_name'][$i], array(
                "resource_type" => "auto",
                "public_id" => "TEST_RR/".$user->getId()."/musics/".$filename
              ));
              unlink($tmp_name['tmp_name'][$i]);

              $music->setName(pathinfo($tmp_name["name"][$i])['filename']);

              $music->setSrc($res['public_id']);
              $music->setDuration(floor($res['duration']));
              $music->setBitRate($res['bit_rate']);
              $music->setAlbum($album);
              $music->setArtist($user);

              $album->getMusics()->add($music);
              $dm->persist($music);
            }
            $this->addFlash("notice", "Mise à jour des informations de la musique terminée!");
            $dm->persist($album);
            $dm->flush();
          }
          catch(\Exception $exception){
            $this->addFlash("error", "CDN non Disponible!");
            return $this->redirectToRoute("radio_relax_admin_albums_show", array(
              "id" => $id_album
            ));
          }
        }
        return $this->redirectToRoute("radio_relax_admin_albums_show", array(
          "id" => $id_album
        ));

      }

      return $this->render('RadioRelaxAdminBundle:Music:new-multiple.html.twig', array(
        'artist' => $user,
        'album' => $album
      ));
    }

    $this->addFlash('error', "Album inexistant");
    return $this->redirectToRoute('radio_relax_admin_profile');
  }

  public function showAction($id, Request $request)
  {
    //    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $music = $dm->getRepository("RadioRelaxCoreBundle:Music")->findOneBy(array(
      'id' => $id
    ));
    if(!empty($music)){
//      $album = $user->getAlbum();
      $user = $music->getArtist();
      $form = $this->createForm(MusicType::class, $music);
      if($request->isMethod('post')){
        $form->handleRequest($request);
        if($form->isValid()){
//          On enregistre et push l'image
          $tmp_name = !empty($_FILES['profile'])?$_FILES['profile']['tmp_name']:null;
//          var_dump($_FILES);
//          m;
//          print_r($iii);

          $res["public_id"] = 0;
          if(!empty($tmp_name)){
            try{
              $params_clnry = array(
                'api_key' => $this->getParameter('cloudinary_api_key'),
                'cloud_name' => $this->getParameter('cloudinary_cloud_name'),
                'api_secret' => $this->getParameter('cloudinary_api_secret'),
              );

              $filename = time();
              \Cloudinary::config($params_clnry);
              $res = \Cloudinary\Uploader::upload($tmp_name, array(
                "resource_type" => "auto",
                "public_id" => "TEST_RR/".$user->getId()."/musics/".$filename
              ));
              unlink($tmp_name);
              $music->setSrc($res['public_id']);
              $music->setDuration(floor($res['duration']));
              $music->setBitRate($res['bit_rate']);
              print_r($music->getDuration());
            }
            catch(\Exception $exception){
              $this->addFlash("error", "CDN non Disponible!");
              print_r("bad");
              /*return $this->redirectToRoute("radio_relax_admin_albums_show", array(
                "id" => $id_album
              ));*/
            }
          }
          return new Response("45");

          $this->addFlash("notice", "Mise à jour des informations de la musique terminée!");
          $dm->persist($music);
          $dm->flush();
          return $this->redirectToRoute("radio_relax_admin_albums_show", array(
            "id" => $music->getAlbum()->getId()
          ));
        }
        else{
          $this->addFlash("error", "Formulaire mal défini");
        }
      }

      return $this->render('RadioRelaxAdminBundle:Music:show.html.twig', array(
        'artist' => $user,
        'music' => $music,
        'album' => $music->getAlbum(),
        'form' => $form->createView()
      ));
    }

    $this->addFlash('error', "Musique inexistante");
    return $this->redirectToRoute('radio_relax_admin_profile');
  }
}
