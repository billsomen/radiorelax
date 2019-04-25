<?php

namespace MainBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MainBundle\Document\Node;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\UserBundle\Document\Telephone;
use XS\UserBundle\Document\User;
use XS\UserBundle\Form\UserEditPasswordType;
use XS\UserBundle\Form\UserEditType;
use XS\UserBundle\Form\UserType;

class AdminController extends Controller
{
//  Template d'un noeud dans le réseau MLM
  private $mlm_node_template;

  public function __construct()
  {
    $this->mlm_node_template = array(
      "name" => "RelaxNode",
      "gender" => "M",
      "key" => 0,
    );
  }

  public function favoritesAction(){
    return $this->render('MainBundle:Admin/Me:favorites.show.html.twig', array(
      'user' => $this->getUser()
    ));
  }

  public function mlmAction(){
//    We build the node JSON :)
//    On lançe le noeud de base
/*
    $node = new Node(0);
    $user = new User();
    $user->setRoles(array());
    $user->getRoles()[] = "ROLE_SYSTEM";
    $user->getProfiles()->add("artist");
    $user->getProfiles()->getArtist()->generateNamespace("Radio Relax");
    $user->getProfiles()->getArtist()->setAlbums(new ArrayCollection());
    $user->setNode($node);
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $dm->persist($user);
    $dm->persist($node);
    $dm->flush();

    return new Response( "done");*/




    $tree = array();
    $user = $this->getUser();

//    Noeud de base :)
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $rr_user = $dm->getRepository("XSUserBundle:User")->findOneBy(array(
      "profiles.artist.namespace" => "radiorelax"
    ));

        $node_rr = $rr_user->getNode();

    $template = $this->mlm_node_template;
    $template["name"] = $this->getParameter("app_name");
    $template["level"] = $node_rr->getLevel();
    $template["key"] = $node_rr->getId();
    $template["children"] = count($node_rr->getChildren());

    $tree[] = $template;

    $node = $user->getNode();

//    print_r($node->getParent()->getLevel());

//    On construit l'arbre depuis le noeud identifié

//    Load from me to UP
    $this->loadTreeUpFrom($node, $tree);

//    print_r($tree);

    //    TODO Load from me to Down
    $this->loadTreeDownFrom($node_rr, $tree);
//    print_r($tree);

//    return new Response("555");

    return $this->render('MainBundle:Admin/Me:mlm.show.html.twig', array(
      'user' => $this->getUser(),
      "tree" => json_encode($tree)
    ));
  }


  public function loadTreeUpFrom(Node $node, &$tree){
//    Return an element to add at
    $template = $this->mlm_node_template;
    if($node->getLevel() > 0){
      if($node->getLevel() == 1){
        $template["key"] = $node->getId();
//            Mon parent est RADIO_RELAX
        $template["parent"] = 0;
        $template["level"] = 1;
        $template["children"] = count($node->getChildren());
        $template["name"] = $node->getUser()->getNickname();
        $tree[] = $template;
      }
      else{
        $parent = $node->getParent();
        if(!empty($parent)){
          $children = $parent->getChildren();
          foreach ($children as $child){
            $template["key"] = $child->getId();
            $template["parent"] = $parent->getId();
            $template["name"] = $child->getUser()->getNickname();
            $template["children"] = count($child->getChildren());
            $template["level"] = $child->getLevel();
            $tree[] = $template;
          }
//      on remonte au parent du parent identiquement :)
          $this->loadTreeUpFrom($parent, $tree);
        }
      }
    }
  }

  public function loadTreeDownFrom(Node $node, &$tree){
//    Charge uniquement l'arbre à partir de l'éléméne (en l'ignorant) et en descendant
    $template = $this->mlm_node_template;
    if($node->getLevel() < Node::MAX_LEVEL){
      $children = $node->getChildren();
      if(!empty($children)){
        foreach ($children as $child){
//          On ajoute chaque enfant

          $template["key"] = $child->getId();
          $template["parent"] = $node->getId();
          $template["name"] = $child->getUser()->getNickname();
          $template["children"] = count($child->getChildren());
          $template["level"] = $child->getLevel();
          $tree[] = $template;

//          On ajoute les enfant des enfant identiquement
          $this->loadTreeDownFrom($child, $tree);
        }
      }
    }
  }

  public function playlistAction(){
    //    Show and Edit Artists
    $user = $this->getUser();
    if(!empty($user)){
      return $this->render('MainBundle:Admin/Me:show.html.twig', array(
        'user' => $user
      ));
    }

    $this->addFlash('error', $this->get("translator")->trans('flashbags.artist.not_found'));
    return $this->redirectToRoute('admin_profile');
  }


  public function playlistAlbumShowAction($id){
    //    Proxy to redirect user to  album view
    $dm = $this->get('doctrine.odm.mongodb.document_manager');

    $album = $dm->getRepository("MainBundle:Album")->findOneBy(array(
      'id' => $id
    ));

    return $this->redirectToRoute("core_artists_albums_show", array(
      "id" => $id,
      "namespace" => $album->getArtist()->getProfiles()->getArtist()->getNamespace()
    ));
  }

  public function favoriteAlbumAddAction($id){
    //    Ajouter un album à la liste des favoris
    $dm = $this->get('doctrine.odm.mongodb.document_manager');

    $album = $dm->getRepository("MainBundle:Album")->findOneBy(array(
      'id' => $id
    ));

    $user = $this->getUser();

//    $user->getProfiles()->getListener()->setFavoritesAlbums(new ArrayCollection());
    $user->getProfiles()->getListener()->getFavoritesAlbums()->add($album);
    $dm->persist($user);
    $dm->flush();

//    return new Response(count($user->getProfiles()->getListener()->getFavoritesAlbums()));

    $this->addFlash("notice", "Album ajouté avec susccès aux favoris");


    return $this->redirectToRoute("core_artists_albums_show", array(
      "id" => $id,
      "namespace" => $album->getArtist()->getProfiles()->getArtist()->getNamespace()
    ));
  }

  public function favoriteAlbumRemoveAction($id, $from_admin){
    //    Retirer un album à la liste des favoris
    $dm = $this->get('doctrine.odm.mongodb.document_manager');

    $album = $dm->getRepository("MainBundle:Album")->findOneBy(array(
      'id' => $id
    ));

    $user = $this->getUser();

    $user->getProfiles()->getListener()->getFavoritesAlbums()->removeElement($album);
    $dm->persist($album);
    $dm->flush();

    $this->addFlash("notice", "Album retiré avec susccès des favoris");


    if($from_admin){
      return $this->redirectToRoute("admin_profile_favorites");
    }

    return $this->redirectToRoute("core_artists_albums_show", array(
      "id" => $id,
      "namespace" => $album->getArtist()->getProfiles()->getArtist()->getNamespace()
    ));
  }

//  Music to & from Favorites

  public function favoriteMusicAddAction($id){
    //    Ajouter un music à la liste des favoris
    $dm = $this->get('doctrine.odm.mongodb.document_manager');

    $music = $dm->getRepository("MainBundle:Music")->findOneBy(array(
      'id' => $id
    ));

    $user = $this->getUser();

    $user->getProfiles()->getListener()->getFavoritesMusics()->add($music);
    $dm->persist($user);
    $dm->flush();

//    return new Response(count($user->getProfiles()->getListener()->getFavoritesMusics()));

    $this->addFlash("notice", "Music ajouté avec susccès aux favoris");


    return $this->redirectToRoute("core_artists_albums_show", array(
      "id" => $music->getAlbum()->getId(),
      "namespace" => $music->getArtist()->getProfiles()->getArtist()->getNamespace()
    ));
  }

  public function favoriteMusicRemoveAction($id){
    //    Retirer un music à la liste des favoris
    $dm = $this->get('doctrine.odm.mongodb.document_manager');

    $music = $dm->getRepository("MainBundle:Music")->findOneBy(array(
      'id' => $id
    ));

    $user = $this->getUser();

    $user->getProfiles()->getListener()->getFavoritesMusics()->removeElement($music);
    $dm->persist($music);
    $dm->flush();

    $this->addFlash("notice", "Music retiré avec susccès des favoris");


    return $this->redirectToRoute("core_artists_albums_show", array(
      "id" => $music->getAlbum()->getId(),
      "namespace" => $music->getArtist()->getProfiles()->getArtist()->getNamespace()
    ));
  }

  public function dashboardAction(){
    //Tableau de bord de l'administrateur
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
//    1. Stats
    /*
     * 1.1. Listeners
     * 1.2. Albums
     * 1.3. Musics
     * 1.4. Artists
     * 1.5. Transactions & Sales
     */

    $transactions = $dm->getRepository("XSAfrobankBundle:Transaction")->findAll();
    /*$listeners = $dm->getRepository("XSUserBundle:User")->findBy(array(
      'roles' => "ROLE_USER"
    ));*/
    $artists = $dm->getRepository("XSUserBundle:User")->findBy(array(
      'roles' => "ROLE_ARTIST"
    ));

//    Listeners

    $query = $dm->createQueryBuilder("XSUserBundle:User");

    $query
      ->field('roles')
      ->in(["ROLE_USER"])
      ->notIn(["ROLE_ARTIST"])
    ;
    $listeners = $query->getQuery()->execute();

    $albums = $dm->getRepository("MainBundle:Album")->findAll();
    $musics = $dm->getRepository("MainBundle:Music")->findAll();

    $charts = array();
    $labels = [];
    foreach ($albums as $item){
      /*if(isset($labels[date_format($music->getDateAdd(), "Y-M")])){
        $labels[date_format($music->getDateAdd(), "Y-M")] = 0;
      }*/
      $labels[date_format($item->getDateAdd(), "Y-m")][] = 1;
//      $charts["musics"]["labels"][""] = 1;
    }

    foreach ($labels as $key => $label){
//      $charts["albums"]["labels"][] = explode('-', $key)[2];
      $charts["albums"]["labels"][] = $key;
      //        TODO: remove something below : *1000
      $charts["albums"]["data"][] = count($label)*100;
    }

    $charts["albums"]["datasets"][0] = array(
      "label" => "Ajouts",
      "data" => $charts["albums"]["data"]
    );

    unset($charts["albums"]["data"]);

//Gestion des ventes
    $labels = array();
    foreach ($transactions as $item){
      $labels[date_format($item->getDateAdd(), "Y-m")][] = $item->getAmount()->getValue();
    }

    foreach ($labels as $key => $label){
      $charts["sales"]["labels"][] = $key;
      $total = 0;
      foreach ($label as $val){
//        TODO: remove something below
        $total += 500*rand(1, 9)+$val;
      }
      $charts["sales"]["data"][] = $total;
    }

    $charts["sales"]["datasets"][0] = array(
      "label" => "Ajouts",
      "data" => $charts["sales"]["data"]
    );

    unset($charts["sales"]["data"]);

    $charts["albums"] = json_encode($charts["albums"]);
    $charts["sales"] = json_encode($charts["sales"]);



    $total_income = 0;
    foreach ($transactions as $transaction){
      $total_income += $transaction->getAmount()->getValue();
    }

    return $this->render('@Main/Admin/dashboard.html.twig', array(
      "listeners" => $listeners,
      "artists" => $artists,
      "albums" => $albums,
      "charts" => $charts,
      "musics" => $musics,
      "total_income" => $total_income,
      "transactions" => $transactions
    ));

  }


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

    $dm = $this->get('doctrine.odm.mongodb.document_manager');

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
      return $this->redirectToRoute('admin_profile', array(
        "_locale" => $user->getLocale()->getLanguage()
      ));
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
