<?php

namespace XS\UserBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\AfrobankBundle\Document\Amount;
use XS\AfrobankBundle\Document\Transaction;
use XS\AfrobankBundle\Form\TransactionPaymentType;
use XS\CoreBundle\Document\ManagerSystem;
use XS\UserBundle\Document\User;
use XS\UserBundle\Form\UserEditType;

class UserController extends Controller
{
  public function _allAction(Request $request/*, $group, $filter, $type*/){
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $users = $dm->getRepository('XSUserBundle:User')->findAll();
    
    return $this->render('@XSUser/User/_all.html.twig', array(
      'users' => $users
    ));
  }
  
  public function _jsonCheckNamespaceAction(Request $request, $namespace=0){
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $user = $dm->getRepository('XSUserBundle:User')->findOneBy(
      array('namespace' => $namespace)
    );
    
    $me = $request->getUser();
    
    $flag = false;
    $is_me = false;
    $formatted = null;
    if(isset($user)){
      $me = $this->getUser();
      if(isset($me)){
        if($user->getId() == $me->getId()){
          $is_me = true;
        }
      }
      
      print_r($user->getId());
//      print_r($me->getId());
      
      $flag = true;
    }
    else{
      $tmp = new User();
      $formatted = $tmp->generateNamespace($namespace);
      unset($tmp);
    }
    
    if($request->isXmlHttpRequest()){
      
      $response = new JsonResponse();
      
      $response->setData(array(
        'found' => $flag,
        'formatted' => $formatted,
        'is_me' => $is_me
      ));
      return $response;
    }
    
    var_dump($flag);
    
    
    
    return $this->render('@XSUser/User/_all.html.twig', array(
      'users' => $user
    ));
  }
  
  public function _jsonGenerateNamespaceAction(Request $request, $namespace){
    $tmp_u = new ManagerSystem();
    $gen_namespace = $tmp_u->generateNamespace($namespace, true);
    
    
    $response = new JsonResponse();
    
    $response->setData(array(
      'formatted' => $gen_namespace,
    ));
    return $response;
  }
  
  /**
   * @Security("has_role('ROLE_USER')")
   */
  public function showAction()
  {
//            todo: Pour l'instant, on ne voit pas le profil des autres membres...
//        On le modifie juste, comme ci-bas...
//    return $this->redirectToRoute('xs_user_edit');
        return $this->render('@XSUser/User/show.html.twig', array(
          "user" => $this->getUser()
        ));
  }
  
  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function removeAction($id)
  {
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $user = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
      'id' => $id
    ));
    $author = $user->getAuthor();
    $author->getEdu()->getAdmin()->getUsers()->removeElement($user);
    
    $dm->remove($user);
    $dm->persist($author);
    $dm->flush();
    
    return $this->redirectToRoute('main_homepage');
  }
  
  public function validateAction(Request $request){
    $email = '';
    $code = '';
    $confirmed = false;
    if($request->isMethod('GET')) {
      if(isset($_GET['email']) AND isset($_GET['code'])) {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $email = $_GET['email'];
        $code = $_GET['code'];
//        Permet de valider un code de validation venant d'une adresse Email...
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $user = $dm->getRepository('XSUserBundle:User')->findOneByUsername($email);
        if ($user != null) {
          /*if($user->confirmed){
//                        On s'assure qu'on n'a pas deja confirme le compte...
          }
          else {*/
//            On a trouve l'utilisateur, on peut alors verifier si le code de validation est bon...
          if ($user->getConfirmationCode() == $code) {
//                Ce n'est que dans ce cas, qu'on valide le compte et demande a l'utilisateur de se connecter pour commencer a utiliser l'application :)
            $user->confirm();
            $dm->persist($user);
            $dm->flush();
            $confirmed = true;
          }
        }
//                }
      }
    }

//        Dans le cas ou, ce n'est pas bon, on affiche l'erreur... :)
    return $this->render('@XSUser/User/validation.html.twig', array(
      'confirmed' => $confirmed,
      'email' => $email,
      'code' => $code
    ));
  }
  
  public function editProfileAction($namespace, Request $request)
  {
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $data = $dm->getRepository('XSUserBundle:User')->findOneByNamespace($namespace);
    if($data != null){
      $user = $this->getUser();
      if($user == $data) {
        $form = $this->createFormBuilder()
          ->add('uri', 'hidden')
          ->getForm();
        $uri = 0;
        if($request->isMethod('POST')){
          $form->handleRequest($request);
          if($form->isValid()){
            $uri = $form->getData()['uri'];
//                        On efface l'ancienne photo
            $removed = $this->container->get('app.image_controller')->removeProfile($user->getProfile());
            $id = $this->container->get('app.image_controller')->persistBase64($uri);
            if($id){
//                            Dans ce cas, tout est bon, on peut modifier le profil...
              $image = $dm->getRepository('XSCoreBundle:Image')->findOneById($id);
              $user->setProfile($image);
              $dm->persist($user);
              $dm->flush();
//                            C'est ok, on repart a la visuatisation du profil.
              return $this->redirectToRoute('xs_user_show', array(
                'namespace' => $namespace
              ));
              
            }
          }
        }
        return $this->render('@XSUser/User/editProfile.html.twig', array(
            'form' => $form->createView(),
            'src' => $uri
          )
        );
        
      }
    }
    return $this->redirect($this->generateUrl('main_home'));
  }
  
  public function adsAction($namespace)
  {
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $data = $dm->getRepository('XSUserBundle:User')->findOneByNamespace($namespace);
    if(isset($data)){
      //Traficoquer ce qu'on peut ici...
      return $this->render('@XSUser/User/ads.html.twig', array(
        'user_profile' => $data,
      ));
    }
    return $this->redirectToRoute('main_home');
  }
  
  public function removeNewsAction($id, Request $request)
  {
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $news = $dm->getRepository('XSCoreBundle:News')->findOneBy(array(
      'id' => $id
    ));
    $user = $this->getUser();
    $user->getNews()->removeElement($news);
    $dm->remove($news);
    $dm->persist($news);
    $dm->flush();
    $this->addFlash('notice', 'News supprimée avec succès');
    $target = $request->query->get('target');
    if(!empty($target)){
      return $this->redirect($target);
    }
    return $this->redirectToRoute('xs_education_homepage');
  }
  
  public function followUserAction(Request $request, $id){
//        Editer le profil utilisateur... :)
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $user = $this->getUser();
    $follow = $request->query->get('follow');
    $target = $request->query->get('target');
    
    $target_user = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
      'id' => $id
    ));
    
    if(!isset($target_user)){
      $this->addFlash('error', "Cet utilisateut n'existe pas!");
//      return $this->redirectToRoute('xs_mboadjoss_users_all');
    }
    
    $following = false;
    $is_me = false;
    if(isset($user)){
      $following = $user->isFollowing($target_user);
      if($user->getId() == $id){
        $is_me = true;
      }
    }
    
    if(isset($follow) and !$following){
      if($follow == 1){
        $user->addFollowee($target_user);
        $target_user->addFollower($user);
        $dm->persist($user);
        $dm->persist($target_user);
        $dm->flush();
        $this->addFlash('notice', "Vous suivez actuellement : ".$target_user->getNickname().' !');
        try{
          if(!empty($target)){
            return $this->redirect($target);
          }
        }catch(\Exception $exception){
        }
        return $this->redirectToRoute('xs_mboadjoss_users_show', array(
          'id' => $id
        ));
      }
    }
    
    
    return $this->render('@XSUser/User/follow.user.html.twig',
      array(
        'is_me' => $is_me,
        'target' => $target_user,
        'following' => $following,
      )
    );
  }
  /**
   * @Security("has_role('ROLE_USER')")
   */
  public function editAction(Request $request){
//        Editer le profil utilisateur... :)
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
//        On ne gere pas la modif d'autres profils.
//        $data = $dm->getRepository('XSUserBundle:User')->findOneByNamespace($namespace);
    $user = $this->getUser();
    $form = $this->createForm(new UserEditType(), $user);
    $form->handleRequest($request);
    if ($form->isValid()) {
      $dm->persist($user);
      $dm->flush();
    }
    
    return $this->render(
      '@XSUser/User/edit.html.twig',
      array(
        'form' => $form->createView()
      )
    );
  }
  
  public function allAction()
  {
    //Affichage de toutes les boutiques...
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $base_query = $dm->createQueryBuilder('XSUserBundle:User');
    
    //nombre de produit par page = nb_produits
    $nb_produits = 12;
    $users = null;
    $user  = $base_query->getQuery()->execute();
    //Nombre de pages...
    $users['pages'] = ceil(count($user)/$nb_produits);
    $user  = $base_query->limit($nb_produits)->getQuery()->execute();
    
    
    //            todo : On fait plonger les parametres
    $params['town'] = 0;
    $params['country'] = 0;
    $params['query'] = 0;
    $params['sort_tag'] = 0;
    $params['sort_by'] = 0;
    $params['page'] = 1;
    //            Ajout du formulaire
    $form = $this->createFormBuilder()
      ->add('search', 'text')
      ->getForm();

//            Fin params...

//        Groupages
    $qb = $base_query
      ->group(
        array(
          'localisation.town' => null
        ),
        array(
          'total' => 0,
        )
      )
      ->reduce('function(k, vals){
                    vals.total++;
            }')
    ;
    $query = $qb->getQuery();
    $users['town'] = $query->execute()->toArray();
    
    $qb = $base_query
      ->group(
        array(
          'localisation.country' => null
        ),
        array(
          'total' => 0,
        )
      )
      ->reduce('function(k, vals){
                    vals.total++;
            }')
    ;
    $query = $qb->getQuery();
    $users['country']  = $query->execute()->toArray();
// On ne peut regrouper par les services ou les produits, parce que ces infos sont referencees...
    $groups['users'] = $users;
    
    return $this->render('@XSUser/User/all.html.twig', array(
      'users' => $user,
      'groups' => $groups,
      'form' => $form->createView(),
      'params' => $params
    ));
  }
  
  public function searchAction(Request $request, $query, $town, $country, $sort_tag, $sort_by, $page){
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    //nombre de produit par page = nb_produits
    $nb_produits = 12;
    $users = null;
//            todo......Coooooooooool
//            On applique les filtres de recherche
    
    $base_query = $dm->createQueryBuilder('XSUserBundle:User');
    
    if($town != (null OR 0) ){
      $base_query->addAnd($base_query->expr()->field('localisation.town')->equals($town));
    }
    
    if($country != (null OR 0)){
      $base_query->addAnd($base_query->expr()->field('localisation.country')->equals($country));
    }

//            MOTEUR DE RECHERCHE
//            Ajout du formulaire
    $form = $this->createFormBuilder()
      ->add('search', 'text')
      ->getForm();
    
    $form->handleRequest($request);
//            Gestion de la soumission et recherche :)
    if($form->isValid()){
      $search_form = $form->getData()['search'];
      $keywords = explode(' ', $search_form);
//            On effectue la recherche dans le nom de la user :)
      $base_query->addAnd($base_query->expr()->field('nickname')->in($keywords));
    }
//            FIN MOTEUR DE RECHERCHE
    
    if($page != (null OR 0)){
      //Gestion de la page :)
      $page = (int) $page;
      $page = ceil($page);
      if($page > 0){
        $base_query->skip(($page-1)*$nb_produits);
      }
    }
    
    if($sort_tag != (null OR 0)){
      if($sort_by != (null OR 0)){
        //On fait le classement :)
        $base_query->sort($sort_tag, $sort_by);
      }
    }
    
    //PAGINNATION
    $base_query->limit($nb_produits);
    
    // Les produits a envoyer...
    $user = $base_query->getQuery()->execute();
    //Nombre actualise de pages...
    $users['pages'] = ceil(count($user)/$nb_produits);
    
    //On envois nos parametres a la vue... :)
//            todo : On fait plonger les parametres
    $params['town'] = $town;
    $params['country'] = $country;
    $params['query'] = $query;
    $params['sort_tag'] = $sort_tag;
    $params['sort_by'] = $sort_by;
    $params['page'] = $page;
//            Fin params...
//            Tout est ok, on peut appliquer les groupages :)
    
    
    $qb = $base_query
      ->group(
        array(
          'localisation.town' => null
        ),
        array(
          'total' => 0,
        )
      )
      ->reduce('function(k, vals){
                    vals.total++;
            }')
    ;
    $query = $qb->getQuery();
    $users['town'] = $query->execute()->toArray();
    
    $qb = $base_query
      ->group(
        array(
          'localisation.country' => null
        ),
        array(
          'total' => 0,
        )
      )
      ->reduce('function(k, vals){
                    vals.total++;
            }')
    ;
    $query = $qb->getQuery();
    $users['country']  = $query->execute()->toArray();
    
    $groups['users'] = $users;
    //-----------SERVICES :)
//            todo: A venir... :)
    //------------------
    return $this->render('@XSUser/User/all.html.twig', array(
      'users' => $user,
      'groups' => $groups,
      'form' => $form->createView(),
      'params' => $params
    ));
  }
  
  public function subscribeAction(Request $request){
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $session = $request->getSession();
    $courses = $session->get('courses');
    $user = $this->getUser();
//    $user->getEdu()->getStudent()->setCoursesPending(new ArrayCollection());
//    $dm->persist($user);
//    $dm->flush();
    $transaction_ser = new \AppBundle\Service\Transaction();
    $transaction = $transaction_ser->_new($dm);
    $form = $this->createForm(TransactionPaymentType::class, $transaction);
    if($request->isMethod('POST')){
      $form->handleRequest($request);
      $accepted_keys = array(
        'Orange Money', 'MTN Mobile Money', 'Express Union', 'Paypal', 'Express Exchange', 'Bank Transfer'
      );
      if(in_array($transaction->getBank(), $accepted_keys)){
        $receiver = $transaction_ser->_getSystemReceiver($dm);
//        On ajoute la transaction et on en**oit le mail!
//        $transaction->setType('payment');
        $transaction->setType('deposit');
        $transaction->setAuthor($user);
        $transaction->setReason('Recharge Externe de mon Compte Edutool');
//        $transaction->setReason('Abonnement à vie aux cours Edutool');
        $transaction->setReceiver($receiver);
        $transaction->setState('pending');
        $transaction->setSender($user);
        $receiver->getAccount()->getTransactions()->add($transaction);
        $user->getAccount()->getTransactions()->add($transaction);
//        On ajoute les cours en attente 2 paiement
//        $user->getEdu()->getStudent()->setCoursesPending(new ArrayCollection());
//        try{
        $courses_selected = $_POST['courses'];
        foreach($courses_selected as $course){
          $course = $dm->getRepository('XSEducationBundle:Course')->findOneBy(array(
            'id' => $course,
          ));
          if(!$user->getEdu()->getStudent()->getCoursesPending()->contains($course)){
            $user->getEdu()->getStudent()->getCoursesPending()->add($course);
//              On crée aussi les transactions pour les écoles correspon*antes!!!
            $transaction_tmp = $transaction_ser->_new($dm);
            $receiver_tmp = $transaction_ser->_getSystemReceiver($dm, $course->getSchool(), 'SCHOOL');
//        On ajoute la transaction et on en**oit le mail!
//        $transaction->setType('payment');
            $transaction_tmp->setType('payment');
            $transaction_tmp->setAuthor($receiver);
            $transaction_tmp->setReason('Achat du cours : '.$course->getName());
            $transaction_tmp->setReceiver($receiver_tmp);
            $transaction_tmp->setState('pending');
            $transaction_tmp->setCourse($course);
            $transaction_tmp->setSender($user);
            $transaction_tmp->setAmount(new Amount($course->getLifePrice()));
            $receiver_tmp->getAccount()->getTransactions()->add($transaction_tmp);
            $user->getAccount()->getTransactions()->add($transaction_tmp);
            $dm->persist($transaction_tmp);
            $dm->persist($receiver_tmp);
            $dm->persist($receiver);
            $dm->flush();
//              On essaie d'exécuter les transactions :)!!!
            $res = $transaction_ser->_execute($dm, $transaction_tmp);
            if($res){
              $this->addFlash('notice', 'Votre achat a été confirmé, vous pouvez déjà consulter votre cours : '.$course->getName());
            }
          }
        }
        
        $dm->persist($transaction);
        $dm->persist($user);
        $dm->persist($receiver);
        $dm->flush();
        
        $this->addFlash('notice', 'Votre paiment a été enregistré. Vous recevrez une confirmation dans quelques minutes');
//        On vide les cours  2 la session
        $session->set('courses', null);
        return $this->redirectToRoute('xs_education_my_account');
//        }catch(\Exception $exception){
        $this->addFlash('error', 'Aucun cours sélectionné!');
        return $this->redirectToRoute('xs_user_subscribe');
//        }
      
      }
      else{
        $this->addFlash('error', 'Votre formulaire n\'est pas valide');
        return $this->redirectToRoute('xs_user_subscribe');
      }
    }
    
    return $this->render('@XSUser/User/subscribe.html.twig', array(
      'courses' => $courses,
      'form' => $form->createView()
    ));
  }
  
  public function jsonMyContactsAction(Request $request){
    $response = new JsonResponse();
    $query = $request->query->get('query');
    $user = $this->getUser();
    
    $entries = array();
    /*foreach($user->getFollowers() as $entries_info){
      $tmp = [
        'value' => $entries_info->getId(),
        'text' => $entries_info->getNickname(),
      ];
      $entries[] = $tmp;
    }*/
    
    foreach($user->getFollowees() as $entries_info){
      $entries[] = [
        'value' => $entries_info->getId(),
        'text' => $entries_info->getNickname(),
//        "value" => $query . ' - ' . rand(10, 100),
//        "desc" => $entries_info->getEdu()->getStudent()->getClasse()->getFormattedName(),
        "desc" => 'Description',
        "img" => "http://lorempixel.com/50/50/?" . (rand(1, 10000) . rand(1, 10000)),
        "tokens" => array($entries_info->getNickname(), 'Terminale')
      ];
    }
    
    
    
    $response->setData($entries);
    return $response;
  }
  
}
