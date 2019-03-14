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
use XS\CoreBundle\Document\ManagerSystem;

/**
 * Class Store
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\Document
 */

class Store extends ManagerSystem
{
  /** @MongoDB\Field(type="string") */
  protected $name; //Nom de la boutique
  
  /** @MongoDB\Field(type="boolean") */
  protected $available; // boutique est disponible quand on a paye son bail et qu'on est dans les temps...
  
  /** @MongoDB\Field(type="boolean") */
  protected $rent_paid; // Loyer paye via Afrobanking.
  
  /** @MongoDB\Field(type="float") */
  protected $max_products; // Le nombre de produits max dans la Store...
  
  /** @MongoDB\Field(type="float") */
  protected $max_services; // Le nombre de services max dans la Store...
  
  /** @MongoDB\Field(type="date") */
  protected $deadline; // Date de fin de disponibilité de la boutique : générée à partir du versement effectué...
  //todo Ces informations sont entrees uniquement lorsque l'on commence a mettre des produits dans la boutique ou a ajouter des services
  //todo par defaut, on a droit a 30 jours apres la publication d'un produit ou service
  
  /** @MongoDB\EmbedOne(targetDocument="Opened") */
  protected $opened; //Heures et dates d'ouverture
  //@todo: Pour plus tard...
  
  /** @MongoDB\ReferenceMany(targetDocument="XS\UserBundle\Document\User") */
  protected $members;
  
  /** @MongoDB\EmbedMany(targetDocument="XS\EducationBundle\Document\Contact") */
//    For user not on App!
  protected $contacts_members;
  
  //@todo: Pour plus tard... : On peut travailler dans la boutique de quelqu'un.
  /*On peut etre vendeur, editeur, livreur, etc.
   * Type Utilisateurs
   * Attributions propres a la page
   * Autres -> Concerne plus les services...
   *
  (types d'utilisateurs) danns une boutique, celon les types des utilisateurs..
  */
  
  /** @MongoDB\ReferenceMany(targetDocument="Service") */
  protected $services;  //Les services proposes par cette Store / Boutique
  
  /** @MongoDB\ReferenceMany(targetDocument="Product") */
  protected $products;  //On ajoute les produits dans la store, uniquement...

  /** @MongoDB\EmbedOne(targetDocument="StoreContact") */
  protected $contacts;  //Les contacts de la boutique
  
  /** @MongoDB\ReferenceMany(targetDocument="XS\CoreBundle\Document\Image") */
  protected $photos;  //Les photos de la boutique @todo: Optionnel
  
  /** @MongoDB\Field(type="string") */
  protected $url_logo;
  
  /** @MongoDB\Field(type="string") */
  protected $url_profile;
  
  /** @MongoDB\EmbedMany(targetDocument="UrlDocument") */
  protected $urls_documents;
  
  /** @MongoDB\Field(type="string")*/
  protected $type; //Type de boutique : Basique, Pro ou Standards...
  
  /** @MongoDB\Field(type="boolean")*/
  protected $terms;
  
  /** @MongoDB\EmbedMany(targetDocument="StoreProSuCat") */
  protected $pro_categories;
  
  /** @MongoDB\EmbedMany(targetDocument="ProCatSub") */
  protected $pros_cats;
  
  /** @MongoDB\ReferenceMany(targetDocument="Command") */
  protected $commands;
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\CoreBundle\Document\Newsletter") */
  protected $newsletter;
  
  /**
   * Store constructor.
   */
  public function __construct() {
    parent::__construct();
    $this->setDateAdd(new \DateTime());
    $this->max_products = 50;
    $this->max_services = 2;
    $this->terms = false;
    $this->products = new ArrayCollection();
    $this->services = new ArrayCollection();
    $this->pro_categories = new ArrayCollection();
    
    $this->contacts_members = new ArrayCollection();
    $this->commands = new ArrayCollection();
    $this->urls_documents = new ArrayCollection();
    $this->pros_cats = new ArrayCollection();
  }
  
  /**
   * @return mixed
   */
  public function getProsCats()
  {
    return $this->pros_cats;
  }
  
  /**
   * @param mixed $pros_cats
   */
  public function setProsCats($pros_cats)
  {
    $this->pros_cats = $pros_cats;
  }
  
  /**
   * @return mixed
   */
  public function getUrlsDocuments()
  {
    return $this->urls_documents;
  }
  
  /**
   * @param mixed $urls_documents
   */
  public function setUrlsDocuments($urls_documents)
  {
    $this->urls_documents = $urls_documents;
  }
  
  //Constructeur...
  
  
  /**
   * @return mixed
   */
  public function getType() {
    return $this->type;
  }
  
  /**
   * @param mixed $type
   */
  public function setType($type) {
    switch($type){
      case 'basic':
        $this->deadline = $this->getDateAdd()->modify('+14 day');
        $this->available = true;
        $this->rent_paid = true;
        break;
      case 'pro':
        //todo: Finaliser avec la classe de paiement...
        break;
      case 'standard':
        //todo: Finaliser avec la classe de paiement... et l'attribut rent_paid
        break;
    }
    $this->type = $type;
  }
  
  /**
   * @return mixed
   */
  public function getTerms() {
    return $this->terms;
  }
  
  /**
   * @param mixed $terms
   */
  public function setTerms($terms) {
    $this->terms = $terms;
  }
  
  /**
   * @return mixed
   */
  public function getName() {
    $date = new \DateTime();
//        Dans ce cas, on desactive la boutique...
    if($this->deadline < $date){
      $this->rent_paid = false;
      $this->available = false;
    }
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
  public function getDeadline() {
    return $this->deadline;
  }
  
  /**
   * @param mixed $deadline
   */
  public function setDeadline($deadline) {
    $this->deadline = $deadline;
  }
  
  /**
   * @return mixed
   */
  public function getOpened() {
    return $this->opened;
  }
  
  /**
   * @param mixed $opened
   */
  public function setOpened($opened) {
    $this->opened = $opened;
  }
  
  /**
   * @return mixed
   */
  public function getMembers() {
    return $this->members;
  }
  
  /**
   * @param mixed $members
   */
  public function setMembers($members) {
    $this->members = $members;
  }
  
  /**
   * @return mixed
   */
  public function getServices() {
    return $this->services;
  }
  
  /**
   * @param mixed $services
   */
  public function setServices($services) {
    $this->services = $services;
  }
  
  /**
   * @return mixed
   */
  public function getProducts() {
    return $this->products;
  }
  
  /**
   * @param mixed $products
   */
  public function setProducts($products) {
    $this->products = $products;
  }
  
  /**
   * @return mixed
   */
  public function getContacts() {
    return $this->contacts;
  }
  
  /**
   * @param mixed $contacts
   */
  public function setContacts($contacts) {
    $this->contacts = $contacts;
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

  //Fonctions speciales
  public function addProduct($data){
    $this->products[] = $data;
  }
  
  public function addService($data){
    $this->services[] = $data;
  }
  
  public function addPhoto($data){
    $this->photos[] = $data;
  }
  
  public function addMember($data){
    $this->members[] = $data;
  }
  
  /**
   * @return mixed
   */
  public function getRentPaid() {
    return $this->rent_paid;
  }
  
  public function isRentPaid() {
    return $this->rent_paid;
  }
  
  /**
   * @param mixed $rent_paid
   */
  public function setRentPaid($rent_paid) {
    $this->rent_paid = $rent_paid;
  }
  
  /**
   * @return mixed
   */
  public function getMaxProducts() {
    return $this->max_products;
  }
  
  /**
   * @param mixed $max_products
   */
  public function setMaxProducts($max_products) {
    $this->max_products = $max_products;
  }
  
  /**
   * @return mixed
   */
  public function getMaxServices() {
    return $this->max_services;
  }
  
  /**
   * @param mixed $max_services
   */
  public function setMaxServices($max_services) {
    $this->max_services = $max_services;
  }
  
  
  /**
   * Remove member
   *
   */
  public function removeMember($member)
  {
    $this->members->removeElement($member);
  }
  
  /**
   * Remove service
   */
  public function removeService($service)
  {
    $this->services->removeElement($service);
  }
  
  /**
   * Remove product
   *
   */
  public function removeProduct($product)
  {
    $this->products->removeElement($product);
  }
  
  /**
   * Remove photo
   *
   */
  public function removePhoto($photo)
  {
    $this->photos->removeElement($photo);
  }

//    On ecrit la fonction, qui active la boutique lorsque le paiement est effectue, et ceci pour le mode de paiement standard ou pro... :) =P
//    Ce n'a lieu qu'apres la validation Afrobanking
  public function activate(){
    $date = new \DateTime();
    switch($this->type){
      case 'pro':
        $this->deadline = $date->modify('+12 month');
        $this->available = true;
        $this->rent_paid = true;
        break;
      
      case 'standard':
        $this->deadline = $date->modify('+1 month');
        $this->available = true;
        $this->rent_paid = true;
        break;
    }
  }
  
  /**
   * @return mixed
   */
  public function getProCategories()
  {
    return $this->pro_categories;
  }
  
  /**
   * @param mixed $pro_categories
   */
  public function setProCategories($pro_categories)
  {
    $this->pro_categories = $pro_categories;
  }
  
  public function addProCategories($cat, $su_cat)
  {
    $tmp_pro_cat = null;
//        if(!isset($this->pro_categories)){
    if(0){
      $pro_cat = new StoreProSuCat();
      $pro_cat->setCat($cat);
      $tmp_pro_c = $pro_cat->getSuCats();
      $tmp_pro_c[] = $su_cat;
      $pro_cat->setSuCats($tmp_pro_c);
      $this->pro_categories->add($pro_cat);
    }
    else{
      $flag = false;
      $key = null;
      $len = $this->pro_categories->count();
      for ($i=0; $i<$len; $i++){
        if($cat == $this->pro_categories->get($i)->getCat()){
          $flag = true;
          $key = $i;
          break;
        }
      }
      
      
      if($flag){
        $pro_cat = $this->pro_categories->get($key);
        if(!array_search($su_cat, $pro_cat->getSuCats())){
//                    On ajoute, comme on n'a rien trou*é
          $tmp_pro_cat = $pro_cat->getSuCats();
          $tmp_pro_cat[] = $su_cat;
          $tmp_pro_cat2 = $pro_cat;
          $tmp_pro_cat2->setSuCats(array());
          $tmp_pro_cat2->setSuCats($tmp_pro_cat);
          
          $this->pro_categories->removeElement($pro_cat);
          $this->pro_categories->add($tmp_pro_cat2);
        }
      }
      else{
        $pro_cat = new StoreProSuCat();
        $pro_cat->setCat($cat);
        $tmp_pro_c = $pro_cat->getSuCats();
        $tmp_pro_c[] = $su_cat;
        $pro_cat->setSuCats(array());
        $pro_cat->setSuCats($tmp_pro_c);
        $this->pro_categories->add($pro_cat);
      }
    }
  }
  
  /**
   * @return mixed
   */
  public function getCommands()
  {
    return $this->commands;
  }
  
  /**
   * @param mixed $commands
   */
  public function setCommands($commands)
  {
    $this->commands = $commands;
  }
  
  /**
   * @return mixed
   */
  public function getContactsMembers()
  {
    return $this->contacts_members;
  }
  
  /**
   * @param mixed $contacts_members
   */
  public function setContactsMembers($contacts_members)
  {
    $this->contacts_members = $contacts_members;
  }
  
  /**
   * @return mixed
   */
  public function getUrlLogo()
  {
    return $this->url_logo;
  }
  
  /**
   * @param mixed $url_logo
   */
  public function setUrlLogo($url_logo)
  {
    $this->url_logo = $url_logo;
  }
  
  /**
   * @return mixed
   */
  public function getUrlProfile()
  {
    return $this->url_profile;
  }
  
  /**
   * @param mixed $url_profile
   */
  public function setUrlProfile($url_profile)
  {
    $this->url_profile = $url_profile;
  }
  
  /**
   * @return mixed
   */
  public function getNewsletter()
  {
    return $this->newsletter;
  }
  
  /**
   * @param mixed $newsletter
   */
  public function setNewsletter($newsletter)
  {
    $this->newsletter = $newsletter;
  }
}
