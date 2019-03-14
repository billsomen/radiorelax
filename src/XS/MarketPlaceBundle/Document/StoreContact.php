<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 8/29/2015
 * Time: 5:59 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Store
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

class StoreContact
{
  //Ceci ce sont les contacts de la boutique, ils suivent un modele bien defini...
  
  /** @MongoDB\EmbedMany(targetDocument="XS\UserBundle\Document\Telephone") */
  protected $telephones;
  
  /**
   * @MongoDB\Field(type="string")
   * @Assert\Email()
   */
  
  protected $email; //Adresse Email externe... @partir du namespace
  
  
  /** @MongoDB\Field(type="collection") */
  protected $websites;
  
  /**
   * @MongoDB\Field(type="string")
   */
  
  protected $twitter; //todo: Url vers le compte twitter  - Compte Pro
  
  /**
   * @MongoDB\Field(type="string")
   */
  
  protected $facebook;
  
  /**
   * @return mixed
   */
  public function getTelephones() {
    return $this->telephones;
  }
  
  /**
   * @param mixed $telephones
   */
  public function setTelephones($telephones) {
    $this->telephones = $telephones;
  }
  
  /**
   * @return mixed
   */
  public function getEmail() {
    return $this->email;
  }
  
  /**
   * @param mixed $email
   */
  public function setEmail($email) {
    $this->email = $email;
  }
  
  /**
   * @return mixed
   */
  public function getTwitter() {
    return $this->twitter;
  }
  
  /**
   * @param mixed $twitter
   */
  public function setTwitter($twitter) {
    $this->twitter = $twitter;
  }
  
  /**
   * @return mixed
   */
  public function getFacebook() {
    return $this->facebook;
  }
  
  /**
   * @param mixed $facebook
   */
  public function setFacebook($facebook) {
    $this->facebook = $facebook;
  } //todo: Url de la page Facebook. Bien notifier cela au moment de la majour de cette information
  
  public function addTelephone($telephone){
    $this->telephones[] = $telephone;
  }
  
  public function generateEmail($namespace){ //Genere l'Email...apd namespace
    $this->setEmail($namespace.'@mboadjoss.com');
  }
  
  //A ce stade, on est bon pour les contacts...
  
  public function __construct()
  {
    $this->telephones = array();
    $this->websites = array();
  }
 
  public function removeTelephone($telephone)
  {
    $key = -1;
    foreach($this->telephones as $telephone_i){
      $key++;
      if($telephone_i == $telephone){
        unset($this->telephones[$key]);
      }
    }
  }
  
  /**
   * @return mixed
   */
  public function getWebsites()
  {
    return $this->websites;
  }
  
  /**
   * @param mixed $websites
   */
  public function setWebsites($websites)
  {
    $this->websites = $websites;
  }
  
}
