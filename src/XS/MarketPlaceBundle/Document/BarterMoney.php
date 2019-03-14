<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 10/18/2015
 * Time: 3:32 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class BarterMMoney
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

class BarterMoney
{
  /** @MongoDB\Field(type="string") */
  protected $type; //'add' or 'want'... default to add :)
  
  /** @MongoDB\Field(type="string") */
  protected $currency; //La devise utilisee pour ce produit...
  
  /** @MongoDB\Field(type="float") */
  protected $amount;
  
  /**
   * BarterMoney constructor.
   */
  public function __construct() {
    $this->type = 'add';
    $this->currency = 'XAF';
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
  public function getAmount() {
    return $this->amount;
  }
  
  /**
   * @param mixed $amount
   */
  public function setAmount($amount) {
    $this->amount = $amount;
  }
  
  
}