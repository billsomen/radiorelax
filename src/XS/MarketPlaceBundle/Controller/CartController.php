<?php

namespace XS\MarketPlaceBundle\Controller;

use AppBundle\Service\Locale;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\MarketPlaceBundle\Document\Buyer;
use XS\MarketPlaceBundle\Document\Cart;
use XS\MarketPlaceBundle\Document\ProductCart;

class CartController extends Controller
{
  public function indexAction(Request $request)
  {
    $session = $request->getSession();
    $cart = $session->get('cart');
    return $this->render('@XSMarketPlace/Cart/index.html.twig', array(
      'cart' => $cart
    ));
  }
  public function addProduct_2Action(Request $request, $id){
    $dm = $this->get('doctrine_mongodb')->getManager();
//       Quand on ajoute un produit, on n'a pas encore acces a sa quantite...
    $product = $dm->getRepository('MainBundle:Product')->findOneById($id);
    $session = $request->getSession();
//        $cart = $session->get('cart');
    $cart = new Cart();
    $productCart = new ProductCart();
    $productCart->setProductId($id);
    $productCart->setProfileUrl($product->getProfileUrl());
    $productCart->setName($product->getName());
    $productCart->setAmount($product->getPriceShown());
    $productCart->setProduct($product);
    $cart->addProductCart($productCart);
//        var_dump($cart);
    $session->set('cart', $cart);
    $cartS = $session->get('cart');
    $response = new Response('Hello '.$id, Response::HTTP_OK);
    
    return $response;
//        return $this->redirectToRoute('main_cart');
  }
  
  public function addSessionAction(Request $request, $id, $tutor_id){
    $dm = $this->get('doctrine_mongodb')->getManager();
    $tutor = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
      'id' => $tutor_id
    ));
    $message = "Erreur inconnue, merci de recommencer!";
    $type = "error";
    
    $cart = null;
    $session = $request->getSession();
    $user = $this->getUser();
    if(isset($user)){
      $cart = $user->getMarket()->getCart();
    }
    else{
      $cart = $session->get('cart');
      if(!isset($cart)){
        $cart = new Cart();
      }
    }
    
    foreach($tutor->getCalendar()->getEntries() as $entry){
      if($entry->getId() == $id){
        $result = $cart->addSession($tutor, $entry);
        if($result){
//          We update the Cart
          if(isset($user)){
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($user);
            $dm->flush();
          }
          $session->set('cart', $cart);
          $type = "notice";
          $message = "Session ajoutée à mon panier";
        }
        else{
          $message = "Cette session existe déjà dans mon panier :)";
        }
        break;
      }
    }
    
    if($request->isXmlHttpRequest()){
      $response = new JsonResponse();
      $response->setData(array(
        'message' => $message,
        'type' => $type
      ));
      
      return $response;
    }
    else{
      $this->addFlash($type, $message);
      return new Response($message);
    }
  }
  
  public function addAlbumAction($id){
    $dm = $this->get('doctrine_mongodb')->getManager();
//       Quand on ajoute un produit, on n'a pas encore acces a sa quantite...
    $album = $dm->getRepository('MainBundle:Album')->findOneBy(array(
      'id' => $id
    ));
    $in_cart = false;
    $user = $this->getUser();
    if(isset($product) and !empty($user)){
//          ON s'assure que l'user est connecté ou pas
      $cart = $user->getCart();
      $productCart = new ProductCart();
      $productCart->setProduct($product);
      $productCart->setAmount($product->getPriceShown());
      $productCart->setName($product->getName());
      $productCart->setProfileUrl($product->getProfileUrl());
//            On cree le flag in_cart, renseignant sur la presence du produit dans le cart
      if(!isset($cart)){
        $cart = new Cart();
//                On ajoute directement...
        $cart->addProductCart($productCart);
      }
//            On s'assure desormais de l'unicite d'un produit dans le panier...
      else{
        foreach($cart->getProductsCarts() as $product_cart){
          if($product_cart->getProduct()->getId() == $id){
            $in_cart = true;
            break;
          }
        }
        if(!$in_cart) {
          $cart->addProductCart($productCart);
          print_r(555555555555555);
          $session->set('cart', $cart);
        }
      }
      
      $user = $this->getUser();
      if(isset($user)){
        $user->getMarket()->setCart($cart);
//        $user
        $dm->persist($user);
        $dm->flush();
      }
      
      //                Avant de continuer, on renvoit des donnees s'il s'agit d'une requete xmlhttp...
      
      if($request->isXmlHttpRequest()){
//                On up*ate juste le mo*ule général
//        return new Response('Fuck');
        return $this->container->get('templating')->renderResponse('XSMarketPlaceBundle:Cart:cart-module.html.twig');
      }
      /* if($request->isXmlHttpRequest())
       {
           return $this->container->get('templating')->renderResponse('MainBundle:Cart:addProduct.html.twig', array(
                   'id' => $id)
           );
       }*/
    }
    else{
      $this->addFlash('error', 'Sorry, the product does not exist');
    }
//    return new Response('Fuck');
    return $this->redirectToRoute('xs_market_place_cart');
  }
  
  
  public function loadTableXmlhttpAction(Request $request){
    $session = $request->getSession();
    $cart = $session->get('cart');
//            On cree le flag in_cart, renseignant sur la presence du produit dans le cart
    //                On up*ate juste le mo*ule général
    return $this->container->get('templating')->renderResponse('XSMarketPlaceBundle:Cart:cart-table.html.twig', array(
      'cart' => $cart
    ));
  }
  
  public function removeSessionAction(Request $request, $tutor_id, $id){
    $cart = null;
    $session = $request->getSession();
    $user = $this->getUser();
    if(isset($user)){
      $cart = $user->getMarket()->getCart();
    }
    else{
      $cart = $session->get('cart');
      if(!isset($cart)){
        $cart = new Cart();
        $session->set('cart', $cart);
      }
    }
    
    $res = $cart->removeSession($tutor_id, $id);
    if($res){
      if(isset($user)){
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($user);
        $dm->flush();
      }
      $session->set('cart', $cart);
    }
    
    if(!isset($user)){
      $user = new \XS\UserBundle\Document\User();
      $user->setNickname("Anonyme");
    }
    $locale_action = new Locale();
    $locale = $locale_action->defaultLocale($user);
    
    return $this->container->get('templating')->renderResponse('@XSEducation/User/cart-module.html.twig', array(
      'cart' => $cart,
      'locale' => $locale,
      'user' => $user
    ));
  }
  public function removeProductAction(Request $request, $id){
    $dm = $this->get('doctrine_mongodb')->getManager();
//       Quand on ajoute un produit, on n'a pas encore acces a sa quantite...
    $product = $dm->getRepository('XSMarketPlaceBundle:Product')->findOneById($id);
    $in_cart = false;
    if(isset($product)){
//          ON s'assure que l'user est connecté ou pas
      
      $session = $request->getSession();
      $cart = $session->get('cart');
//            On cree le flag in_cart, renseignant sur la presence du produit dans le cart
      if(!isset($cart)){
        $cart = new Cart();
//                On ajoute directement...
        $this->addFlash('error', 'Sorry, your cart is empty');
      }
//            On s'assure desormais de l'unicite d'un produit dans le panier...
      else{
        $productCart = null;
        foreach($cart->getProductsCarts() as $product_cart){
          if($product_cart->getProduct()->getId() == $id){
            $cart->removeProductCart($product_cart);
            $session->set('cart', $cart);
            $user = $this->getUser();
            if(isset($user)){
              $user->getMarket()->setCart($cart);
              $dm->persist($user);
              $dm->flush();
            }
            break;
          }
        }
      }
//                Avant de continuer, on renvoit des donnees s'il s'agit d'une requete xmlhttp...
      if($request->isXmlHttpRequest())
      {
        return $this->container->get('templating')->renderResponse('XSMarketPlaceBundle:Cart:cart-module.html.twig');
      }
    }
    else{
      $this->addFlash('error', 'Sorry, the product does not exist');
    }
    
    return $this->redirectToRoute('xs_market_place_cart');
  }
  
  public function buyProductAction(Request $request, $id){
    return $this->redirectToRoute('homepage');
    $dm = $this->get('doctrine_mongodb')->getManager();
//       Achat d'un produit...
    $product = $dm->getRepository('MainBundle:Product')->findOneById($id);
    if(isset($product)){
      if($request->isMethod('POST')){
        if(isset($_POST['quantity'][$id])){
          $quantity = $_POST['quantity'][$id];
          if($quantity <= $product->getQuantity()){
            $user = $this->getUser();
            $cart = $user->getCart();
            if($cart != null){
//                            On recherche le produit, et on MAJ la quantite...
              foreach($cart->getProductsCarts() as $productCart){
                if($productCart->getProduct()->getId() == $id){
                  $productCart->setQuantity($quantity);
                  $product->setQuantity($product->getQuantity() - $quantity);
                  $productCart->isBought();
//                                    On ajoute le client a la liste des acheteurs du produit..
                  $buyer = new Buyer();
                  $buyer->setUser($user);
                  $product->addBuyer($buyer);
//                                    Tout est bon... :)... Il ne manque plus que les notifications =P
//                                    On persiste l'utilisateur actuel et le produit...
                  $dm->persist($product);
                  $dm->persist($user);
                  $dm->flush();
                  break;
                }
              }
//                            MAJ de la quantite dans le CART...
            }
            
          }
        }
      }

//            On redirige ensuite au panier...
      return $this->redirectToRoute('xs_market_place_cart');
    }
    return $this->redirectToRoute('xs_market_place_homepage');
  }

//    Le reste est gere dans la navigation...
}
