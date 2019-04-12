<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/20/2015
 * Time: 7:00 AM
 */

namespace XS\AfrobankBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\CoreBundle\Document\ManagerSystem;

/**
 * Class Amount
 * @package XS\AfrobankBundle\Document
 * @MongoDB\EmbeddedDocument()
 */

class Amount
{
  const PAY_ONCE = "once";
  const PAY_YEAR = "year";
  const PAY_MONTH = "month";
  const PAY_WEEK = "week";
  const PAY_DAY = "day";
  const PAY_HOUR = "hour";
  const CURRENCY_USD = "USD";
  const CURRENCY_CAD = "CAD";

  /** @MongoDB\Field(type="float") */
  protected $value; //Valeur
  
  /** @MongoDB\Field(type="date") */
  protected $date_add; //Quand as-t'on ajoute cela ?
  
  /** @MongoDB\Field(type="string") */
  protected $currency; //Devise utilisee... Pour l'instant on est dans le XAF, et c'est apres qu'on va utiliser les API pour remplir notre BD de currencies
  
  /** @MongoDB\Field(type="string") */
//    Fréquence de paiement
  protected $payment_frequency;
  
  public function __construct($value=0, $currency=self::CURRENCY_CAD, $payment_frequency=self::PAY_ONCE){
    $this->currency = $currency;
    $this->value = $value;
    $this->payment_frequency = $payment_frequency;
    $this->setDateAdd(new \DateTime());
  }
  
  /**
   * @return mixed
   */
  public function getValue() {
    return $this->value;
  }
  
  /**
   * @param mixed $value
   */
  public function setValue($value) {
    $this->value = $value;
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
  
  public function getFormatted(){
    return $this->currency." ".$this->value;
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
  
  public function isEqual(Amount $amount){
//    Check if this amount is equal to this other in params
    return ($this->value == $amount->getValue() AND $this->currency == $amount->getCurrency());
  }
  
  public function hasIdCurrency(Amount $amount){
//    Check if the Amount ha*e the same currency. Useful to make arithmetical operations
    return $this->currency == $amount->getCurrency();
  }
  
  public function diff(Amount $amount, $update=false){
//    Return the real difference of two amounts
    $diff = 0;
    if($this->hasIdCurrency($amount)){
      $diff = $this->getValue() - $amount->getValue();
      if($update){
        $this->setValue($diff);
      }
    }
    
    return $diff;
  }
  
  public function diffAmount(Amount $amount, $update=false){
//    Return an Amount of difference of Amounts
    $diff = $this->diff($amount, $update);
    $diffAmount = new Amount($diff);
    $diffAmount->setCurrency($this->getCurrency());
    
    return $diffAmount;
  }
  
  public function add(Amount $amount, $update=false){
//    Return the real addition of two amounts
    $add = 0;
    if($this->hasIdCurrency($amount)){
      $add = $this->getValue() + $amount->getValue();
      if($update){
        $this->setValue($add);
      }
    }
    
    return $add;
  }
  public function addOnceAmount(Amount $amount, $update=false){
//    Return the real addition of two amounts, with once pediod as Payment frequency
    $add = 0;
    if($this->hasIdCurrency($amount)){
      $add = $this->getValue() + $amount->getValue();
      if($update){
        $this->setValue($add);
      }
    }
    
    return $add;
  }
  
  public function addNumber($number, $update=false){
//    Return the real addition of two amounts
    $add = $this->getValue() + $number;
    if($update){
      $this->setValue($add);
    }
    
    return $add;
  }
  
  public function addAmount(Amount $amount, $update=false){
//    Return an Amount of addition of Amounts
    $add = $this->add($amount, $update);
    $addAmount = new Amount($add);
    $addAmount->setCurrency($this->getCurrency());
    
    return $addAmount;
  }
  
  public function mult(Amount $amount, $update=false){
//    Return the real multiplication of two amounts
    $mult = 0;
    if($this->hasIdCurrency($amount)){
      $mult = $this->getValue() * $amount->getValue();
      if($update){
        $this->setValue($mult);
      }
    }
    
    return $mult;
  }
  
  public function multNumber($number, $update=false){
//    Return the real multiplication of two amounts
    $mult = $this->getValue() * $number;
    if($update){
      $this->setValue($mult);
    }
    return $mult;
  }
  
  public function multAmount(Amount $amount, $update=false){
//    Return an Amount of multiplication of Amounts
    $mult = $this->mult($amount, $update);
    $multAmount = new Amount($mult);
    $multAmount->setCurrency($this->getCurrency());
    
    return $multAmount;
  }
  
  public function multNumberAmount($number, $update=false){
//    Return an Amount of multiplication of this Amount with a num*er
    $mult = $this->multNumber($number, $update);
    $multAmount = new Amount($mult);
    $multAmount->setCurrency($this->getCurrency());
    
    return $multAmount;
  }
  
  public function div(Amount $amount, $update=false){
//    Return the real division of two amounts
    $div = 0;
    if($this->hasIdCurrency($amount)){
      if($amount->getValue() != 0){
        $div = $this->getValue() / $amount->getValue();
        if($update){
          $this->setValue($div);
        }
      }
    }
    
    return $div;
  }
  
  public function divAmount(Amount $amount, $update=false){
//    Return an Amount of division of Amounts
    $div = $this->div($amount, $update);
    $divAmount = new Amount($div);
    $divAmount->setCurrency($this->getCurrency());
    
    return $divAmount;
  }
  
  
  /**
   * @return mixed
   */
  public function getPaymentFrequency()
  {
    return $this->payment_frequency;
  }
  
  /**
   * @param mixed $payment_frequency
   */
  public function setPaymentFrequency($payment_frequency)
  {
    $this->payment_frequency = $payment_frequency;
  }
  
  public function convertTo($payment_frequency = self::PAY_MONTH, $currency = self::CURRENCY_CAD){
//    On ne gère pas encore les currencies et on retourne tout en MOIS :)
    switch($this->payment_frequency){
      case self::PAY_ONCE:
        $this->value = -1;
        break;
      case self::PAY_HOUR:
        $this->value = $this->value*24*30;
        break;
      case self::PAY_DAY:
        $this->value =  $this->value*30;
        break;
      case self::PAY_WEEK:
        $this->value =  $this->value*4;
        break;
      case self::PAY_YEAR:
        $this->value =  $this->value*1/12;
        break;
      default:
//        $this->value = $this->value;
        break;
    }
  }
}