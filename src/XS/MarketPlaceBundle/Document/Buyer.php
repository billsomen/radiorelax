<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 10/19/2015
 * Time: 9:42 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * Class Buyer
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 *
 */


class Buyer
{
//    Bref, les informations sur
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $user; //Qui a fait cette proposition ?
  
  /** @MongoDB\Field(type="date") */
  protected $date;
  
  /**
   * Buyer constructor.
   */
  public function __construct() {
    $this->date = new \DateTime();
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
  
}