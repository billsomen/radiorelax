<?php

namespace XS\MarketPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\MarketPlaceBundle\Document\Product;
use XS\MarketPlaceBundle\Document\Room;
use XS\MarketPlaceBundle\Form\ProductType;

class ProductController extends Controller
{
  public function indexAction($name)
  {
    return $this->render('', array('name' => $name));
  }
  
  public function _allAction(Request $request){
//       ::this is for Ajax $requests
    $views = array("grid", "list");
    $view = $request->query->get('view');
    if(!in_array($view, $views)){
      $view = $views[0];
    }
    $dm = $this->get('doctrine_mongodb')->getManager();
    $products = $dm->getRepository('XSMarketPlaceBundle:Product')->findAll();
    
    $cat = $request->query->get('cat');
    $sub = $request->query->get('sub');
    $path = 'xs_market_place_products_all';

//
    return $this->container->get('templating')->renderResponse('XSMarketPlaceBundle:Product:_all.html.twig', array(
      'view' => $view,
      'path' => $path,
      'cat' => $cat,
      'products' => $products,
    ));
    
    /* return $this->render('MainBundle:Store:_products.all.html.twig', array(
         'view' => $view,
         'cat' => $cat,
         'products' => $products,
     ));*/
  }
  
  public function showAction(Request $request, $id){
//        $request
    $dm = $this->get('doctrine_mongodb')->getManager();
    $product = $dm->getRepository('XSMarketPlaceBundle:Product')->findOneById($id);
    if(!isset($product)){
      return $this->redirectToRoute('xs_market_place_products_all');
    }
    $store = $product->getStore();
    
    
    $cat = $request->query->get('cat');
    $sub = $request->query->get('sub');
    return $this->render('@XSMarketPlace/Product/show.html.twig', array(
      'product' => $product,
      'store' => $store,
    ));
  }
  
  public function _newAction(Request $request){
    //Ajout d'une boutique a l'utilisateur courant...
    $dm = $this->get('doctrine_mongodb')->getManager();
    $product = new Product();
    
    $user =  $this->getUser();
    $store = $user->getMarket()->getStore();
    if(isset($store)){
      $schools = $dm->getRepository('XSEducationBundle:School')->findAll();
      
      $form = $this->createForm(ProductType::class, $product);
      
      if($request->isMethod('POST')){
        $form->handleRequest($request);
        if($form->isValid()){
          $school_proxy = $_POST['school_proxy'];
          if($school_proxy != 'none'){
            $school_proxy = $dm->getRepository('XSEducationBundle:School')->findOneBy(array(
              'id' => $school_proxy
            ));
            if(isset($school_proxy)){
              $product->getHouse()->setSchool($school_proxy);
            }
          }
          else{
            $product->getHouse()->setDistanceSchool(0);
            $product->getHouse()->setSchool(null);
          }
          if($product->getState() == 'new'){
            $product->setStateDuration(0);
          }
//        Gestion des photos

//          Finalisations
          $store->getProducts()->add($product);
          $product->setStore($store);

          $dm->persist($store);
          $dm->persist($product);
          $dm->flush();
          
          $this->addFlash('notice', "Produit enregistré avec succès!");
          
          return $this->redirectToRoute('xs_manager_market_stores', array(
            'tab' => 'products'
          ));
        }
      }
      
      return $this->render('@XSMarketPlace/Product/_new.html.twig', array(
        'form' => $form->createView(),
        'schools' => $schools,
        'user' => $user
      ));
    }
    else{
      $this->addFlash('error', "Merci de créer/sélectionner une Boutique au préalable");
      return $this->redirectToRoute('xs_manager_market_stores');
    }
  }
  
  public function _editAction(Request $request, $id){
    //Ajout d'une boutique a l'utilisateur courant...
    $dm = $this->get('doctrine_mongodb')->getManager();
    $product = $dm->getRepository('XSMarketPlaceBundle:Product')->findOneBy(array(
      'id' => $id
    ));
    
    $user = $this->getUser();
    $store = $user->getMarket()->getStore();
    if(isset($store) and isset($product)){
      $schools = $dm->getRepository('XSEducationBundle:School')->findAll();
      
      $form = $this->createForm(ProductType::class, $product);
      $price_old = $product->getPriceShown();
      if($request->isMethod('POST')){
        $form->handleRequest($request);
        if($form->isValid()){
          $school_proxy = $_POST['school_proxy'];
          if($school_proxy != 'none'){
            $school_proxy = $dm->getRepository('XSEducationBundle:School')->findOneBy(array(
              'id' => $school_proxy
            ));
            if(isset($school_proxy)){
              $product->getHouse()->setSchool($school_proxy);
            }
          }
          else{
            $product->getHouse()->setDistanceSchool(0);
            $product->getHouse()->setSchool(null);
          }
          if($product->getState() == 'new'){
            $product->setStateDuration(0);
          }
//        Gestion des photos

//          Finalisations
//          $store->getProducts()->add($product);
//          $product->setStore($store);

//          $dm->persist($store);
//          print_r($product->getPayMonth().'-');
//          print_r($product->getPriceShown().'-');
          if(!$price_old->isEqual($product->getPriceShown())){
            $product->setPriceOld($price_old);
          }
          $product->updatePrices();
          $dm->persist($product);
//          print_r($product->getPriceShown().'-');
//          print_r($product->getPayMonth().'-');
          $dm->flush();
          
          $this->addFlash('notice', "Produit enregistré avec succès!");
          return new Response(44);
          
          return $this->redirectToRoute('xs_manager_market_stores', array(
            'tab' => 'products',
            'action' => 'all'
          ));
        }
      }
      
      return $this->render('@XSMarketPlace/Product/_edit.html.twig', array(
        'form' => $form->createView(),
        'schools' => $schools,
        'product' => $product,
        'user' => $user
      ));
    }
    else{
      $this->addFlash('error', "Merci de créer/sélectionner une Boutique au préalable");
      return $this->redirectToRoute('xs_manager_market_stores');
    }
  }
  
  
}
