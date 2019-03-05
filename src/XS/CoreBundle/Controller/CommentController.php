<?php

namespace XS\CoreBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\CoreBundle\Document\Comment;
use XS\CoreBundle\Document\Image;
use XS\CoreBundle\Form\CommentMinType;
use XS\CoreBundle\Form\CommentType;

class CommentController extends Controller
{
  
  public function addAction(Request $request, $obj_name, $is_question=0, $obj_id, $comment_id=0, $id_question=0)
  {
    //Cette methode ajoute le commentaire a n'importe quel objet passe en parametre
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $session = $request->getSession();
    $target = $session->get('target_path');
    $comment = new Comment();
    
    $form = $this->createForm(CommentType::class, $comment);
    if($request->isMethod('POST')){
      $form->handleRequest($request);
      if($form->isValid()){
        //                Gestionnaire de photos...
        $photos = $comment->getPhotos();
        $comment->setPhotos(new ArrayCollection());
        $doc = null;
        try{
          if($photos[0] != null) {
            foreach($photos as $image){
              $doc = new Image();
              
              $doc->setFile($image->getPathname());
              $doc->setFilename($image->getClientOriginalName());
              $doc->setMimeType($image->getClientMimeType());
              
              $dm->persist($doc);
              $comment->addPhoto($doc);
            }
          }
        }catch(\Exception $exception){
//          print_r('No picture');
        }
        
        
        //On effectue les relations.
        $user = $this->getUser();
        
        $comment->setAuthor($user);
        //On recupere l'objet de la session
        //On effectue un switch case pour couvrir le cas et enregistrer dans le bon document.
        $doc = $obj_name;
        $id = $obj_id;
        $object = null;
        $question = null;
        $listener = null;
        $link = null;
        $message = $user->getNickname().' a aussi ajouté un commentaire sur ';
        switch ($doc) {
          case 'Ad':
            $object = $dm->getRepository('MainBundle:Ad')->findOneById($id);
            $message .= 'une annonce';
            $link = $this->generateUrl('main_ads_show', array('id' => $object->getId()));
            break;
          case 'Course':
            $object = $dm->getRepository('XSEducationBundle:Course')->findOneById($id);
            $message .= 'un cours';
            $link = $this->generateUrl('xs_education_courses_show', array('id' => $object->getId()));
            break;
          
          case 'ClassGroup':
            $object = $dm->getRepository('XSEducationBundle:ClassGroup')->findOneById($id);
            $message .= 'un groupe';
            if($is_question){
              foreach($object->getQuestions() as $question_info){
                if($question_info->getId() == $id_question){
                  $question = $question_info;
                  break;
                }
              }
              if($question){
                $link = $this->generateUrl('xs_mboadjoss_class_groups_show_question', array(
                  'id' => $object->getId(),
                  'id_question' => $id_question
                ));
              }
            }
            break;
          case 'BledDownloader':
            $object = $dm->getRepository('XSAfrobankBundle:Afrobank')->findOneById($id);
            $message .= 'une banque';
            $link = $this->generateUrl('xs_afrobank_home');
            break;
          
          case 'Product':
            $object = $dm->getRepository('MainBundle:Product')->findOneById($id);
            $link = $this->generateUrl('main_products_show', array('id' => $object->getId()));
            $message .= 'un produit';
            break;
          
          case 'Service':
            $object = $dm->getRepository('MainBundle:Service')->findOneById($id);
            $link = $this->generateUrl('main_services_show', array('id' => $object->getId()));
            $message .= 'un service';
            break;
          
          default:
            # code...
            break;
        }
      }
      //On ajoute le commentaire
      if($is_question){
        if($object != null) {
          if($comment_id !=0){
            $flag = false;
            foreach($question->getComments() as $comment_master){
              if($comment_master->getLocalId() == $comment_id){
                $comment_master->addComment($comment);
                $flag = true;
                break;
              }
            }
          }
        }
        else{
          $question->addComment($comment);
        }
      }
      else{
        if($object != null) {
          if($comment_id !=0){
            foreach($object->getComments() as $comment_master){
              if($comment_master->getLocalId() == $comment_id){
                $comment_master->addComment($comment);
                break;
              }
            }
          }
          else{
            $object->addComment($comment);
          }
        }
      }
      
      $dm->persist($object);
      $dm->flush();

//                    On loop sur tous les gars qui ont deja commente...
//                    Tous les gars qui ont deja commente, doublons compris
//                    TODO : ensuite, on appelle la fonction array_unique() qui va effacer les doublons... :)
      $listeners = null;
      foreach($object->getComments() as $cmt) {
        $listeners[] = $cmt->getAuthor();
      }
      if($question){
        foreach($question->getComments() as $cmt) {
          $listeners[] = $cmt->getAuthor();
        }
      }
      $results = array_unique($listeners);
//Pour eviter les doublons...
//          $notification = $this->get('app.notification_controller')->create($message, $link, $dm);
      foreach($results as $listener){
        if ($listener != null and $listener != $user) {
          //        On peut generer la notif...
//              $this->get('app.notification_controller')->associate($notification, $listener, $dm);
        }
      }
      $dm->flush();
//                    TODO : END Notif...
    }
    
    //On recupere l'URL
    $url = $session->get('url');
    //On redirige a la source...
    if($request->isXmlHttpRequest())
    {
      if($comment_id != 0 and !$is_question){
//            Its a reply
        return $this->container->get('templating')->renderResponse('@XSCore/Comment/_showResponse.html.twig', array(
          'reply' => $comment
        ));
      }
      else{
        return $this->container->get('templating')->renderResponse('@XSCore/Comment/_show.html.twig', array(
          'comment' => $comment,
          'obj_name' => $obj_name,
          'id_question' => $id_question,
          'localId' => $comment_id,
          'obj_id' => $obj_id,
        ));
      }
      
    }
    
    /*try{
      return $this->redirect($target);
    }catch(\Exception $exception){
      return $this->redirectToRoute('xs_education_my_profile');
    }*/
    
    
    
    return $this->render('@XSCore/Comment/add.html.twig', array(
      'form' => $form->createView(),
      'obj_name' => $obj_name,
      'localId' => $comment_id,
      'obj_id' => $obj_id,
    ));
  }
  
  public function addToQuestionAction(Request $request, $obj_id, $comment_id=0, $id_question=0)
  {
    //Cette methode ajoute le commentaire a n'importe quel objet passe en parametre
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $session = $request->getSession();
    $target = $session->get('target_path');
    $comment = new Comment();
    
    $form = $this->createForm(CommentType::class, $comment);
    if($request->isMethod('POST')){
      $form->handleRequest($request);
      if($form->isValid()){
        $user = $this->getUser();
        
        $comment->setAuthor($user);
        //On recupere l'objet de la session
        //On effectue un switch case pour couvrir le cas et enregistrer dans le bon document.
        $id = $obj_id;
        $group = null;
        $question = null;
        $listener = null;
        $link = null;
        $group = $dm->getRepository('XSEducationBundle:ClassGroup')->findOneBy(array(
          'id'=>$id
        ));
        $message = $user->getNickname().' a aussi ajouté un commentaire le groupe ';
        if(isset($group)){
          $message .= $group->getName();
//          On cherche la question
          foreach($group->getQuestions() as $question_info){
            if($question_info->getId() == $id_question){
              $question = $question_info;
              break;
            }
          }
          
          if(isset($question)){
            $link = $this->generateUrl('xs_mboadjoss_class_groups_show_question', array(
              'id' => $group->getId(),
              'id_question' => $id_question
            ));
//            On ajoute le commentaire
            if($comment_id !=0){
              print_r(5555555555555);
              print_r($comment_id);
              $flag = false;
              foreach($question->getComments() as $comment_master){
                if($comment_master->getLocalId() == $comment_id){
                  $comment_master->addComment($comment);
                  $flag = true;
                  break;
                }
              }
            }
            else{
              $question->addComment($comment);
            }
            $dm->persist($group);
            $dm->flush();
          }
          
          
        }
  
        if($request->isXmlHttpRequest())
        {
          if($comment_id != 0){
//            Its a reply
            return $this->container->get('templating')->renderResponse('@XSCore/Comment/_showResponseFromQuestion.html.twig', array(
              'reply' => $comment
            ));
          }
          else{
            return $this->container->get('templating')->renderResponse('@XSCore/Comment/_showFromQuestion.html.twig', array(
              'comment' => $comment,
              'id_question' => $id_question,
              'localId' => $comment_id,
              'obj_id' => $obj_id,
            ));
          }
        }
      }
    }
    
    return $this->render('@XSCore/Comment/addToQuestion.html.twig', array(
      'form' => $form->createView(),
      'id_question' => $id_question,
      'localId' => $comment_id,
      'obj_id' => $obj_id,
    ));
  }
  
  public function addMinAction(Request $request, $obj_name, $obj_id)
  {
    //Cette methode ajoute le commentaire a n'importe quel objet passe en parametre
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    
    $comment = new Comment();
    $form = $this->createForm(new CommentMinType(), $comment);
    if($request->isMethod('POST')){
      $form->handleRequest($request);
      if($form->isValid()){
        $photos = $comment->getPhotos();
        $doc = new Image();
        //On boucle pour bien formater les photos...
        if(isset($photos)){
          foreach($photos as $image){
            //On a pri les photos par reference
            $doc->setFile($image->getPathname());
            $doc->setFilename($image->getClientOriginalName());
            $doc->setMimeType($image->getClientMimeType());
            $image = $doc;
            //On persiste une fois la photo..
            $dm->persist($image);
          }
          $dm->persist($photos);
        }
        //On detruit la reference sur le dernier element du tableau
        unset($image);
        //On MAJ notre annonce...
        $comment->setPhotos($photos);
        //On effectue les relations.
        $user = $this->getUser();
        
        $comment->setAuthor($user);
        //On recupere l'objet de la session
        $session = $request->getSession();
        //On effectue un switch case pour couvrir le cas et enregistrer dans le bon document.
        $doc = $obj_name;
        $id = $obj_id;
        $object = null;
        $listener = null;
        $link = null;
        $message = $user->getNickname().' a aussi ajouté un commentaire sur ';
        switch ($doc) {
          case 'Ad':
            $object = $dm->getRepository('MainBundle:Ad')->findOneById($id);
            $message .= 'une annonce';
            $link = $this->generateUrl('main_ads_show', array('id' => $object->getId()));
            break;
          case 'BledDownloader':
            $object = $dm->getRepository('XSAfrobankBundle:Afrobank')->findOneById($id);
            $message .= 'une banque';
            $link = $this->generateUrl('xs_afrobank_home');
            break;
          
          case 'Product':
            $object = $dm->getRepository('MainBundle:Product')->findOneById($id);
            $link = $this->generateUrl('main_products_show', array('id' => $object->getId()));
            $message .= 'un produit';
            break;
          
          case 'Service':
            $object = $dm->getRepository('MainBundle:Service')->findOneById($id);
            $link = $this->generateUrl('main_services_show', array('id' => $object->getId()));
            $message .= 'un service';
            break;
          
          default:
            # code...
            break;
        }
        //On ajoute le commentaire
        if($object != null) {
          $object->addComment($comment);
          $dm->persist($object);
          $dm->flush();

//                    On loop sur tous les gars qui ont deja commente...
//                    Tous les gars qui ont deja commente, doublons compris
//                    TODO : ensuite, on appelle la fonction array_unique() qui va effacer les doublons... :)
          $listeners = null;
          foreach($object->getComments() as $cmt) {
            $listeners[] = $cmt->getAuthor();
          }
          $results = array_unique($listeners);
//Pour eviter les doublons...
          $notification = $this->get('app.notification_controller')->create($message, $link, $dm);
          foreach($results as $listener){
            if ($listener != null and $listener != $user) {
              //        On peut generer la notif...
              $this->get('app.notification_controller')->associate($notification, $listener, $dm);
            }
          }
          $dm->flush();

//                    TODO : END Notif...
          
        }
        
        //On recupere l'URL
        $url = $session->get('url');
        //On redirige a la source...
        return $this->redirect($url);
      }
    }
    return $this->render('@XSCore/Comment/addMin.html.twig', array(
      'form' => $form->createView()
    ));
  }
  
  
}
