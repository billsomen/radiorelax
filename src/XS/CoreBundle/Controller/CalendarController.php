<?php

namespace XS\CoreBundle\Controller;

use AppBundle\Service\Locale;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use XS\AfrobankBundle\Document\Amount;
use XS\MarketPlaceBundle\Document\Cart;
use XS\MarketPlaceBundle\Document\User;

class CalendarController extends Controller
{
  public function indexAction($name)
  {
    return $this->render('', array('name' => $name));
  }
  
  public function jsonShowSessionAction(Request $request, $tutor_id, $id){
//    To show the session (en entry on the calendar) of a tutor
    $dm = $this->get('doctrine_mongodb')->getManager();
    $tutor = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
      'id' => $tutor_id
    ));
    $message = "Erreur, la session sélectionnée n'existe pas!";
    $type = "error";
    $tutoring_session = $tutor->getCalendar()->getSession($id);
//    print_r($tutoring_session->getAccessPrice()->getValue());
    $user = $this->getUser();
    $in_cart = false;
    $cart = null;
    if(isset($user)){
//      $market = new User();

//      $user->setMarket($market);
      $cart = $user->getMarket()->getCart();
      /*$dm->persist($user);
      $dm->flush();*/
      $in_cart = $cart->isSessionInCart($tutoring_session);
    }
    else{
      $session = $request->getSession();
      $cart = $session->get('cart');
      if(!isset($cart)){
        $session->set('cart', new Cart());
      }
      else{
        $in_cart = $cart->isSessionInCart($tutoring_session);
      }
    }
    if(!is_null($tutoring_session)){
      if(empty($tutoring_session->getAccessPrice())){
        $tutoring_session->setAccessPrice(new Amount());
      }
      return $this->container->get('templating')->renderResponse('@XSCore/Calendar/session-module.html.twig', array(
        'tutor' => $tutor,
        'in_cart' => $in_cart,
        'session' => $tutoring_session,
      ));
    }
    else{
      $response = new JsonResponse(array(
        'message' => $message,
        'type' => $type
      ));
      return $response;
    }
  }
  
  public function jsonEntriesAction(Request $request, $type, $id){
    $start = $request->query->get('start');
    $end = $request->query->get('end');
    $response = new JsonResponse();
    $user = $this->getUser();
    $locale_action = new Locale();
    $locale = $locale_action->defaultLocale($user);
    
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $element = null;
    $calendar = null;
    $cal_type = null;
    $name = null;
    switch($type){
      case 'school':
        $element = $dm->getRepository('XSEducationBundle:School')->findOneBy(array(
          'id' => $id
        ));
        $calendar = $element->getCalendar();
        $cal_type = 'school';
        $name = $element->getName();
        break;
      case 'class_room':
        $element = $dm->getRepository('XSEducationBundle:SubClasse')->findOneBy(array(
          'id' => $id
        ));
        $calendar = $element->getCalendar();
        $calendar->addCalendar($element->getSchool()->getCalendar());
        $cal_type = 'class_room';
        
        $name = $element->getFormattedName();
        break;
      case 'tutoring':
        $element = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
          'id' => $id
        ));
        
        $calendar = $element->getCalendar()->getTutoringCalendar();
        
        $name = $element->getNickname();
        $cal_type = 'tutoring';
        break;
      case 'me':
        $element = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
          'id' => $id
        ));
        $calendar = $element->getCalendar();
//        $calendar->addCalendar($element->getCalendar()->getTutoringCalendar());
        $cal_type = 'me';
        $name = $element->getNickname();
        break;
    }
//    $entries_infos = $calendar->getEntries();
    $entries_infos = $calendar->getEntries(new \DateTime($start), new \DateTime($end));
    $entries = array();
    $color = 'orange';
    foreach($entries_infos as $entries_info){
      switch($entries_info->getSource()){
        case 'Task':
          $color = 'red';
          break;
        case 'Tutoring':
          $color = 'green';
          break;
        case 'School':
          $color = 'green';
          break;
        case 'SubClasse':
          $color = 'blue';
          break;
      }
      
      if(!empty($entries_info->getSubject())){
        $subject = $entries_info->getSubject()->getName();
      }
      if(!$entries_info->getIsParent()){
        $entry_id = $entries_info->getId();
        $formatted_price = new Amount();
        if(!empty($entries_info->getAccessPrice())){
          $formatted_price = $entries_info->getAccessPrice();
        }
//        TODO if calendar_entry == paid_session, on effectue une requete
        if($entries_info->getType() == 'session'){
          $tutor = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
            'id' => $entries_info->getTutorId()
          ));
          $entry_id = $entries_info->getSessionId();
          $entries_info = $tutor->getCalendar()->getSession($entry_id);
          $name = $tutor->getNickname();
          $color = 'green';
        }
        $formatted_price = $formatted_price->getFormatted();
        $tmp = [
          'title' => $entries_info->getName(),
          'author_name' => $name,
          'entry_id' => $entry_id,
          'start' => $entries_info->getDateFrom()->format("Y-m-d\TH:i:s"),
          'end' => $entries_info->getDateTo()->format("Y-m-d\TH:i:s"),
          'status' => $entries_info->getStatus(),
          'author_id' => $id,
          'calendar_type' => $cal_type,
          'description' => $entries_info->getDescription(),
          "textEscape" => false,
          'className' => 'tooltips',
          'backgroundColor' => $color,
        ];
        $entries[] = $tmp;
      }

//      break;
    }
    
    $response->setData($entries);
    return $response;
  }
}
