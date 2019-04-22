<?php

namespace XS\MarketPlaceBundle\Controller;

use JMS\Serializer\Tests\Fixtures\Discriminator\Car;
use function MongoDB\BSON\toJSON;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use XS\MarketPlaceBundle\Document\Cart;

class DefaultController extends Controller
{
  public function indexAction(Request $request)
  {
    $session = $request->getSession();
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $flag_price = false;
    $flag_school_distance = false;
    $flag_route_distance = false;
    $flag_type = false;
    $flag_parking_gte = false;
    $flag_living_room_gte = false;
    $flag_bed_room_gte = false;
    $flag_bath_room_gte = false;
    $flag_kitchen_gte = false;
    $flag_furnished = false;
    
    $parking_gte = $request->query->get('parking_gte')+0;
    $living_room_gte = $request->query->get('living_room_gte')+0;
    $bed_room_gte = $request->query->get('bed_room_gte')+0;
    $bath_room_gte = $request->query->get('bath_room_gte')+0;
    $kitchen_gte = $request->query->get('kitchen_gte')+0;
    
    $min_price = $request->query->get('min_price');
    $max_price = $request->query->get('max_price');
    
    $min_school_distance = $request->query->get('min_school_distance');
    $max_school_distance = $request->query->get('max_school_distance');
    
    $min_route_distance = $request->query->get('min_route_distance');
    $max_route_distance = $request->query->get('max_route_distance');
    
    $house_type_plus = $request->query->get('house_type_plus');
    $house_type_minus = $request->query->get('house_type_minus');
    $cancel = $request->query->get('cancel');
    
    $furnished_plus = $request->query->get('furnished_plus');
    $furnished_minus = $request->query->get('furnished_minus');
    
    $furnished = $session->get('furnished');
    $house_types = $session->get('house_types');
    
    $views = array("grid", "list");
    $view = $request->query->get('view');
    
    if(isset($view)){
      if(!in_array($view, $views)){
        $view = $views[0];
      }
      $session->set('view', $view);
    }
    else{
      $view = $session->get('view');
    }
    
    
    if(isset($parking_gte)){
      $session->set('parking_gte', $parking_gte);
    }
    else{
      $parking_gte = $session->get('parking_gte');
    }
    if($parking_gte > 5){
      $parking_gte = 5;
    }
    
    if(isset($living_room_gte)){
      $session->set('living_room_gte', $living_room_gte);
    }
    else{
      $living_room_gte = $session->get('living_room_gte');
    }
    if($living_room_gte > 5){
      $living_room_gte = 5;
    }
    
    if(isset($bed_room_gte)){
      $session->set('bed_room_gte', $bed_room_gte);
    }
    else{
      $bed_room_gte = $session->get('bed_room_gte');
    }
    if($bed_room_gte > 5){
      $bed_room_gte = 5;
    }
    
    if(isset($bath_room_gte)){
      $session->set('bath_room_gte', $bath_room_gte);
    }
    else{
      $bath_room_gte = $session->get('bath_room_gte');
    }
    if($bath_room_gte > 5){
      $bath_room_gte = 5;
    }
    
    if(isset($kitchen_gte)){
      $session->set('kitchen_gte', $kitchen_gte);
    }
    else{
      $kitchen_gte = $session->get('kitchen_gte');
    }
    if($kitchen_gte > 5){
      $kitchen_gte = 5;
    }
    
    if(isset($min_price)){
      $session->set('min_price', $min_price);
    }
    else{
      $min_price = $session->get('min_price');
    }
    
    if(isset($max_price)){
      $session->set('max_price', $max_price);
    }
    else{
      $max_price = $session->get('max_price');
    }
    
    if(isset($min_school_distance)){
      $session->set('min_school_distance', $min_school_distance);
    }
    else{
      $min_school_distance = $session->get('min_school_distance');
    }
    
    if(isset($max_school_distance)){
      $session->set('max_school_distance', $max_school_distance);
    }
    else{
      $max_school_distance = $session->get('max_school_distance');
    }
    
    if(isset($min_route_distance)){
      $session->set('min_route_distance', $min_route_distance);
    }
    else{
      $min_route_distance = $session->get('min_route_distance');
    }
    
    if(isset($max_route_distance)){
      $session->set('max_route_distance', $max_route_distance);
    }
    else{
      $max_route_distance = $session->get('max_route_distance');
    }
    
    /* if(isset($house_type_plus)){
       $session->set('house_type_plus', $house_type_plus);
     }
     else{
       $house_type_plus = $session->get('house_type_plus');
     }
     
     if(isset($house_type_minus)){
       $session->set('house_type_minus', $house_type_minus);
     }
     else{
       $house_type_minus = $session->get('house_type_minus');
     }
     
     if(isset($furnished_plus)){
       $session->set('furnished_plus', $furnished_plus);
     }
     else{
       $furnished_plus = $session->get('furnished_plus');
     }
     
     if(isset($furnished_minus)){
       $session->set('furnished_minus', $furnished_minus);
     }
     else{
       $furnished_minus = $session->get('furnished_minus');
     }*/
    
    if(!isset($furnished)){
      $furnished = array();
    }

//    Quels paremètres sont MAJ ?
    if(isset($house_type_minus) OR isset($furnished_plus)){
      $flag_type = true;
    }
    
    if(isset($furnished_minus) OR isset($furnished_plus)){
      $flag_furnished = true;
    }

//    Initialisations
    if($house_types == null or empty($house_types)){
      $house_types = array();
    }
    
    if($furnished == null or empty($furnished)){
      $furnished = array();
    }

//    $house_types_enc = urlencode($house_types_enc);
//    $house_types_enc = urlencode($house_types_enc);
//    print_r($house_types_enc);


//    Specs for all the Store
    $specs_defaults = array();
    $specs = array();
    $defaults = array();
    
    $products = $dm->getRepository('XSMarketPlaceBundle:Product')->findAll();
    foreach($products as $product){
      $specs_defaults['price'][] = $product->getPayMonth()->getValue();
      $specs_defaults['school_distance'][] = $product->getHouse()->getDistanceSchool();
      $specs_defaults['route_distance'][] = $product->getHouse()->getDistanceToRoad();
      $specs_defaults['type'][$product->getHouse()->getType()] = 0;
      $specs_defaults['furnished'][$product->getHouse()->getFurnished()] = 0;
      $specs_defaults['parking'][$product->getHouse()->getParking()] = 0;
      $specs_defaults['bath_room'][$product->getHouse()->getRoom()->getToilet()] = 0;
      $specs_defaults['kitchen'][$product->getHouse()->getRoom()->getKitchen()] = 0;
      $specs_defaults['bed_room'][$product->getHouse()->getRoom()->getBedRoom()] = 0;
      $specs_defaults['living_room'][$product->getHouse()->getRoom()->getLivingRoom()] = 0;
      /*if($product->getHouse()->getFurnished() == false){
        $specs_defaults['furnished'][0] = 0;
      }else{
        $specs_defaults['furnished'][1] = 0;
      }*/
    }
    
    $specs_defaults['furnished'] = array_keys($specs_defaults['furnished']);
    $specs_defaults['type'] = array_keys($specs_defaults['type']);
    
//    print_r($specs_defaults["parking"]);
    if($cancel == 1){
//          defaults
      $view = 'list';
      $session->set('view', $view);
      $session->set('min_school_distance', null);
      $session->set('max_school_distance', null);
      $session->set('min_route_distance', null);
      $session->set('max_route_distance', null);
      $session->set('furnished', $specs_defaults['furnished']);
      $session->set('house_types', $specs_defaults['type']);
      $session->set('price', null);
      $session->set('parking_gte', 0);
      $session->set('living_room_gte', 0);
      $session->set('bed_room_gte', 0);
      $session->set('bath_room_gte', 0);
      $session->set('kitchen_gte', 0);
  
      if(!empty($specs_defaults['parking'])){
        $specs_defaults["parking"] = array_keys($specs_defaults['parking']);
        array_multisort($specs_defaults['parking'], SORT_DESC);
    
        $specs_defaults['parking']['min'] = ceil($specs_defaults['parking'][count($specs_defaults['parking'])-1]);
        $specs_defaults['parking']['max'] = ceil($specs_defaults['parking'][0]);
        if($specs_defaults['parking']['max'] > 5){
          $specs_defaults['parking']['max'] = 5;
        }
      }
  
      else{
//    print_r($specs_defaults);
        $specs_defaults['parking']['min'] = 0;
        $specs_defaults['parking']['max'] = 5;
      }
    
      if(!empty($specs_defaults['bath_room'])){
        $specs_defaults["bath_room"] = array_keys($specs_defaults['bath_room']);
        array_multisort($specs_defaults['bath_room'], SORT_DESC);
    
        $specs_defaults['bath_room']['min'] = ceil($specs_defaults['bath_room'][count($specs_defaults['bath_room'])-1]);
        $specs_defaults['bath_room']['max'] = ceil($specs_defaults['bath_room'][0]);
        if($specs_defaults['bath_room']['max'] > 5){
          $specs_defaults['bath_room']['max'] = 5;
        }
      }
  
      else{
//    print_r($specs_defaults);
        $specs_defaults['bath_room']['min'] = 0;
        $specs_defaults['bath_room']['max'] = 5;
      }
    
      if(!empty($specs_defaults['bed_room'])){
        $specs_defaults["bed_room"] = array_keys($specs_defaults['bed_room']);
        array_multisort($specs_defaults['bed_room'], SORT_DESC);
    
        $specs_defaults['bed_room']['min'] = ceil($specs_defaults['bed_room'][count($specs_defaults['bed_room'])-1]);
        $specs_defaults['bed_room']['max'] = ceil($specs_defaults['bed_room'][0]);
        if($specs_defaults['bed_room']['max'] > 5){
          $specs_defaults['bed_room']['max'] = 5;
        }
      }
  
      else{
//    print_r($specs_defaults);
        $specs_defaults['bed_room']['min'] = 0;
        $specs_defaults['bed_room']['max'] = 5;
      }
    
      if(!empty($specs_defaults['living_room'])){
        $specs_defaults["living_room"] = array_keys($specs_defaults['living_room']);
        array_multisort($specs_defaults['living_room'], SORT_DESC);
    
        $specs_defaults['living_room']['min'] = ceil($specs_defaults['living_room'][count($specs_defaults['living_room'])-1]);
        $specs_defaults['living_room']['max'] = ceil($specs_defaults['living_room'][0]);
        if($specs_defaults['living_room']['max'] > 5){
          $specs_defaults['living_room']['max'] = 5;
        }
      }
  
      else{
//    print_r($specs_defaults);
        $specs_defaults['living_room']['min'] = 0;
        $specs_defaults['living_room']['max'] = 5;
      }
    
      if(!empty($specs_defaults['kitchen'])){
        $specs_defaults["kitchen"] = array_keys($specs_defaults['kitchen']);
        array_multisort($specs_defaults['kitchen'], SORT_DESC);
    
        $specs_defaults['kitchen']['min'] = ceil($specs_defaults['kitchen'][count($specs_defaults['kitchen'])-1]);
        $specs_defaults['kitchen']['max'] = ceil($specs_defaults['kitchen'][0]);
        if($specs_defaults['kitchen']['max'] > 5){
          $specs_defaults['kitchen']['max'] = 5;
        }
      }
  
      else{
//    print_r($specs_defaults);
        $specs_defaults['kitchen']['min'] = 0;
        $specs_defaults['kitchen']['max'] = 5;
      }
  
      //Price
      if(!empty($specs_defaults['price'])){
        array_multisort($specs_defaults['price'], SORT_DESC);
//    print_r($specs_defaults);
        $specs_defaults['price']['min'] = ceil($specs_defaults['price'][count($specs_defaults['price'])-1])-1;
        $specs_defaults['price']['max'] = ceil($specs_defaults['price'][0])+1;
      }
  
      else{
//    print_r($specs_defaults);
        $specs_defaults['price']['min'] = 0;
        $specs_defaults['price']['max'] = 0;
      }
  
      if(!empty($specs_defaults['school_distance'])){
        array_multisort($specs_defaults['school_distance'], SORT_DESC);
        $specs_defaults['school_distance']['min'] = ceil($specs_defaults['school_distance'][count($specs_defaults['school_distance'])-1])-1;
        $specs_defaults['school_distance']['max'] = ceil($specs_defaults['school_distance'][0])+1;
      }
  
      else{
        $specs_defaults['school_distance']['min'] = 0;
        $specs_defaults['school_distance']['max'] = 0;
      }
  
      if(!empty($specs_defaults['route_distance'])){
        array_multisort($specs_defaults['route_distance'], SORT_DESC);
        $specs_defaults['route_distance']['min'] = ceil($specs_defaults['route_distance'][count($specs_defaults['route_distance'])-1])-1;
        $specs_defaults['route_distance']['max'] = ceil($specs_defaults['route_distance'][0])+1;
      }
  
      else{
        $specs_defaults['route_distance']['min'] = 0;
        $specs_defaults['route_distance']['max'] = 0;
      }
  
  
      $specs = $defaults = $specs_defaults;
//      $defaults['price']['min'] = $min_price+0;
//      $defaults['price']['max'] = $max_price+0;
//      $defaults['school_distance']['min'] = $min_school_distance+0;
//      $defaults['school_distance']['max'] = $max_school_distance+0;
//      $defaults['route_distance']['min'] = $min_route_distance+0;
//      $defaults['route_distance']['max'] = $max_route_distance+0;
      $defaults['parking'] = $parking_gte+0;
      $defaults['bath_room'] = $bath_room_gte+0;
      $defaults['kitchen'] = $kitchen_gte+0;
      $defaults['bed_room'] = $bed_room_gte+0;
      $defaults['living_room'] = $living_room_gte+0;
//      $defaults['type'] = $specs_defaults['type'];
//      $defaults['furnished'] = $specs_defaults['furnished'];
      
    }
    else{

//    Ajout et retraits
      if(isset($house_type_plus)){
        if(!in_array($house_type_plus, $house_types)){
          $house_types[] = $house_type_plus;
        }
      }
      
      if(isset($house_type_minus)){
        if(in_array($house_type_minus, $house_types)){
          $key = array_search($house_type_minus, $house_types);
          unset($house_types[$key]);
        }
      }
      
      if(isset($furnished_plus)){
        if(!in_array($furnished_plus, $furnished)){
          $furnished[] = $furnished_plus;
        }
      }
      
      if(isset($furnished_minus)){
        if(in_array($furnished_minus, $furnished)){
          
          $key = array_search($furnished_minus, $furnished);
          unset($furnished[$key]);
        }
      }

//    $this->purge_array($house_types);
//    $this->purge_array($furnished);
//    print_r($furnished);
      
      //    We set on the session :) !
      
      $session->set('house_types', $house_types);
      $session->set('furnished', $furnished);
//      print_r(school)

//    Ranger defaults
//    Parking
      if(!empty($specs_defaults['parking'])){
        $specs_defaults["parking"] = array_keys($specs_defaults['parking']);
        array_multisort($specs_defaults['parking'], SORT_DESC);
        
        $specs_defaults['parking']['min'] = ceil($specs_defaults['parking'][count($specs_defaults['parking'])-1]);
        $specs_defaults['parking']['max'] = ceil($specs_defaults['parking'][0]);
        if($specs_defaults['parking']['max'] > 5){
          $specs_defaults['parking']['max'] = 5;
        }
      }
      
      else{
//    print_r($specs_defaults);
        $specs_defaults['parking']['min'] = 0;
        $specs_defaults['parking']['max'] = 5;
      }
      
      //    Living_room
      if(!empty($specs_defaults['living_room'])){
        $specs_defaults["living_room"] = array_keys($specs_defaults['living_room']);
        array_multisort($specs_defaults['living_room'], SORT_DESC);
        
        $specs_defaults['living_room']['min'] = ceil($specs_defaults['living_room'][count($specs_defaults['living_room'])-1]);
        $specs_defaults['living_room']['max'] = ceil($specs_defaults['living_room'][0]);
        if($specs_defaults['living_room']['max'] > 5){
          $specs_defaults['living_room']['max'] = 5;
        }
      }
      
      else{
//    print_r($specs_defaults);
        $specs_defaults['living_room']['min'] = 0;
        $specs_defaults['living_room']['max'] = 5;
      }
      //    Kitchen
      if(!empty($specs_defaults['kitchen'])){
        $specs_defaults["kitchen"] = array_keys($specs_defaults['kitchen']);
        array_multisort($specs_defaults['kitchen'], SORT_DESC);
        
        $specs_defaults['kitchen']['min'] = ceil($specs_defaults['kitchen'][count($specs_defaults['kitchen'])-1]);
        $specs_defaults['kitchen']['max'] = ceil($specs_defaults['kitchen'][0]);
        if($specs_defaults['kitchen']['max'] > 5){
          $specs_defaults['kitchen']['max'] = 5;
        }
      }
      
      else{
//    print_r($specs_defaults);
        $specs_defaults['kitchen']['min'] = 0;
        $specs_defaults['kitchen']['max'] = 5;
      }
      //    Bed_room
      if(!empty($specs_defaults['bed_room'])){
        $specs_defaults["bed_room"] = array_keys($specs_defaults['bed_room']);
        array_multisort($specs_defaults['bed_room'], SORT_DESC);
        
        $specs_defaults['bed_room']['min'] = ceil($specs_defaults['bed_room'][count($specs_defaults['bed_room'])-1]);
        $specs_defaults['bed_room']['max'] = ceil($specs_defaults['bed_room'][0]);
        if($specs_defaults['bed_room']['max'] > 5){
          $specs_defaults['bed_room']['max'] = 5;
        }
      }
      
      else{
//    print_r($specs_defaults);
        $specs_defaults['bed_room']['min'] = 0;
        $specs_defaults['bed_room']['max'] = 5;
      }
      //    Bath_room
      if(!empty($specs_defaults['bath_room'])){
        $specs_defaults["bath_room"] = array_keys($specs_defaults['bath_room']);
        array_multisort($specs_defaults['bath_room'], SORT_DESC);
        
        $specs_defaults['bath_room']['min'] = ceil($specs_defaults['bath_room'][count($specs_defaults['bath_room'])-1]);
        $specs_defaults['bath_room']['max'] = ceil($specs_defaults['bath_room'][0]);
        if($specs_defaults['bath_room']['max'] > 5){
          $specs_defaults['bath_room']['max'] = 5;
        }
      }
      
      else{
//    print_r($specs_defaults);
        $specs_defaults['bath_room']['min'] = 0;
        $specs_defaults['bath_room']['max'] = 5;
      }
      
      //Price
      if(!empty($specs_defaults['price'])){
        array_multisort($specs_defaults['price'], SORT_DESC);
//    print_r($specs_defaults);
        $specs_defaults['price']['min'] = ceil($specs_defaults['price'][count($specs_defaults['price'])-1])-1;
        $specs_defaults['price']['max'] = ceil($specs_defaults['price'][0])+1;
      }
      
      else{
//    print_r($specs_defaults);
        $specs_defaults['price']['min'] = 0;
        $specs_defaults['price']['max'] = 0;
      }
      
      if(!empty($specs_defaults['school_distance'])){
        array_multisort($specs_defaults['school_distance'], SORT_DESC);
        $specs_defaults['school_distance']['min'] = ceil($specs_defaults['school_distance'][count($specs_defaults['school_distance'])-1])-1;
        $specs_defaults['school_distance']['max'] = ceil($specs_defaults['school_distance'][0])+1;
      }
      
      else{
        $specs_defaults['school_distance']['min'] = 0;
        $specs_defaults['school_distance']['max'] = 0;
      }
      
      if(!empty($specs_defaults['route_distance'])){
        array_multisort($specs_defaults['route_distance'], SORT_DESC);
        $specs_defaults['route_distance']['min'] = ceil($specs_defaults['route_distance'][count($specs_defaults['route_distance'])-1])-1;
        $specs_defaults['route_distance']['max'] = ceil($specs_defaults['route_distance'][0])+1;
      }
      
      else{
        $specs_defaults['route_distance']['min'] = 0;
        $specs_defaults['route_distance']['max'] = 0;
      }
      
      $defaults['price']['min'] = $min_price+0;
      $defaults['price']['max'] = $max_price+0;
      $defaults['school_distance']['min'] = $min_school_distance+0;
      $defaults['school_distance']['max'] = $max_school_distance+0;
      $defaults['route_distance']['min'] = $min_route_distance+0;
      $defaults['route_distance']['max'] = $max_route_distance+0;

      $defaults['parking'] = $parking_gte+0;
      $defaults['bath_room'] = $bath_room_gte+0;
      $defaults['kitchen'] = $kitchen_gte+0;
      $defaults['bed_room'] = $bed_room_gte+0;
      $defaults['living_room'] = $living_room_gte+0;
      
      $defaults['type'] = $specs_defaults['type'];
      $defaults['furnished'] = $specs_defaults['furnished'];
      
      $specs['type'] = $house_types;
      $specs['furnished'] = $furnished;
      
      $query = $dm->createQueryBuilder("XSMarketPlaceBundle:Product");

//    Gestion du Parking
      if($flag_parking_gte OR !empty($parking_gte)){
//    if(!empty($parking_gte) AND !empty($max_price)){
        $parking_gte += 0;
        
        $flag_parking_gte = true;
        
        $query->addAnd($query->expr()
          ->field('house.parking')
          ->gte($parking_gte)
        );
      }
//    Gestion du Kitchen
      if($flag_kitchen_gte OR !empty($kitchen_gte)){
//    if(!empty($kitchen_gte) AND !empty($max_price)){
        $kitchen_gte += 0;
        
        $flag_kitchen_gte = true;
        
        $query->addAnd($query->expr()
          ->field('house.room.kitchen')
          ->gte($kitchen_gte)
        );
      }
//    Gestion du Bath_room
      if($flag_bath_room_gte OR !empty($bath_room_gte)){
//    if(!empty($bath_room_gte) AND !empty($max_price)){
        $bath_room_gte += 0;
        
        $flag_bath_room_gte = true;
        
        $query->addAnd($query->expr()
          ->field('house.room.toilet')
          ->gte($bath_room_gte)
        );
      }
//    Gestion du Bed_room
      if($flag_bed_room_gte OR !empty($bed_room_gte)){
//    if(!empty($bed_room_gte) AND !empty($max_price)){
        $bed_room_gte += 0;
        
        $flag_bed_room_gte = true;
        
        $query->addAnd($query->expr()
          ->field('house.room.bed_room')
          ->gte($bed_room_gte)
        );
      }
//    Gestion du Living_room
      if($flag_living_room_gte OR !empty($living_room_gte)){
//    if(!empty($living_room_gte) AND !empty($max_price)){
        $living_room_gte += 0;
        
        $flag_living_room_gte = true;
        
        $query->addAnd($query->expr()
          ->field('house.room.living_room')
          ->gte($living_room_gte)
        );
      }

//    Gestion du Prix
      if(!empty($min_price) AND !empty($max_price)){
        $min_price += 0;
        $max_price += 0;

        $flag_price = true;
        
        $query->addAnd($query->expr()
          ->field('pay_month.value')
          ->range($min_price, $max_price)
        );
      }
//    Gestion de la distance de l'école
      if(!empty($min_school_distance) AND !empty($max_school_distance)){
        $min_school_distance += 0;
        $max_school_distance += 1;
        
        $flag_school_distance = true;
        
        $query->addAnd($query->expr()
          ->field('house.distance_school')
          ->range($min_school_distance, $max_school_distance)
        );
      }

//    Gestion de la distance à la route
      if(!empty($min_route_distance) AND !empty($max_route_distance)){
        $min_route_distance += 0;
        $max_route_distance += 1;
        
        $flag_route_distance = true;
        
        $query->addAnd($query->expr()
          ->field('house.distance_to_road')
          ->range($min_route_distance, $max_route_distance)
        );
      }

//    Gestion des types sélectionnés
      if($flag_type OR !empty($house_types)){
//    if(!empty($house_types)){
        $query->addAnd($query->expr()
          ->field('house.type')
          ->in($house_types)
        );
      }
      
      else{
        //      All Selected
        $specs['type'] = array();
        foreach($defaults['type'] as $key => $item){
          $specs['type'][] = $key;
        }
      }

//    Gestion des furnished ? Meu*lé ou non ?
//    if($flag_furnished){
      if($flag_furnished OR !empty($furnished)){
        $local_query = $local_query_1= $local_query_0 = null;
        $all_selected = false;
        if(in_array(1, $furnished)){
          $local_query_1 = $query->expr()
            ->field('house.furnished')
            ->equals(true)
          ;
        }
        if(in_array(0, $furnished)){
          $local_query_0 = $query->expr()
            ->field('house.furnished')
            ->equals(false)
          ;
        }
        if(empty($furnished) OR (!is_null($local_query_0) and !is_null($local_query_1))){
          $all_selected = true;
//        print_r(1);
        }
        else{
          if(!is_null($local_query_0)){
            $local_query = $local_query_0;
          }
          if(!is_null($local_query_1)){
            
            $local_query = $local_query_1;
          }
          $query->addAnd($local_query);
        }
        if(empty($furnished)){
          $local_query = $query->expr()
            ->field('house.furnished')
            ->equals(0)
          ;
          $query->addAnd($local_query);
        }
        elseif(empty($local_query) AND !$all_selected){
//        On exécute un truc tjrs faux :)
          $local_query = $query->expr()
            ->field('house.furnished')
            ->equals(0)
          ;
          $query->addAnd($local_query);
        }
      }
      
      else{
        //      All Selected
        $specs['furnished'] = array();
        foreach($defaults['furnished'] as $key => $item){
          $specs['furnished'][] = $key;
        }
      }
      
      $products = $query->getQuery()->execute();
      
      foreach($products as $product){
        $specs['price'][] = $product->getPayMonth()->getValue();
        $specs['school_distance'][] = $product->getHouse()->getDistanceSchool();
        $specs['route_distance'][] = $product->getHouse()->getDistanceToRoad();
      }
//    $specs['price'][2] = 9000;

//    Final Prices
//    print_r($specs);
      if(!empty($specs['price'])){
        array_multisort($specs['price'], SORT_DESC);
        array_multisort($specs['school_distance'], SORT_DESC);
        array_multisort($specs['route_distance'], SORT_DESC);
//    print_r($specs);
        $specs['price']['min'] = ceil($specs['price'][count($specs['price'])-1])-1;
        $specs['price']['max'] = ceil($specs['price'][0])+1;
        if(!$flag_price){
          $defaults['price']['min'] = $specs['price']['min'];
          $defaults['price']['max'] = $specs['price']['max'];
        }

//      School distance :) !!!
        $specs['school_distance']['min'] = ceil($specs['school_distance'][count($specs['school_distance'])-1])-1;
        $specs['school_distance']['max'] = ceil($specs['school_distance'][0])+1;
        if(!$flag_school_distance){
          $defaults['school_distance']['min'] = $specs['school_distance']['min'];
          $defaults['school_distance']['max'] = $specs['school_distance']['max'];
        }
        
        //      Route distance :) !!!
        $specs['route_distance']['min'] = ceil($specs['route_distance'][count($specs['route_distance'])-1])-1;
        $specs['route_distance']['max'] = ceil($specs['route_distance'][0])+1;
        if(!$flag_route_distance){
          $defaults['route_distance']['min'] = $specs['route_distance']['min'];
          $defaults['route_distance']['max'] = $specs['route_distance']['max'];
        }
        
      }
      
      else{
        $specs['price'] = $specs_defaults['price'];
        $specs['parking'] = $specs_defaults['parking'];
        $specs['school_distance'] = $specs_defaults['school_distance'];
        $specs['route_distance'] = $specs_defaults['route_distance'];
      }
      
      $user = $this->getUser();
      /* $user->getMarket()->setCart(new Cart());
       $user->getMarket()->setWishList(new Cart());
       $user->getMarket()->setCompareList(new Cart());
       $user->getMarket()->setOrders(new Cart());
      
       $dm->persist($user);
       $dm->flush();*/

//   Default for numerics :)
      $specs['parking'] = $specs_defaults['parking'];
      $specs['kitchen'] = $specs_defaults['kitchen'];
      $specs['living_room'] = $specs_defaults['living_room'];
      $specs['bath_room'] = $specs_defaults['bath_room'];
      $specs['bed_room'] = $specs_defaults['bed_room'];
//   print_r($specs);
    }
    
    return $this->render('XSMarketPlaceBundle:Default:index.html.twig', array(
      'products' => $products,
      'view' => $view,
      'specs' => $specs,
      'defaults' => $defaults,
    ));
  }
    
    public function addProductToListAction(Request $request, $id, $list){
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $product = $dm->getRepository('XSMarketPlaceBundle:Product')->findOneBy(array(
      'id' => $id
    ));
    
    $session = $request->getSession();
    $message_type = 'error';
    $message_content = "Le produit sélectionné n'existe plus ou a été supprimé!";
    $user_list = null;
    $product_cart = null;
    
    if(isset($product)){
      $user = $this->getUser();
      
      switch($list){
        case 'compare_list':
          $user_list = $user->getMarket()->getCompareList();
          if(!isset($user_list)){
            $user_list = $session->get($list);
            
            if(empty($user_list)){
              $user_list = new Cart();
            }
          }
          
          if($user_list->getCountProducts() >= 3){
            $message_type = 'error';
            $message_content = 'Vous ne pouvez comparer plus de 3 produits à la fois!';
          }
          else{
            $product_cart = $user_list->addProduct($product);
            if(isset($product_cart)){
//              product_cart is set, donc on peut ajouter le produit...
              if(isset($user)){
                $user->getMarket()->setCompareList($user_list);
                $product_cart->setUser($user);
                if(empty($product->getCompareList())){
                  $product->setCompareList(new Cart());
                }
                $product->getCompareList()->addProductCart($product_cart);
                
                $dm->persist($product);
                $dm->persist($user);
                $dm->flush();
                $session->set($list, $user_list);
                $message_type = 'notice';
                $message_content = "Produit ajouté à la liste de comparaison!";
              }
            }
            else{
              $message_type = 'error';
              $message_content = "Ce produit est déja dans votre liste de comparaison!";
            }
          }
          break;
        
        case 'cart':
          $user_list = $user->getMarket()->getCart();
          if(!isset($user_list)){
            $user_list = $session->get($list);
            
            if(empty($user_list)){
              $user_list = new Cart();
            }
          }
          
          $product_cart = $user_list->addProduct($product);
          if(isset($product_cart)){
//              product_cart is set, donc on peut ajouter le produit...
            if(isset($user)){
              $user->getMarket()->setCart($user_list);
              $product_cart->setUser($user);
              if(empty($product->getCart())){
                $product->setCart(new Cart());
              }
              $product->getCart()->addProductCart($product_cart);
              
              $dm->persist($product);
              $dm->persist($user);
              $dm->flush();
              $session->set($list, $user_list);
              $message_type = 'notice';
              $message_content = "Produit ajouté à mon Panier!";
            }
          }
          else{
            $message_type = 'error';
            $message_content = "Ce produit est déja dans votre Panier!";
          }
          
          break;
        
        case 'wish_list':
          $user_list = $user->getMarket()->getWishList();
          if(!isset($user_list)){
            $user_list = $session->get($list);
            
            if(empty($user_list)){
              $user_list = new Cart();
            }
          }
          
          $product_cart = $user_list->addProduct($product);
          if(isset($product_cart)){
//              product_cart is set, donc on peut ajouter le produit...
            if(isset($user)){
              $user->getMarket()->setWishList($user_list);
              $product_cart->setUser($user);
              if(empty($product->getWishList())){
                $product->setWishList(new Cart());
              }
              $product->getWishList()->addProductCart($product_cart);
              
              $dm->persist($product);
              $dm->persist($user);
              $dm->flush();
              $session->set($list, $user_list);
              $message_type = 'notice';
              $message_content = "Produit ajouté à la liste de voeux!";
            }
          }
          else{
            $message_type = 'error';
            $message_content = "Ce produit est déja dans votre liste de voeux!";
          }
          
          break;
      }
    }
    
    
    if($request->isXmlHttpRequest()){
      $response = new JsonResponse();
      $response->setData(array(
        'type' => $message_type,
        'content' => $message_content
      ));
      
      return $response;
//      return new Response($message_content);
    }
    else{
      $this->addFlash($message_type, $message_content);
      try{
        return $this->redirectToRoute('xs_market_place_'.$list);
      }catch(\Exception $exception){
        return $this->redirectToRoute('xs_market_place_homepage');
      }
      /*
       switch($list){
         case 'compare_list':
           return $this->redirectToRoute('xs_market_place_compare_list');
           break;
         
         case 'cart':
           return $this->redirectToRoute('xs_market_place_cart');
           break;
           
         case 'wish_list':
           return $this->redirectToRoute('xs_market_place_wish_list');
           break;
           
         default:
           return $this->redirectToRoute('xs_market_place_homepage');
           break;
       }*/
    }
  }
    
    public function removeProductFromListAction(Request $request, $id, $list){
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $product = $dm->getRepository('XSMarketPlaceBundle:Product')->findOneBy(array(
      'id' => $id
    ));
    
    $session = $request->getSession();
    $message_type = 'error';
    $message_content = "Le produit sélectionné n'existe plus ou a été supprimé!";
    $user_list = null;
    $product_cart = null;
    
    if(isset($product)){
      $user = $this->getUser();
      
      switch($list){
        case 'compare_list':
          $user_list = $user->getMarket()->getCompareList();
          if(!isset($user_list)){
            $user_list = $session->get($list);
            
            if(empty($user_list)){
              $user_list = new Cart();
            }
          }
          
          $product_cart = $user_list->removeProduct($product);
          if(($product_cart)){
//              product_cart, donc le produit était dans la liste...
            if(isset($user)){
              $user->getMarket()->setCompareList($user_list);
              if(empty($product->getCompareList())){
                $product->setCompareList(new Cart());
              }
              $product->getCompareList()->removeProduct($product, $user);
              
              $dm->persist($product);
              $dm->persist($user);
              $dm->flush();
              $session->set($list, $user_list);
              $message_type = 'notice';
              $message_content = "Produit retiré de la liste de comparaison!";
            }
          }
          else{
            $message_type = 'error';
            $message_content = "Ce produit n'est pas dans votre liste de comparaison!";
          }
          
          break;
        
        case 'cart':
          $user_list = $user->getMarket()->getCart();
          if(!isset($user_list)){
            $user_list = $session->get($list);
            
            if(empty($user_list)){
              $user_list = new Cart();
            }
          }
          
          $product_cart = $user_list->removeProduct($product);
          if(($product_cart)){
//              product_cart is set, donc on peut ajouter le produit...
            if(isset($user)){
              $user->getMarket()->setCart($user_list);
              if(empty($product->getCart())){
                $product->setCart(new Cart());
              }
              $product->getCart()->removeProduct($product, $user);
              
              $dm->persist($product);
              $dm->persist($user);
              $dm->flush();
              $session->set($list, $user_list);
              $message_type = 'notice';
              $message_content = "Produit retiré de mon Panier!";
            }
          }
          else{
            $message_type = 'error';
            $message_content = "Ce produit n'est pas dans votre Panier!";
          }
          
          break;
        
        case 'wish_list':
          $user_list = $user->getMarket()->getWishList();
          if(!isset($user_list)){
            $user_list = $session->get($list);
            
            if(empty($user_list)){
              $user_list = new Cart();
            }
          }
          
          $product_cart = $user_list->removeProduct($product);
          if(($product_cart)){
//              product_cart is set, donc on peut ajouter le produit...
            if(isset($user)){
              $user->getMarket()->setWishList($user_list);
              if(empty($product->getWishList())){
                $product->setWishList(new Cart());
              }
              $product->getWishList()->removeProduct($product, $user);
              
              $dm->persist($product);
              $dm->persist($user);
              $dm->flush();
              $session->set($list, $user_list);
              $message_type = 'notice';
              $message_content = "Produit retiré de la liste de voeux!";
            }
          }
          else{
            $message_type = 'error';
            $message_content = "Ce produit n'est pas dans votre liste de voeux!";
          }
          
          break;
      }
    }
    
    
    if($request->isXmlHttpRequest()){
      $response = new JsonResponse();
      $response->setData(array(
        'type' => $message_type,
        'content' => $message_content
      ));
      
      return $response;
//      return new Response($message_content);
    }
    else{
      $this->addFlash($message_type, $message_content);
      try{
        return $this->redirectToRoute('xs_market_place_'.$list);
      }catch(\Exception $exception){
        return $this->redirectToRoute('xs_market_place_homepage');
      }
    }
  }
    
    public function menuStoreAction($page){
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $namespace = $this->getParameter('app_store_namespace');
    $store = $dm->getRepository('XSEducationBundle:School')->findOneByNamespace($namespace);
//    $products = $store->getProducts();
    
    /*
            $base_query = $dm->createQueryBuilder('MainBundle:Product');
            $qb = $base_query
                ->field('store')->referees($store)
               /* ->field('sub_category')
                ->field('name')
                ->group(
                    array(
    //                    'sub_category' => null,
                        'category' => null,
                    ),
                    array(
                        'stotal' => 0,
                    )
                )
    //            ->sort('sub_category', 'asc')
                ->sort('category', 'asc')
                ->reduce('function(k, vals){
                        vals.total++;
                }')
            ;

            $query = $qb->getQuery();
            $groups = $query->execute()->toArray();


            var_dump($groups);
            $result = $groups;
               */



//The menu of admin
    return $this->render('::menu_store.html.twig', array(
      'store' => $store,
      'page' => $page,
    ));
  }
    
    public function purge_array(&$array){
    $tmp = array();
    foreach($array as $item){
      if($item != null){
        $tmp[] = $item;
      }
    }
    $array = $tmp;
  }
  }
