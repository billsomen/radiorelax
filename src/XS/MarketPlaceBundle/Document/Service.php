<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 8/28/2015
 * Time: 2:16 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use XS\CoreBundle\Document\Image;
use XS\CoreBundle\Document\ManagerSystem;

/**
 * Class Service
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\Document
 */

class Service extends ManagerSystem
{
  /** @MongoDB\Field(type="string") */
  protected $name; //Nom du service, remplit automatiquement (modelisation 3d, dessins, etc...)
  
  /** @MongoDB\ReferenceMany(targetDocument="XS\CoreBundle\Document\Image") */
  protected $photos;  //Les photos descriptives du produit, pour le reconnaitre rapidement...
  
  /** @MongoDB\ReferenceOne(targetDocument="Category") */
  protected $category; //Categuorie referencee. Obtenu par une concatenation du nom de ce service et de sa supra categorie
  
  /** @MongoDB\Field(type="float") */
  protected $hour_price; //Prix des heures de travail de ce service. Comment payer la personne par heure ?
  
  /** @MongoDB\Field(type="string") */
  protected $payment_type; //Type de paiement (au km, a l'heure, a la realisation, arrangement)
  
  /** @MongoDB\Field(type="float") */
  protected $price_per_type; //Le paiement a l'unite de type de paiement...
  
  /** @MongoDB\Field(type="string") */
  protected $currency; //La devise utilisee...
  
  /** @MongoDB\ReferenceMany(targetDocument="Store") */
  protected $stores;  //Quelles sont les stores qui implementent ce service ?
  
  /** @MongoDB\ReferenceOne(targetDocument="Store") */
  protected $store;  //Quelles sont les stores qui implementent ce service ?
  
  /**
   * @return mixed
   */
  public function getName() {
    return $this->name;
  }
  
  /**
   * @param mixed $name
   */
  public function setName($name) {
    $this->name = $name;
  }
  
  /**
   * @return mixed
   */
  public function getCategory() {
    return $this->category;
  }
  
  /**
   * @param mixed $category
   */
  public function setCategory($category) {
    $this->category = $category;
  }
  
  /**
   * @return mixed
   */
  public function getHourPrice() {
    return $this->hour_price;
  }
  
  /**
   * @param mixed $hour_price
   */
  public function setHourPrice($hour_price) {
    $this->hour_price = $hour_price;
  }
  
  /**
   * @return mixed
   */
  public function getStores() {
    return $this->stores;
  }
  
  /**
   * @param mixed $stores
   */
  public function setStores($stores) {
    $this->stores = $stores;
  }
  
  public function addStore($data){
    $this->stores[] = $data;
  }
  
  public function __construct()
  {
    parent::__construct();
    $this->setDateAdd(new \DateTime());
    $this->photos = new ArrayCollection();
    $this->currency = 'XAF';
  }
  
  public function removeStore($store)
  {
    $this->stores->removeElement($store);
  }
  
  /**
   * @return mixed
   */
  public function getStore() {
    return $this->store;
  }
  
  /**
   * @param mixed $store
   */
  public function setStore($store) {
    $this->store = $store;
  }
  
  /**
   * @return mixed
   */
  public function getPaymentType() {
    return $this->payment_type;
  }
  
  /**
   * @param mixed $payment_type
   */
  public function setPaymentType($payment_type) {
    $this->payment_type = $payment_type;
  }
  
  /**
   * @return mixed
   */
  public function getPricePerType() {
    return $this->price_per_type;
  }
  
  /**
   * @param mixed $price_per_type
   */
  public function setPricePerType($price_per_type) {
    $this->price_per_type = $price_per_type;
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
  public function getPhotos() {
    return $this->photos;
  }
  
  /**
   * @param mixed $photos
   */
  public function setPhotos($photos) {
    $this->photos = $photos;
  }
  
  public function addPhoto($data) {
    $this->photos[] = $data;
  }
  
  /**
   * Remove photo
   *
   * @param Image $photo
   */
  public function removePhoto(Image $photo)
  {
    $this->photos->removeElement($photo);
  }
  
  
}
