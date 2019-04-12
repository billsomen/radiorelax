<?php

namespace XS\MarketPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\MarketPlaceBundle\Document\Cart;

class CompareListController extends Controller
{
  public function indexAction(Request $request)
  {
    $user = $this->getUser();
    $session = $request->getSession();
    $compare_list = $user->getMarket()->getCompareList();
    $output_list = array();
    if(!isset($compare_list)){
      $compare_list = $session->get('compare_list');
    }
    
    return $this->render('XSMarketPlaceBundle:CompareList:index.html.twig', array(
      'cart' => $compare_list
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
      $compare_list = $user->getMarket()->getCompareList();
      if(!isset($compare_list)){
        $compare_list = $session->get('compare_list');
        
        if(empty($compare_list)){
          $compare_list = new Cart();
        }
      }
      
      if($compare_list->getCountProducts() >= 3){
        $message_type = 'error';
        $message_content = 'Vous ne pouvez comparer plus de 3 produits à la fois!';
      }
      else{
        $compare_list->addProduct($product);
        if(isset($user)){
          if(empty($user->getMarket()->getCompareList())){
            $user->getMarket()->setCompareList($compare_list);
          }
          $dm->persist($user);
          $dm->flush();
          $session->set('compare_list', $compare_list);
        }
      }
    }
    if($request->isXmlHttpRequest()){
      return new Response($message_content);
    }
    else{
      $this->addFlash($message_type, $message_content);
      return $this->redirectToRoute('xs_market_place_compare_list');
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
      $compare_list = $user->getMarket()->getCompareList();
      if(!isset($compare_list)){
        $compare_list = $session->get('compare_list');
        
        if(empty($compare_list)){
          $compare_list = new Cart();
        }
      }
      
      $compare_list->removeProduct($product);
      if(isset($user)){
        if(empty($user->getMarket()->getCompareList())){
          $user->getMarket()->setCompareList($compare_list);
        }
        $dm->persist($user);
        $dm->flush();
        $session->set('compare_list', $compare_list);
        $message_type = 'notice';
        $message_content = 'Produit retiré de la liste de comparaison';
      }
    }
    if($request->isXmlHttpRequest()){
      return new Response($message_content);
    }
    else{
      $this->addFlash($message_type, $message_content);
      return $this->redirectToRoute('xs_market_place_compare_list');
      return new Response(555);
    }
  }
  
  public function _allAction(Request $request){
//    Compare tous les produits et retourne le ta*le
    $dm = $this->get('doctrine_mongodb.odm.document_manager');
    $user = $this->getUser();
    $session = $request->getSession();
    $compare_list = $user->getMarket()->getCompareList();
    $output_list = array();
    if(!isset($compare_list)){
      $compare_list = $session->get('compare_list');
    }
    
    return $this->render('XSMarketPlaceBundle:CompareList:_all.html.twig', array(
      'cart' => $compare_list
    ));
    /*if(isset($compare_list)){
      foreach($compare_list->getProductsCart() as $product_cart){
  
        $data[] = array(
          'card' => $report_card,
          'coef' => $coef,
          'moy' => $moy
        );
        $sum_section += $moy;
        array_multisort($data, SORT_DESC);
        
        
        
        
      }
    }*/
    
  }
}
