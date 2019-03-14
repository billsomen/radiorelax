<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 8/28/2015
 * Time: 2:15 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\AfrobankBundle\Document\Amount;
use XS\CoreBundle\Document\EditionBase;
use XS\CoreBundle\Document\Image;
use XS\CoreBundle\Document\ManagerSystem;

/**
 * Class Product
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\Document
 */

class Product extends EditionBase
{
  //todo les produits n'ont pas de namespace
  
  /*
   * On identifie un produit par :
   * Etat
   * Couleur
   * Quantite
   * Localisation --->todo: par defaut la localisation de Boutique/Vendeur
   * ...Et c'est tout
   */
  
  const PAY_ONCE = "once";
  const PAY_YEAR = "year";
  const PAY_MONTH = "month";
  const PAY_WEEK = "week";
  const PAY_DAY = "day";
  const PAY_HOUR = "hour";
  
  /** @MongoDB\EmbedOne(targetDocument="Cart") */
//    Stores
  protected $cart;
  
  /** @MongoDB\EmbedOne(targetDocument="Cart") */
  protected $wish_list;
  
  /** @MongoDB\EmbedOne(targetDocument="Cart") */
  protected $compare_list;
  
  /** @MongoDB\EmbedOne(targetDocument="Cart") */
  protected $orders;
  
  /** @MongoDB\Field(type="string") */
//    once, year, month, etc...
  protected $pay_frequency;
  
  /** @MongoDB\Field(type="boolean") */
//    Location ?
  protected $pay_loan;
  
  /** @MongoDB\Field(type="string") */
//    Catégorie_et sub
  protected $cat_sub;
  
  /** @MongoDB\Field(type="string") */
//    Catégorie_Principale
  protected $category;
  
  /** @MongoDB\Field(type="boolean") */
//    Ceci est un logement
  protected $is_house;
  
  /** @MongoDB\Field(type="string") */
//    Sous Catégorie_Principale
  protected $sub_category;
  
  /** @MongoDB\Field(type="string") */
  protected $state; //etat du produit : new.neuf, second_hand.seconde-main, used.occasion.
  
  /** @MongoDB\Field(type="string") */
//    Je l'ai 2p8 com*en 2 temps ?
  protected $state_duration;
  
  /** @MongoDB\Field(type="string") */
//    Ecran cassé, etc...
  protected $state_description;
  
  /** @MongoDB\Field(type="boolean") */
  protected $bartering; //On peut troquer ?
  
  /** @MongoDB\EmbedMany(targetDocument="Barter") */
  protected $barters; //La liste des trocs proposes sur ce produit...
  
  /** @MongoDB\Field(type="boolean") */
  protected $available; //A 0 si qte = 0, 1 sinon. Vaut aussi 0 si la boutique correspondante n'est pas disponible elle aussi pour des raisons diverses...
  
  /** @MongoDB\ReferenceOne(targetDocument="ProductStandard") */
  protected $standard;
  
  /** @MongoDB\Field(type="string") */
  protected $standard_name;
  
  /** @MongoDB\EmbedOne(targetDocument="ProductStandardModel") */
//    Le modèle de produit standard contenant toutes les informations 
  protected $standard_model;
  
  /** @MongoDB\Field(type="string") */
  protected $standard_id;

//    todo: plus tard. Maintenant, on utilise seulement : price_shown et price_min
  /** @MongoDB\EmbedOne(targetDocument="Pricing") */
  protected $pricing; //liste des prix-qte, les auctions (ventes aux encheres et propositions du venteur (ex: 1 = 300, 2 = 500, etc...)
  
  /** @MongoDB\EmbedOne(targetDocument="StandardHouse") */
//  Pour commencer, on ne commence que par les logements
  protected $house;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
  protected $price_shown;
  
  /** @MongoDB\Field(type="float") */
  protected $price_ratio;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
  protected $price_old;
  
  /** @MongoDB\EmbedMany(targetDocument="Buyer") */
  protected $buyers;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
  protected $price_min;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
  protected $pay_month;
  
  /** @MongoDB\Field(type="string") */
  protected $currency; //La devise utilisee pour ce produit...
  
  //todo: Changer les prices et ameliore Pricing...
  
  /** @MongoDB\Field(type="float") */
  protected $renting_day_price; //todo: S'il faille louer, quelles sont les modalites... ? DayPrice : Prix au jour de location
  
  /** @MongoDB\Field(type="integer") */
  protected $quantity = 1; //La quantite representee. Par defaut, comme partout, cette valeur vaut 1.
  
  /** @MongoDB\ReferenceOne(targetDocument="Store") */
  protected $store; //Quelle est la store qui contienne ce produit ?

//    todo: On met des mots cles pour faciliter la recherche.
//      todo> On en mettra aussi sur les produits standards..
  
  /**
   * @return mixed
   */
  public function getBartering() {
    return $this->bartering;
  }
  
  /**
   * @param mixed $bartering
   */
  public function setBartering($bartering) {
    $this->bartering = $bartering;
  }
  
  /**
   * @return mixed
   */
  public function getAvailable() {
    return $this->available;
  }
  
  /**
   * @param mixed $available
   */
  public function setAvailable($available) {
    $this->available = $available;
  }
  
  /**
   * @return mixed
   */
  public function getStandard() {
    return $this->standard;
  }
  
  /**
   * @param mixed $standard
   */
  public function setStandard($standard) {
    $this->standard = $standard;
  }
  
  /**
   * @return mixed
   */
  public function getPricing() {
    return $this->pricing;
  }
  
  /**
   * @param mixed $pricing
   */
  public function setPricing($pricing) {
    $this->pricing = $pricing;
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
  public function getState() {
    return $this->state;
  }
  
  /**
   * @param mixed $state
   */
  public function setState($state) {
    $this->state = $state;
  }
  
  /**
   * @return mixed
   */
  public function getRentingDayPrice() {
    return $this->renting_day_price;
  }
  
  /**
   * @param mixed $renting_day_price
   */
  public function setRentingDayPrice($renting_day_price) {
    $this->renting_day_price = $renting_day_price;
  }
  
  /**
   * @return mixed
   */
  public function getPriceShown() {
    return $this->price_shown;
  }
  
  /**
   * @param mixed $price_shown
   */
  public function setPriceShown($price_shown) {
    $this->price_old = $this->price_shown;
    $this->price_shown = $price_shown;
    $this->updatePrices();
//    $this->price_ratio = ($this->price_shown - $this->price_old)/$this->price_old*100;

//    On MAJ le tarif Mensuel
    /*//    $this->pay_month
        switch($this->pay_frequency){
          case self::PAY_ONCE:
            $this->pay_month = -1;
            break;
          case self::PAY_HOUR:
            $this->pay_month = 24*30*$this->price_shown;
            break;
          case self::PAY_DAY:
            $this->pay_month = 30*$this->price_shown;
            break;
          case self::PAY_WEEK:
            $this->pay_month = 4*$this->price_shown;
            break;
          case self::PAY_YEAR:
            $this->pay_month = $this->price_shown/12;
            break;
          default:
            $this->pay_month = $price_shown;
            break;
        }*/
  }

//  /** @MongoDB\PrePersist() */
//  /** @MongoDB\PreUpdate() */
//  /** @MongoDB\PreFlush() */
  public function updatePrices(){
    $price_shown = $this->price_shown;
    if(!$price_shown->isEqual($this->price_old)){
      $this->price_ratio = $this->price_shown->diffAmount($this->price_old)->div($this->price_old)*100;
    }
//    On MAJ le tarif Mensuel
//    $this->pay_month
    switch($this->pay_frequency){
      case self::PAY_ONCE:
        print_r('once');
        $this->pay_month = new Amount(-1);
        break;
      case self::PAY_HOUR:
        print_r('hour');
        $this->pay_month = $this->price_shown->multNumberAmount(24*30);
        break;
      case self::PAY_DAY:
        print_r('day');
        $this->pay_month =  $this->price_shown->multNumberAmount(30);
        break;
      case self::PAY_WEEK:
        print_r('week');
        $this->pay_month =  $this->price_shown->multNumberAmount(4);
        break;
      case self::PAY_YEAR:
        print_r('year');
        $this->pay_month =  $this->price_shown->multNumberAmount(1/12);
        break;
      default:
        print_r('old');
        $this->pay_month = $price_shown;
        break;
    }
    print_r(6);
    print_r($this->pay_month);
    print_r($this->price_shown);
  }
  
  /**
   * @return mixed
   */
  public function getPriceMin() {
    return $this->price_min;
  }
  
  /**
   * @param mixed $price_min
   */
  public function setPriceMin($price_min) {
    $this->price_min = $price_min;
  }
  
  public function __construct()
  {
    parent::__construct();
    $this->proposals = new ArrayCollection();
    $this->barters = new ArrayCollection();
    $this->buyers = new ArrayCollection();
    $this->setStandardModel(new ProductStandardModel());
    $this->currency = 'USD';
//        Theses prices are mo*ifie* when the price_shown is up*ate*
    $this->price_ratio = 1;
    $this->price_old = new Amount();;
    $this->is_house = 1;
    $this->house = new StandardHouse();
    $this->pay_frequency = self::PAY_MONTH;
    $this->pay_loan = true;
    $this->price_shown = new Amount();
    $this->pay_month = new Amount();
    
    $this->cart = new Cart();
    $this->wish_list = new Cart();
    $this->compare_list = new Cart();
    $this->orders = new Cart();
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
  
  public function addProposal($proposal) {
    $this->proposals[] = $proposal;
  }
  
  public function removeProposal($proposal) {
    $this->proposals->removeElement($proposal);
  }
  
  /**
   * @return mixed
   */
  public function getBarters() {
    return $this->barters;
  }
  
  /**
   * @param mixed $barters
   */
  public function setBarters($barters) {
    $this->barters = $barters;
  }
  
  public function addBarter($barter) {
    $this->barters[] = $barter;
  }
  
  /**
   * @return mixed
   */
  public function getBuyers() {
    return $this->buyers;
  }
  
  /**
   * @param mixed $buyers
   */
  public function setBuyers($buyers) {
    $this->buyers = $buyers;
  }
  
  public function addBuyer($buyer) {
    $this->buyers[] = $buyer;
  }
  
  public function quantityReduce($value) {
//        Permet de decrementer la quantite. Par exemple pour le cas des propositions, achats... :)
    $this->quantity -= $value;
  }
  
  /**
   * @return mixed
   */
  public function getCategory()
  {
    return $this->category;
  }
  
  /**
   * @param mixed $category
   */
  public function setCategory($category)
  {
    $this->category = $category;
  }
  
  /**
   * @return mixed
   */
  public function getSubCategory()
  {
    return $this->sub_category;
  }
  
  /**
   * @param mixed $sub_category
   */
  public function setSubCategory($sub_category)
  {
    $this->sub_category = $sub_category;
  }
  
  /**
   * @return mixed
   */
  public function getStandardModel()
  {
    return $this->standard_model;
  }
  
  /**
   * @param mixed $standard_model
   */
  public function setStandardModel($standard_model)
  {
    $this->standard_model = $standard_model;
  }
  
  /**
   * @return mixed
   */
  public function getCatSub()
  {
    return $this->cat_sub;
  }
  
  /**
   * @param mixed $cat_sub
   */
  public function setCatSub($cat_sub)
  {
    $this->cat_sub = $cat_sub;
  }
  
  /**
   * @return mixed
   */
  public function getPriceRatio()
  {
    return $this->price_ratio;
  }
  
  /**
   * @param mixed $price_ratio
   */
  public function setPriceRatio($price_ratio)
  {
    $this->price_ratio = $price_ratio;
  }
  
  /**
   * @return mixed
   */
  public function getPriceOld()
  {
    return $this->price_old;
  }
  
  /**
   * @param mixed $price_old
   */
  public function setPriceOld($price_old)
  {
    $this->price_old = $price_old;
  }
  
  /**
   * @return mixed
   */
  public function getStateDuration()
  {
    return $this->state_duration;
  }
  
  /**
   * @param mixed $state_duration
   */
  public function setStateDuration($state_duration)
  {
    $this->state_duration = $state_duration;
  }
  
  /**
   * @return mixed
   */
  public function getStateDescription()
  {
    return $this->state_description;
  }
  
  /**
   * @param mixed $state_description
   */
  public function setStateDescription($state_description)
  {
    $this->state_description = $state_description;
  }
  
  /**
   * @return mixed
   */
  public function getStandardName()
  {
    return $this->standard_name;
  }
  
  /**
   * @param mixed $standard_name
   */
  public function setStandardName($standard_name)
  {
    $this->standard_name = $standard_name;
  }
  
  /**
   * @return mixed
   */
  public function getStandardId()
  {
    return $this->standard_id;
  }
  
  /**
   * @param mixed $standard_id
   */
  public function setStandardId($standard_id)
  {
    $this->standard_id = $standard_id;
  }
  
  /**
   * @return mixed
   */
  public function getIsHouse()
  {
    return $this->is_house;
  }
  
  /**
   * @param mixed $is_house
   */
  public function setIsHouse($is_house)
  {
    $this->is_house = $is_house;
  }
  
  /**
   * @return mixed
   */
  public function getHouse()
  {
    return $this->house;
  }
  
  /**
   * @param mixed $house
   */
  public function setHouse($house)
  {
    $this->house = $house;
  }
  
  /**
   * @return mixed
   */
  public function getPayFrequency()
  {
    return $this->pay_frequency;
  }
  
  /**
   * @param mixed $pay_frequency
   */
  public function setPayFrequency($pay_frequency)
  {
    $this->pay_frequency = $pay_frequency;
    $this->updatePrices();
  }
  
  /**
   * @return mixed
   */
  public function getPayLoan()
  {
    return $this->pay_loan;
  }
  
  /**
   * @param mixed $pay_loan
   */
  public function setPayLoan($pay_loan)
  {
    $this->pay_loan = $pay_loan;
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
    if(empty($this->name)){
      $this->name = 'Aucun Nom';
    }
  }
  
  /**
   * @return mixed
   */
  public function getPayMonth()
  {
    return $this->pay_month;
  }
  
  /**
   * @param mixed $pay_month
   */
  public function setPayMonth($pay_month)
  {
    $this->pay_month = $pay_month;
  }
  
  /**
   * @return mixed
   */
  public function getCart()
  {
    return $this->cart;
  }
  
  /**
   * @param mixed $cart
   */
  public function setCart($cart)
  {
    $this->cart = $cart;
  }
  
  /**
   * @return mixed
   */
  public function getWishList()
  {
    return $this->wish_list;
  }
  
  /**
   * @param mixed $wish_list
   */
  public function setWishList($wish_list)
  {
    $this->wish_list = $wish_list;
  }
  
  /**
   * @return mixed
   */
  public function getCompareList()
  {
    return $this->compare_list;
  }
  
  /**
   * @param mixed $compare_list
   */
  public function setCompareList($compare_list)
  {
    $this->compare_list = $compare_list;
  }
  
  /**
   * @return mixed
   */
  public function getOrders()
  {
    return $this->orders;
  }
  
  /**
   * @param mixed $orders
   */
  public function setOrders($orders)
  {
    $this->orders = $orders;
  }
  
}
