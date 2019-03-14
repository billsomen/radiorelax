<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 10/19/2015
 * Time: 12:29 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\AfrobankBundle\Document\Amount;

/**
 * Class ProductCart
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */

class ProductCart
{
  //Pour l'instant on ne copie que le ProductBarter...
  /** @MongoDB\Field(type="boolean") */
  protected $bought;
  
  //    Les produits mis en trocs...
  /** @MongoDB\Field(type="integer") */
  protected $quantity; //La quantite de produits mis en jeux pour ce troc....
  
  //Produit contre lequel on souhaite faire du troc... :)
  /** @MongoDB\ReferenceOne(targetDocument="Product") */
  protected $product;
  
  /** @MongoDB\Field(type="date") */
  protected $date_add;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
  protected $amount;
  
  /** @MongoDB\Field(type="string") */
  protected $name;
  
  /** @MongoDB\Field(type="string") */
  protected $currency;
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $user;
  
  /** @MongoDB\Field(type="string") */
  protected $user_name;
  
  /** @MongoDB\Field(type="string") */
  protected $user_id;
  
  /** @MongoDB\Field(type="string") */
  protected $profile_url;
  
  /** @MongoDB\Field(type="string") */
  protected $product_id;
  
  /**
   * ProductCart constructor.
   */
  public function __construct() {
    $this->quantity = 1;
    $this->date_add = new \DateTime();
    $this->bought = false;
    $this->amount = new Amount();
  }
  
  /**
   * @return mixed
   */
  public function getBought() {
    return $this->bought;
  }
  
  /**
   * @param mixed $bought
   */
  public function setBought($bought) {
    $this->bought = $bought;
  }
  
  public function isBought() {
    $this->bought = true;
  }
  
  /**
   * @return mixed
   */
  public function getQuantity()
  {
    return $this->quantity;
  }
  
  /**
   * @param mixed $quantity
   */
  public function setQuantity($quantity)
  {
    $this->quantity = $quantity;
  }
  
  /**
   * @return mixed
   */
  public function getProduct()
  {
    return $this->product;
  }
  
  /**
   * @param mixed $product
   */
  public function setProduct($product)
  {
    $this->product = $product;
    $this->product_id = $product->getId();
    $this->name = $product->getName();
    $this->amount = $product->getPriceShown();
    $this->profile_url = $product->getProfileUrl();
  }
  
  /**
   * @return mixed
   */
  public function getDateAdd()
  {
    return $this->date_add;
  }
  
  /**
   * @param mixed $date_add
   */
  public function setDateAdd($date_add)
  {
    $this->date_add = $date_add;
  }
  
  /**
   * @return mixed
   */
  public function getAmount()
  {
    return $this->amount;
  }
  
  /**
   * @param mixed $amount
   */
  public function setAmount($amount)
  {
    $this->amount = $amount;
  }
  
  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }
  
  /**
   * @param mixed $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  
  /**
   * @return mixed
   */
  public function getCurrency()
  {
    return $this->currency;
  }
  
  /**
   * @param mixed $currency
   */
  public function setCurrency($currency)
  {
    $this->currency = $currency;
  }
  
  /**
   * @return mixed
   */
  public function getProfileUrl()
  {
    return $this->profile_url;
  }
  
  /**
   * @param mixed $profile_url
   */
  public function setProfileUrl($profile_url)
  {
    $this->profile_url = $profile_url;
  }
  
  /**
   * @return mixed
   */
  public function getProductId()
  {
    return $this->product_id;
  }
  
  /**
   * @param mixed $product_id
   */
  public function setProductId($product_id)
  {
    $this->product_id = $product_id;
  }
  
  /**
   * @return mixed
   */
  public function getUser()
  {
    return $this->user;
  }
  
  /**
   * @param mixed $user
   */
  public function setUser($user)
  {
    $this->user = $user;
    $this->user_name = $user->getNickname();
    $this->user_id = $user->getId();
  }
  
  /**
   * @return mixed
   */
  public function getUserName()
  {
    return $this->user_name;
  }
  
  /**
   * @param mixed $user_name
   */
  public function setUserName($user_name)
  {
    $this->user_name = $user_name;
  }
  
  /**
   * @return mixed
   */
  public function getUserId()
  {
    return $this->user_id;
  }
  
  /**
   * @param mixed $user_id
   */
  public function setUserId($user_id)
  {
    $this->user_id = $user_id;
  }
}