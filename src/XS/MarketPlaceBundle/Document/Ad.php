<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/12/2015
 * Time: 6:26 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\CoreBundle\Document\Image;
use XS\CoreBundle\Document\ManagerSystem;

/**
 * Class Ad
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\Document
 */
class Ad extends ManagerSystem
{
  /** @MongoDB\Field(type="string") */
  protected $title;//Titre de notre annonce...
  
  /** @MongoDB\Field(type="string") */
  protected $state;//Active OR inactive
  
  /** @MongoDB\Field(type="string") */
  protected $type;//Type d'annonce : Produit ou Service ?
  
  /** @MongoDB\Field(type="string") */
  protected $content;// todo: Le contenu de notre annonce...
  
  /** @MongoDB\ReferenceMany(targetDocument="XS\CoreBundle\Document\Image") */
  protected $photos;  //Les photos descriptives de l'annonce...

//    On ne sauvegardde pas ce parametre, etant donne qu'on pourrait le changer plus tard...
  protected $icon;
  
  /**
   * @return mixed
   */
  public function getTitle() {
    return $this->title;
  }
  
  /**
   * @param mixed $title
   */
  public function setTitle($title) {
    $this->title = $title;
  }
  
  /**
   * @return mixed
   */
  public function getContent() {
    return $this->content;
  }
  
  /**
   * @param mixed $content
   */
  public function setContent($content) {
    $this->content = $content;
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
   * @return mixed
   */
  public function getType() {
    return $this->type;
  }
  
  /**
   * @param mixed $type
   */
  public function setType($type) {
    $this->type = $type;
  }
  
  /**
   * @return mixed
   */
  public function getIcon() {
    return $this->icon;
  }
  
  public function generateIcon($size){
    $this->icon = 'fa fa-folder fa-'.$size.'x';
    if($this->type == 'service'){
      $this->icon = 'fa fa-wrench fa-'.$size.'x';
    }
    
    return $this->icon;
  }
  
  /**
   * @param mixed $icon
   */
  public function setIcon($icon) {
    $this->icon = $icon;
  }
  
  
  public function __construct()
  {
    $this->setDateAdd(new \DateTime());
    $this->state = 'Active';
    $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
  }
  
  public function removePhoto(Image $photo)
  {
    $this->photos->removeElement($photo);
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
  
  public function deactivate() {
    $this->state = 'Inactive';
  }
  
  public function activate() {
    $this->state = 'Active';
  }
}
