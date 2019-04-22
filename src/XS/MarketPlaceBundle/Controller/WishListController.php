<?php

namespace XS\MarketPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\MarketPlaceBundle\Document\Cart;

class WishListController extends Controller
{
  public function indexAction(Request $request)
  {
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $user = $this->getUser();
    $session = $request->getSession();
    $wish_list = $user->getMarket()->getWishList();
    if(!isset($wish_list)){
      $wish_list = $session->get('wish_list');
      $user->getMarket()->setWishList($wish_list);
      $dm->persist($user);
      $dm->flush();
    }
    
    return $this->render('XSMarketPlaceBundle:WishList:index.html.twig', array(
      'cart' => $wish_list
    ));
  }
  
  public function addProductAction(Request $request, $id){
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $product = $dm->getRepository('XSMarketPlaceBundle:Product')->findOneBy(array(
      'id' => $id
    ));
    
    $session = $request->getSession();
    $message_type = 'error';
    $message_content = "Le produit sélectionné n'existe plus ou a été supprimé!";
    
    if(isset($product)){
      $user = $this->getUser();
      $wish_list = $user->getMarket()->getWishList();
      if(!isset($wish_list)){
        $wish_list = $session->get('wish_list');
        
        if(empty($wish_list)){
          $wish_list = new Cart();
        }
      }
      
      if($wish_list->getCountProducts() >= 3){
        $message_type = 'error';
        $message_content = 'Vous ne pouvez wishr plus de 3 produits à la fois!';
      }
      else{
        $wish_list->addProduct($product);
        if(isset($user)){
          if(empty($user->getMarket()->getWishList())){
            $user->getMarket()->setWishList($wish_list);
          }
          $dm->persist($user);
          $dm->flush();
          $session->set('wish_list', $wish_list);
        }
      }
    }
    if($request->isXmlHttpRequest()){
      return new Response($message_content);
    }
    else{
      $this->addFlash($message_type, $message_content);
      return $this->redirectToRoute('xs_market_place_wish_list');
      return new Response(555);
    }
  }
  
  public function removeProductAction(Request $request, $id){
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $product = $dm->getRepository('XSMarketPlaceBundle:Product')->findOneBy(array(
      'id' => $id
    ));
    
    $session = $request->getSession();
    $message_type = 'error';
    $message_content = "Le produit sélectionné n'existe plus ou a été supprimé!";
    
    if(isset($product)){
      $user = $this->getUser();
      $wish_list = $user->getMarket()->getWishList();
      if(!isset($wish_list)){
        $wish_list = $session->get('wish_list');
        
        if(empty($wish_list)){
          $wish_list = new Cart();
        }
      }
      
      $wish_list->removeProduct($product);
      if(isset($user)){
        if(empty($user->getMarket()->getWishList())){
          $user->getMarket()->setWishList($wish_list);
        }
        $dm->persist($user);
        $dm->flush();
        $session->set('wish_list', $wish_list);
        $message_type = 'notice';
        $message_content = 'Produit retiré de la liste de comparaison';
      }
    }
    if($request->isXmlHttpRequest()){
      return new Response($message_content);
    }
    else{
      $this->addFlash($message_type, $message_content);
      return $this->redirectToRoute('xs_market_place_wish_list');
      return new Response(555);
    }
  }
  
  public function _allAction(Request $request){
//    Wish tous les produits et retourne le ta*le
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $user = $this->getUser();
    $session = $request->getSession();
    $wish_list = $user->getMarket()->getWishList();
    if(!isset($wish_list)){
      $wish_list = $session->get('wish_list');
      $user->getMarket()->setWishList($wish_list);
      $dm->persist($user);
      $dm->flush();
    }
    
    return $this->render('XSMarketPlaceBundle:WishList:_all.html.twig', array(
      'cart' => $wish_list
    ));
  }
}
