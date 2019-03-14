<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/6/2015
 * Time: 10:39 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Proposal
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */


class Proposal
{
  /** @MongoDB\Id() */
  protected $id; //Id de cette proposition
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $user; //Qui a fait cette proposition ?
  
  /** @MongoDB\Field(type="date") */
  protected $date;
  
  /** @MongoDB\Field(type="float") */
  protected $offer; //Valeur de la proposition en vente aux encheres..
  
  /** @MongoDB\Field(type="string") */
  protected $currency; //La devise utilisee pour ce produit...
  
  /** @MongoDB\Field(type="boolean") */
//    Acceptée ?
  protected $validated;

//    La date de validation... :)
  /** @MongoDB\Field(type="date") */
  protected $date_validation;
  
  /**
   * Proposal constructor.
   */
  public function __construct() {
    $this->currency = 'XAF';
    $this->date = new \DateTime();
    $this->id = new \MongoId();
    $this->validated = false;
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
  public function getOffer() {
    return $this->offer;
  }
  
  /**
   * @param mixed $offer
   */
  public function setOffer($offer) {
    $this->offer = $offer;
  }
  
  /**
   * @return mixed
   */
  public function getCurrency() {
    return $this->currency;
  }
  
  /**
   * @param mixed $currency
   */
  public function setCurrency($currency) {
    $this->currency = $currency;
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
