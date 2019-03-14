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
use MainBundle\Document\Album;
use XS\AfrobankBundle\Document\Amount;
use XS\AfrobankBundle\Interfaces\CartInterface;

/**
 *
 * Class Cart
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 *
 */

class Cart implements CartInterface
{
//    Le panier de l'utilisateur.
  /** @MongoDB\ReferenceMany(targetDocument="MainBundle\Document\Album") */
  protected $albums;
  
//  The cart is lokced when there's a pending payment
  /** @MongoDB\Field(type="boolean") */
  protected $locked;
  
  /**
   * Cart constructor.
   */
  public function __construct() {
    $this->locked = false;
    $this->albums = new ArrayCollection();
  }

  public function getAmount()
  {
// Get the value generated
//    Tout est conservé en CAD
//  Le solde actuel du compte : total des transactions : il est calculé à l'affichage
    $amount = new Amount();
    foreach ($this->albums as $album){
      $amount->addAmount($album->getAmount(), true);
    }

    return $amount;
  }
  
  public function getQuantity(){
    return count($this->albums);
  }

  public function drop()
  {
    // TODO: Implement drop() method.
    $this->__construct();
  }

  public function addAlbum($album){
//        Add an album
    if(!$this->isAlbumInCart($album)){
//      We add the album
      $this->albums->add($album);
      return true;
    }
    return false;
  }
  
  public function removeAlbum($album){
//       Remove an album if not exists
    if($this->isAlbumInCart($album)){
//      On retire simplement l'album
      $this->albums->removeElement($album);
    }
  }

  public function isAlbumInCart(Album $album){
    $found = false;
    foreach($this->albums as $album_){
      if($album_->getId() == $album->getId()){
        $found = true;
        break;
      }
    }
    return $found;
  }

  public function isLocked()
  {
    return $this->locked;
  }

  public function lock()
  {
    $this->locked = true;
  }

  public function unLock()
  {
    $this->locked = false;
  }

  
}