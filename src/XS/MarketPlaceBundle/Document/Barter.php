<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 10/18/2015
 * Time: 3:30 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Barter
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

class Barter
{
  /** @MongoDB\Id() */
  protected $id; //Id de cette proposition
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $user; //Qui a fait cette proposition ?
  
  /** @MongoDB\Field(type="date") */
  protected $date;
  
  /** @MongoDB\EmbedOne(targetDocument="BarterMoney") */
  protected $money; //Argent ajoute ou demande...
  
  /** @MongoDB\EmbedMany(targetDocument="ProductBarter") */
  protected $products_barter;
  
  /** @MongoDB\Field(type="boolean") */
  protected $validated; //La devise utilisee pour ce produit...

//    La date de validation... :)
  /** @MongoDB\Field(type="date") */
  protected $date_validation;
  
  
  /**
   * Barter constructor.
   */
  public function __construct() {
    $this->date = new \DateTime();
    $this->id = new \MongoId();
    $this->products_barter = new ArrayCollection();
    $this->validated = false;
  }
  
  /**
   * @return mixed
   */
  public function getId() {
    return $this->id;
  }
  
  /**
   * @param mixed $id
   */
  public function setId($id) {
    $this->id = $id;
  }
  
  /**
   * @return mixed
   */
  public function getUser() {
    return $this->user;
  }
  
  /**
   * @param mixed $user
   */
  public function setUser($user) {
    $this->user = $user;
  }
  
  /**
   * @return mixed
   */
  public function getDate() {
    return $this->date;
  }
  
  /**
   * @param mixed $date
   */
  public function setDate($date) {
    $this->date = $date;
  }
  
  /**
   * @return mixed
   */
  public function getMoney() {
    return $this->money;
  }
  
  /**
   * @param mixed $money
   */
  public function setMoney($money) {
    $this->money = $money;
  }
  
  /**
   * @return mixed
   */
  public function getProductsBarter() {
    return $this->products_barter;
  }
  
  /**
   * @param mixed $products_barter
   */
  public function setProductsBarter($products_barter) {
    $this->products_barter = $products_barter;
  }
  
  public function addProductsBarter($product_barter) {
    $this->products_barter[] = $product_barter;
  }
  
  /**
   * @return mixed
   */
  public function getValidated() {
    return $this->validated;
  }
  
  /**
   * @param mixed $validated
   */
  public function setValidated($validated) {
    $this->validated = $validated;
  }
  
  public function validate(){
//        Permet de valider l'offre... :)
    $this->setValidated(true);
    $this->setDateValidation(new \DateTime());
  }
  
  /**
   * @return mixed
   */
  public function getDateValidation() {
    return $this->date_validation;
  }
  
  /**
   * @param mixed $date_validation
   */
  public function setDateValidation($date_validation) {
    $this->date_validation = $date_validation;
  }
}