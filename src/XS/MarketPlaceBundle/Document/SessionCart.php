<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 3/12/2018
 * Time: 5:23 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\AfrobankBundle\Document\Amount;
use XS\CoreBundle\Document\CalendarEntry;

/**
 * Class SessionCart
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */


class SessionCart
{
  //Pour l'instant on ne copie que le ProductBarter...
  /** @MongoDB\Field(type="boolean") */
  protected $bought;
  
  /** @MongoDB\Field(type="date") */
  protected $date_add;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
//  Montant unitaire annoncé (ici en heures)
  protected $amount;
  
  /** @MongoDB\Field(type="string") */
  protected $name;
  
  /** @MongoDB\Field(type="string") */
  protected $description;
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $tutor;
  
  /** @MongoDB\Field(type="string") */
  protected $tutor_name;
  
  /** @MongoDB\Field(type="date") */
  protected $date_from;
  
  /** @MongoDB\Field(type="date") */
  protected $date_to;
  
  /** @MongoDB\Field(type="date") */
//  Date à laquelle on a payé cette sessions
  protected $date_paid;
  
  /** @MongoDB\Field(type="string") */
  protected $tutor_id;
  
  /** @MongoDB\Field(type="string") */
//  Image utilisée pour représenter cette session
  protected $profile_url;
  
  /** @MongoDB\Field(type="string") */
  protected $session_id;
  
  /** @MongoDB\Field(type="integer") */
//  Durée en minutes
  protected $duration;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
//  Montant total HT, ici en ONCe
  protected $total_amount;
  
  /**
   * SessionCart constructor.
   */
  public function __construct()
  {
    $this->date_add = new \DateTime();
    $this->amount = new Amount();
    $this->amount->setPaymentFrequency(Amount::PAY_HOUR);
    $this->total_amount = new Amount();
    $this->total_amount->setPaymentFrequency(Amount::PAY_ONCE);
    $this->duration = 0;
  }
  
  /**
   * @return mixed
   */
  public function getBought()
  {
    return $this->bought;
  }
  
  public function isBought()
  {
    return $this->bought;
  }
  
  /**
   * @param mixed $bought
   */
  public function setBought($bought)
  {
    $this->bought = $bought;
  }
  
   /**
   * @param mixed $bought
   */
  public function makePaid()
  {
    $this->bought = true;
    $this->date_paid = new \DateTime();
  }
  
  /**
   * @return mixed
   */
  public function getDateAdd()
  {
    return $this->date_add;
  }
  
  /**
   * @param mixed $date_add
   */
  public function setDateAdd($date_add)
  {
    $this->date_add = $date_add;
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
    $this->updateTotalAmount();
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
  public function getDescription()
  {
    return $this->description;
  }
  
  /**
   * @param mixed $description
   */
  public function setDescription($description)
  {
    $this->description = $description;
  }
  
  /**
   * @return mixed
   */
  public function getTutor()
  {
    return $this->tutor;
  }
  
  /**
   * @param mixed $tutor
   */
  public function setTutor(\XS\UserBundle\Document\User $tutor)
  {
    $this->tutor = $tutor;
    $this->tutor_id = $tutor->getId();
    $this->tutor_name = $tutor->getNickname();
  }
  
  /**
   * @return mixed
   */
  public function getTutorName()
  {
    return $this->tutor_name;
  }
  
  /**
   * @param mixed $tutor_name
   */
  public function setTutorName($tutor_name)
  {
    $this->tutor_name = $tutor_name;
  }
  
  /**
   * @return mixed
   */
  public function getTutorId()
  {
    return $this->tutor_id;
  }
  
  /**
   * @param mixed $tutor_id
   */
  public function setTutorId($tutor_id)
  {
    $this->tutor_id = $tutor_id;
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
  public function getSessionId()
  {
    return $this->session_id;
  }
  
  /**
   * @param mixed $session_id
   */
  public function setSessionId($session_id)
  {
    $this->session_id = $session_id;
  }
  
  /**
   * @return mixed
   */
  public function getDateFrom()
  {
    return $this->date_from;
  }
  
  /**
   * @param mixed $date_from
   */
  public function setDateFrom($date_from)
  {
    $this->date_from = $date_from;
    $this->updateDuration();
  }
  
  /**
   * @return mixed
   */
  public function getDateTo()
  {
    return $this->date_to;
  }
  
  /**
   * @param mixed $date_to
   */
  public function setDateTo($date_to)
  {
    $this->date_to = $date_to;
    $this->updateDuration();
  }
  
  public function setSession(\XS\UserBundle\Document\User $tutor, CalendarEntry $session){
    $this->setSessionId($session->getId());
    $this->setDescription($session->getDescription());
    $this->setName($session->getName());
    $this->setTutor($tutor);
    $this->setDateFrom($session->getDateFrom());
    $this->setDateTo($session->getDateTo());
    $this->setAmount($session->getAccessPrice());
    $this->updateDuration();
  }
  
  /**
   * @return mixed
   */
  public function getDuration()
  {
    return $this->duration;
  }
  
  /**
   * @param mixed $duration
   */
  public function setDuration($duration)
  {
    $this->duration = $duration;
  }
  
  /**
   * @return mixed
   */
  public function getTotalAmount()
  {
    return $this->total_amount;
  }
  
  /**
   * @param mixed $total_amount
   */
  public function setTotalAmount($total_amount)
  {
    $this->total_amount = $total_amount;
  }
  
  public function updateDuration(){
//    La durée est en minutes
    if(!empty($this->getDateFrom()) and !empty($this->getDateTo())){
      $this->duration = ($this->getDateTo()->getTimestamp() - $this->getDateFrom()->getTimestamp())/60;
    }
    else{
      $this->duration = 0;
    }
  }
  
  public function updateTotalAmount(){
    $this->updateDuration();
//    On remet tout en heures (durée)
    $this->total_amount->setPaymentFrequency(Amount::PAY_ONCE);
    $this->total_amount = $this->getAmount()->multNumberAmount($this->duration/60);
  }
  
  /**
   * @return mixed
   */
  public function getDatePaid()
  {
    return $this->date_paid;
  }
  
  /**
   * @param mixed $date_paid
   */
  public function setDatePaid($date_paid)
  {
    $this->date_paid = $date_paid;
  }
}
