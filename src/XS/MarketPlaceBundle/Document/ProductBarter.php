<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 10/18/2015
 * Time: 3:44 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class ProductBarter
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

class ProductBarter
{
//    Les produits mis en trocs...
  /** @MongoDB\Field(type="integer") */
  protected $quantity; //La quantite de produits mis en jeux pour ce troc....
  
  //Produit contre lequel on souhaite faire du troc... :)
  /** @MongoDB\ReferenceOne(targetDocument="Product") */
  protected $product;
  
  /** @MongoDB\Field(type="date") */
  protected $date_add;
  
  /** @MongoDB\Field(type="float") */
  protected $amount;
  
  /** @MongoDB\Field(type="string") */
  protected $name;
  
  /** @MongoDB\Field(type="string") */
  protected $currency;
  
  /** @MongoDB\Field(type="string") */
  protected $profile_url;
  
  /** @MongoDB\Field(type="string") */
  protected $product_id;
  
  /**
   * ProductBarter constructor.
   */
  public function __construct() {
//        La quantite par defaut est de 1...
    $this->quantity = 1;
    $this->date_add = new \DateTime();
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
  
  /**
   * @return mixed
   */
  public function getProduct() {
    return $this->product;
  }
  
  /**
   * @param mixed $product
   */
  public function setProduct($product) {
    $this->product = $product;
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
  
  
  
}