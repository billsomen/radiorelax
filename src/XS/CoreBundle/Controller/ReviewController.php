<?php

namespace XS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use XS\CoreBundle\Document\Review;
use XS\CoreBundle\Form\ReviewType;

class ReviewController extends Controller
{
  public function indexAction($name)
  {
    return $this->render('', array('name' => $name));
  }
  
  public function addAction(Request $request, $target_name, $target_id)
  {
    //Cette methode ajoute la re*ue
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $session = $request->getSession();
    $target = $session->get('target_path');
    $review = new Review();
    $object = null;
    $link = null;
    $user = $this->getUser();
    $message_type = "error";
    $message_content = "Erreur inconnue! Merci de recommencer plus tard!";
    
    $found = $user->checkReview($user->getId(), $target_name, $target_id);
    if(is_null($found)){
      $form = $this->createForm(ReviewType::class, $review);
      if($request->isMethod('POST')){
        $form->handleRequest($request);
        if($form->isValid()){
          
          $review->setAuthor($user);
          //On effectue un switch case pour couvrir le cas et enregistrer dans le bon document.
          $doc = $target_name;
          $id = $target_id;
          $object = null;
          $question = null;
          $listener = null;
          $link = null;
          $message = $user->getNickname().' a aussi ajouté une revue sur ';
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
              $object = $dm->getRepository('XSMarketPlaceBundle:Product')->findOneById($id);
              $link = $this->generateUrl('xs_market_place_products_show', array('id' => $object->getId()));
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
          
          //On ajoute la review
          if($object != null) {
            $object->getReviews()->add($review);
            $review->setTargetName($target_name);
            $review->setTargetId($target_id);
            
            $user->getReviews()->add($review);
            $dm->persist($object);
            $dm->persist($user);
            $dm->flush();
          }
        }
        if($request->isXmlHttpRequest()){
          $response = new JsonResponse();
          $response->setData(array(
            'type' => $message_type,
            'content' => $message_content,
          ));
    
          return $response;
        }
        else{
          $this->addFlash($message_type, $message_content);
          try{
            return $this->redirectToRoute($link);
          }
          catch(\Exception $exception){
            return $this->redirectToRoute('xs_market_place_homepage');
          }
        }
      }
      
      return $this->render('@XSCore/Review/add.html.twig', array(
        'form' => $form->createView(),
        'target_name' => $target_name,
        'target_id' => $target_id,
      ));
    }
    else{
      $message_type = 'error';
      $message_content = "Vous ne pouvez ajouter plus d'une revue sur cet élément!";
    }
    
    //On recupere l'URL
    $url = $session->get('url');
    //On redirige a la source...
    if($request->isXmlHttpRequest()){
      $response = new JsonResponse();
      $response->setData(array(
        'type' => $message_type,
        'content' => $message_content,
      ));
      
      return $response;
      /*return $this->container->get('templating')->renderResponse('@XSCore/Review/_show.html.twig', array(
        'review' => $review,
        'target_name' => $target_name,
        'id_question' => $id_question,
        'localId' => $review_id,
        'target_id' => $target_id,
      ));*/
    }
    else{
      $this->addFlash($message_type, $message_content);
      try{
        return $this->redirectToRoute($link);
      }
      catch(\Exception $exception){
        return $this->redirectToRoute('xs_market_place_homepage');
      }
    }
    
    
  }
}
