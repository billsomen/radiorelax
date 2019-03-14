<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use XS\UserBundle\Form\UserEditPasswordType;
use XS\UserBundle\Form\UserEditType;
use XS\UserBundle\Form\UserType;

class AdminController extends Controller
{
  public function indexAction(Request $request){
//        Profil de l'utilisateur actuel
    $user = $this->getUser();
    $user2 = $user;
    $currentPassword = $user->getPassword();
    $form = $this->createForm(UserEditType::class, $user);
    $formPassword = $this->createForm(UserEditPasswordType::class, $user2);
    $session = $request->getSession();
    $token = $session->get('tmp_pass_token');
    $tmp_token = $request->query->get('tmp_pass_token');
    $code = rand(10000, 99999);

//    print_r($currentPassword);
    if($request->isMethod('POST')){
      $form->handleRequest($request);
      if($form->isValid()){
//              On definit l'username comme premier contact telephonique de l'utilisateur
        $telephone = new Telephone();
//        $telephone->setNumber($user->getUsername());
//        $user->addTelephone($telephone);
        $nickname = $user->getNamespace();
        $user->generateNamespace($nickname);
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $dm->persist($user);
        $dm->flush();
      }

      $formPassword->handleRequest($request);
      if($formPassword->isValid()){
//              On definit l'username comme premier contact telephonique de l'utilisateur
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user2);
        $oldPassword = $encoder->encodePassword($user2->getOldPassword(), $user2->getSalt());
//        print_r($oldPassword);
//        print_r($currentPassword);
        if($token == $tmp_token and !empty($user->getTmpPass())){
          $currentPassword = $user->getTmpPass();
        }
//        print_r($currentPassword);
//                On verifie si les mot de passe sont identiques.
        if(hash_equals($currentPassword, $oldPassword)){
          $password = $encoder->encodePassword($user2->getPassword(), $user2->getSalt());
          $user2->setPassword($password);
//                Tout est bon, on peut continuer
          $dm = $this->get('doctrine.odm.mongodb.document_manager');
          $dm->persist($user2);
          $dm->flush();
          $session->set('tmp_pass_token', null);
        }
        else{
          $this->addFlash('error', 'Le mot de passe actuel ne correspond pas.');
        }
        $this->addFlash('notice', 'Modifications enregistrées !');
      }
      return $this->redirectToRoute('admin_profile');
    }
    return $this->render('@Main/Admin/Artist/my_profile.html.twig', array(
      'user' => $user,
      'form' => $form->createView(),
      'formPassword' => $formPassword->createView()
    ));
  }

  public function index2Action(Request $request)
  {
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $user = $this->getUser();
    $user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      'id' => $user->getId()
    ));
    if(!empty($user)){
      $form = $this->createForm(UserType::class, $user);
      $formPassword = $this->createForm(UserEditPasswordType::class, $user);
      if($request->isMethod('post')){
        $form->handleRequest($request);
        if($form->isValid()){
          $this->addFlash("notice", "Mise à jour du profil terminée!");
          $dm->persist($user);
          $dm->flush();
          return $this->redirectToRoute("radio_relax_admin_profile");
        }
        elseif($formPassword->isValid()){
          $this->addFlash("notice", "Mise à jour du mot de passe!");
          $dm->persist($user);
          $dm->flush();
          return $this->redirectToRoute("radio_relax_admin_profile");
        }
        else{
          $this->addFlash("error", "Formulaire mal défini");
        }
      }

      return $this->render('RadioRelaxAdminBundle:Artist:my_profile.html.twig', array(
        'user' => $user,
        'form' => $form->createView(),
        'form_type' => $formPassword->createView()
      ));
    }

    $this->addFlash('error', $this->get("translator")->trans('flashbags.artist.not_found'));
    return $this->redirectToRoute('radio_relax_admin_artists_homepage');
  }



  public function showAction($id, Request $request)
  {
    //    Show and Edit Artists
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      'id' => $id
    ));
    if(!empty($user)){
      $artist = $user->getArtist();
      if(empty($user->getArtist())){
        //
        $artist = new Artist();
        $album = $artist->getAlbums()->get(0);
        $album->setArtist($user);
        $dm->persist($album);
        $user->setArtist($artist);

        $dm->flush();
      }
//      $artist = !empty($user->getArtist())?$user->getArtist():new Artist();
      $form = $this->createForm(ArtistType::class, $artist);

      if($request->isMethod('post')){
        $form->handleRequest($request);
        if($form->isValid()){
//          Si le namespaçe n'existe pas, on le génère
          $user->getArtist()->generateNamespace();
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
          return $this->redirectToRoute("radio_relax_admin_artists_show", array(
            "id" => $id
          ));
//          }
        }
        else{
          $this->addFlash("error", "Formulaire mal défini");
        }
      }

      return $this->render('RadioRelaxAdminBundle:Artist:show.html.twig', array(
        'user' => $user,
        'form' => $form->createView()
      ));
    }

    $this->addFlash('error', $this->get("translator")->trans('flashbags.artist.not_found'));
    return $this->redirectToRoute('radio_relax_admin_artists_homepage');
  }
}
