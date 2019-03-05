<?php

namespace XS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\CoreBundle\Document\Envy;

class EnvyController extends Controller
{
  protected $envy;
  
  /**
   * EnvyController constructor.
   */
  public function __construct() {
    //On prepare notre objet modele... :)
    $this->envy = new Envy();
//        $this->envy->setUser($this->getUser());
  }
  
  public function addView($data, $dm, $user)
  {
    //Permet d'ajouter une vue a un Doc si elle n'existe pas, et ne fait rien sinon...
    //Un peu comme un service, mais, je suis un peu faignant pour lire comment ca se passe...
    //Finalement, on NE gere avec l'evenement doctrine preLoad parce que ce serait plus facile a mettre en place, mais tres lourd pour le serveur...
    //On gere les vues...
    //Bref, c'est en service...
    $viewed = false;
    $views = $data->getViews();
    foreach($views as $view){
      if($view->getUser() == $user){
        $viewed = true;
      }
    }
    if(!$viewed){
      //Alors, on ajout l'utilisateur a la liste...
      $user_view = new Envy();
      $user_view->setUser($user);
      $data->addView($user_view);
    }
    
    $dm->persist($data);
    $dm->flush();
    //Et c'est tout...
  }
  
  
  public function likeAction($obj_id, $obj_name, Request $request, $comment_id=0)
  {
    //Si on trouve l'objet, on persiste les valeurs et on rentre d'ou on vient.
    //todo: S'assurer que la personne n'aime pas deja... <<<LOL, genere dans la vue, en rendant le lien inacessible... :)
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $object = null;
    $session = $request->getSession();
    $target = $session->get('target_path');
    
    switch ($obj_name) {
      case 'Store':
        $object = $dm->getRepository('MainBundle:Store')->findOneById($obj_id);
        break;
      
      case 'Afrobanking':
        $object = $dm->getRepository('XSAfrobankBundle:Afrobank')->findOneById($obj_id);
        break;
      
      case 'BledDownloader':
        $object = $dm->getRepository('XSBledDownloaderBundle:BledDownloader')->findOneById($obj_id);
        break;
      
      case 'Product':
        $object = $dm->getRepository('MainBundle:Product')->findOneById($obj_id);
        break;
      
      case 'Service':
        $object = $dm->getRepository('MainBundle:Service')->findOneById($obj_id);
        break;
      
      case 'Course':
        $object = $dm->getRepository('XSEducationBundle:Course')->findOneById($obj_id);
        $link = $this->generateUrl('xs_education_courses_show', array('id' => $object->getId()));
        break;
      
      default:
        # code...
        break;
    }
    if($object != null){
      //On peut persister...
      //On fait la relation.
      $user = $this->getUser();
      if($comment_id !=0){
        foreach($object->getComments() as $comment_master){
          if($comment_master->getLocalId() == $comment_id){
            $comment_master->addLike($user);
            break;
          }
        }
      }
      else{
        $object->addLike($user);
      }
      $dm->persist($object);
      $dm->flush();
      
      if($request->isXmlHttpRequest())
      {
        return $this->container->get('templating')->renderResponse('@XSCore/Envy/_showLike.html.twig', array(
          'object' => $object,
          'liked' => true,
          'obj_name' => $obj_name,
          'comment_id' => $comment_id,
          'obj_id' => $obj_id,
        ));
      }
      
      $url = $request->getSession()->get('url');
      try{
        return $this->redirect($target);
      }catch(\Exception $exception){
      
      }
    }
    return $this->redirectToRoute('xs_education_my_profile');
  }
  
  /*    public function dislikeAction($obj_id, $obj_name, Request $request)
      {
          //Si on trouve l'objet, on persiste les valeurs et on rentre d'ou on vient.
          //todo: S'assurer que la personne n'aime pas deja... <<<LOL, genere dans la vue, en rendant le lien inacessible... :)
          $dm = $this->get('doctrine.odm.mongodb.document_manager');
          $object = null;
          switch ($obj_name) {
              case 'Store':
                  $object = $dm->getRepository('MainBundle:Store')->findOneById($obj_id);
                  break;
  
              default:
                  # code...
                  break;
          }
          var_dump($object);
          if($object != null){
              //On peut persister...
              $envy = $this->getEnvy();
              //On fait la relation.
              $envy->setUser($this->getUser());
              $object->addDisLike($envy);
              $dm->persist($object);
              //$dm->flush();
              $url = $request->getSession()->get('url');
              return $this->redirect(($url));
          }
          //Si aucun objet n'est trouve, on rentre a la page d'accueil
          return new Response(333);
  //        return $this->redirect($this->generateUrl('main_home'));
      }*/
  
  /**
   * @return mixed
   */
  public function getEnvy() {
    return $this->envy;
  }
  
  /**
   * @param mixed $envy
   */
  public function setEnvy($envy) {
    $this->envy = $envy;
  }
  
  
}
