<?php

namespace MainBundle\Controller;

use MainBundle\Document\Artist;
use MainBundle\Form\ArtistType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use XS\UserBundle\Document\User;

class ArtistController extends Controller
{
  public function indexAction()
  {
//    All the artists to Manage
    $dm = $this->get("doctrine_mongodb");
    $tmp_artists = $dm->getRepository("RadioRelaxComingSoonBundle:TempUser")->findBy(array(
      "type" => "artist"
    ));

    $artists = $dm->getRepository("XSUserBundle:User")->findAll();

    return $this->render('RadioRelaxAdminBundle:Artist:index.html.twig', array(
      'tmp_artists' => $tmp_artists,
      'artists' => $artists,
    ));
  }

  public function validateTempAction($id)
  {
//    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $tmp_user = $dm->getRepository("RadioRelaxComingSoonBundle:TempUser")->findOneBy(array(
      'id' => $id
    ));
    if(!empty($tmp_user)){
//      On relie et ajoute l'utilisateur a la requete
      $user =  new User();
      $user->setAccessArtist(true);
//      Profil artiste de l'utilisateur
      $artist = new Artist();
      $domain_name = $artist->fillFromRequest($tmp_user);
      $user->setUsername($artist->getEmail());
      $user->setArtist($artist);
      $user->setNickname($artist->getName());
//      On envoit le mail de çonfirmation et de vérifiçation de l'adresse Email de l'artiste :)
      $user_checker = new \AppBundle\Service\User();
      $status = 'notice';
      if(!$user_checker->checkUserAnyway($dm, $user)){
        $user->setConfirmed(false);
        $code = rand(10000, 99999);
        $user->setConfirmationCode($code);

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($code, $user->getSalt());
        $user->setPassword($password);
//                    todo: Fin de la generation du code...
        try{
          $insert_user = new \AppBundle\Service\User();
          $insert_user->_insert_user($dm, $user, null, 1);
          $status_message = 'Artiste validé et profil créé. Un mail lui a été envoyé!';
          $user->setEmail($user->getUsername());

//              On émet le Mail
//          TODO: Edit the mail of the user
          $receiver = $user->getUsername();
          $receiver = "ngongangsomen@gmail.com";
          $sender = $this->getParameter("mailer_user");
          $message = \Swift_Message::newInstance()
            ->setSubject('(TEST) Inscription Validée et Profil créé!')
//              ->setFrom([$sender => $this->getParameter("app_name")])
            ->setFrom([$sender => $this->getParameter("app_name")])
            ->setTo(array($receiver, $this->getParameter("mailer_user")))
            ->setBody(
              $this->renderView(
                '@XSMboadjoss/Message/RadioRelax/artist-validated.html.twig',array(
                  'user' => $user,
                  'namespace' => $domain_name,
                )
              ),
              'text/html'
            )
          ;
          $r = $this->get('mailer')->send($message);

          //      On enregistre le çompte
          $dm->persist($tmp_user);
          $dm->persist($user);
          $dm->flush();

        }catch(\Exception $exception){
          $status = 'error';
          $status_message = "Désolé, aucune adresse Email n'a été configurée pour cette Application";
        }
      }
      else{
        $status_message = 'Adresse Email déjà existante dans la base de données';
      }
//      Ajout des messages
      $this->addFlash($status, $status_message);
    }

    return $this->redirectToRoute('admin_artists_homepage');
  }

  public function showAction($id, Request $request)
  {
    //    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      'id' => $id
    ));
    if(!empty($user)){
      $artist = $user->getProfiles()->getArtist();
      if(empty($artist)){
        //
        $user->getProfiles()->add("artist");
        $artist = $user->getProfiles()->getArtist();
        $album = $artist->getAlbums()->get(0);
        $album->setArtist($user);
        $dm->persist($album);
        $user->getProfiles()->setArtist($artist);

        $dm->flush();
      }
//      $artist = !empty($user->getArtist())?$user->getArtist():new Artist();
      $form = $this->createForm(ArtistType::class, $artist);

      if($request->isMethod('post')){
        $form->handleRequest($request);
        if($form->isValid()){
//          Si le namespaçe n'existe pas, on le génère
          $user->getProfiles()->getArtist()->generateNamespace();
//          On enregistre et push l'image
          $tmp_name = $_FILES['profile']['tmp_name'];
          $params_clnry = array(
            'api_key' => $this->getParameter('cloudinary_api_key'),
            'cloud_name' => $this->getParameter('cloudinary_cloud_name'),
            'api_secret' => $this->getParameter('cloudinary_api_secret'),
//            'preset' => $this->getParameter('cloudinary_preset'),
//            "resource_type" => "raw",
          );

          $res["url"] = 0;
          if(!empty($tmp_name)){
            try{
              $filename = time();
              \Cloudinary::config($params_clnry);
              $res = \Cloudinary\Uploader::upload($tmp_name, array(
                "resource_type" => "auto",
                "public_id" => "TEST_RR/".$user->getId()."/images/".$filename
              ));
              unlink($tmp_name);
              $artist->setProfilePic($res['public_id']);
            }
            catch(\Exception $exception){
              $this->addFlash("error", "CDN non Disponible!");
            }
          }
//          else{
            $this->addFlash("notice", "Mise à jour du profil de l'artiste terminée!");
            $dm->persist($user);
            $dm->flush();
            return $this->redirectToRoute("admin_artists_show", array(
              "id" => $id
            ));
//          }
        }
        else{
          $this->addFlash("error", "Formulaire mal défini");
        }
      }

      return $this->render('MainBundle:Admin/Artist:show.html.twig', array(
        'user' => $user,
        'form' => $form->createView()
      ));
    }

    $this->addFlash('error', $this->get("translator")->trans('flashbags.artist.not_found'));
    return $this->redirectToRoute('admin_artists_homepage');
  }

  public function newAction()
  {
//    Ajout d'un artiste
    return $this->render('RadioRelaxAdminBundle:Artist:new.html.twig', array('artists' => 0));
  }
}
