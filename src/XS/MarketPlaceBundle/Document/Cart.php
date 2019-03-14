<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 10/19/2015
 * Time: 11:11 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\AfrobankBundle\Document\Amount;

/**
 *
 * Class Cart
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 *
 */

class Cart
{
//    Le panier de l'utilisateur.
//  Chaque utilisateur en a un...
  /** @MongoDB\EmbedMany(targetDocument="ProductCart") */
  protected $products_carts;

  /** @MongoDB\Field(type="date") */
  protected $date_add;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
  protected $amount;
  
  /** @MongoDB\Field(type="float") */
  protected $quantity;

//  The cart is lokced when there's a pending payment
  /** @MongoDB\Field(type="boolean") */
  protected $locked;
  
  /**
   * Cart constructor.
   */
  public function __construct() {
    $this->products_carts = new ArrayCollection();
    $this->date_add = new \DateTime();
    $this->amount = new Amount();
    $this->quantity = 0;
    $this->locked = false;
  }
  
  /**
   * @return mixed
   */
  public function getProductsCarts() {
    return $this->products_carts;
  }
  
  /**
   * @param mixed $products_carts
   */
  public function setProductsCarts($products_carts) {
    $this->products_carts = $products_carts;
  }
  
  /**
   * @return mixed
   */
  public function getDateAdd() {
    return $this->date_add;
  }
  
  /**
   * @param mixed $date_add
   */
  public function setDateAdd($date_add) {
    $this->date_add = $date_add;
  }
  
  /**
   * @return mixed
   */
  public function getAmount() {
    return $this->amount;
  }
  
  /**
   * @param mixed $amount
   */
  public function setAmount($amount) {
    $this->amount = $amount;
  }
  
  /**
   * @return mixed
   */
  public function getQuantity() {
    return $this->quantity;
  }
  
  /**
   * @param mixed $quantity
   */
  public function setQuantity($quantity) {
    $this->quantity = $quantity;
  }
  
  public function addProductCart($productCart){
//        Ajout d'un produit au panier et MAJ de la valeurt totale du panier...
    $this->products_carts->add($productCart);
    $this->quantity += $productCart->getQuantity();
    $this->amount->addNumber($productCart->getProduct()->getPriceShown()->multNumber($productCart->getQuantity()), true);
//        Et c'est bon...
  }
  public function addProduct($product){
//        Ajout d'un produit au panier et MAJ de la valeurt totale du panier...
    if(!$this->isProductInCart($product)){
//      On ajoute simplement le produit
      $productCart = new ProductCart();
      $productCart->setProduct($product);
      $this->products_carts->add($productCart);
      $this->quantity += $productCart->getQuantity();
      $this->amount->addNumber($product->getPriceShown()->multNumber($productCart->getQuantity()), true);
//      $this->amount += ($productCart->getQuantity()*$product->getPriceShown());
      return $productCart;
    }
    
    return null;
  }
  
  public function removeProduct($product, $user=null){
//       Retrait d'un produit du panier et MAJ de la valeur totale du panier...
    if($this->isProductInCart($product)){
//      On retire simplement le produit
      if(is_null($user)){
        foreach($this->products_carts as $products_cart){
          if($products_cart->getProductId() == $product->getId()){
            $this->products_carts->removeElement($products_cart);
            $this->quantity -= $products_cart->getQuantity();
            $this->amount->addNumber(-1*$product->getPriceShown()->multNumber($products_cart->getQuantity()), true);
//            $this->amount -= ($products_cart->getQuantity()*$product->getPriceShown());
            return true;
            break;
          }
        }
      }
      else{
        foreach($this->products_carts as $products_cart){
          if($products_cart->getProductId() == $product->getId() AND $products_cart->getUserId() == $user->getId()){
            $this->products_carts->removeElement($products_cart);
            $this->quantity -= $products_cart->getQuantity();
            $this->amount->addNumber(-1*$product->getPriceShown()->multNumber($products_cart->getQuantity()), true);
            return true;
            break;
          }
        }
      }
    }
    
    return false;
  }
  
  public function getCountProducts(){
//    Get the total quantity of product
    return count($this->products_carts);
  }
  
  public function isProductInCart($product){
    $found = false;
    foreach($this->products_carts as $products_cart){
      if($products_cart->getProduct()->getId() == $product->getId()){
        $found = true;
        break;
      }
    }
    return $found;
  }
  
  public function removeProductCart($productCart, $user=null){
//        Ajout d'un produit au panier et MAJ de la valeur totale du panier...
    if(is_null($user)){
      $this->products_carts->removeElement($productCart);
      $this->quantity -= $productCart->getQuantity();
//      $this->amount -= ($productCart->getQuantity()*$productCart->getProduct()->getPriceShown());
      $this->amount->addNumber(-1*$productCart->getProduct()->getPriceShown()->multNumber($productCart->getQuantity()), true);
    }
    else{
      foreach($this->products_carts as $products_cart){
        if($products_cart->getProductId() == $productCart->getProductId() AND $products_cart->getUserId() == $productCart->getUserId()){
          $this->products_carts->removeElement($products_cart);
          $this->quantity -= $products_cart->getQuantity();
          $this->amount->addNumber(-1*$products_cart->getProduct()->getPriceShown()->multNumber($products_cart->getQuantity()), true);
//          $this->amount -= ($products_cart->getQuantity()*$products_cart->getProduct()->getPriceShown());
          break;
        }
      }
    }
  }

  /**
   * @return mixed
   */
  public function getLocked()
  {
    return $this->locked;
  }
  
  public function isLocked()
  {
    return $this->locked;
  }
  
  /**
   * @param mixed $locked
   */
  public function setLocked($locked)
  {
    $this->locked = $locked;
  }
  
}