<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 3/11/2018
 * Time: 2:28 PM
 */

namespace XS\UserBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\CoreBundle\Document\GMaps;

/**
 * Class Locale
 * @package XS\UserBundle\Document
 * @MongoDB\EmbeddedDocument()
 */

class Locale
{
//  langue
  /** @MongoDB\Field(type="string") */
  protected $language;
  
  //  currency, de type currency plus tard
  /** @MongoDB\Field(type="string") */
  protected $currency;
  
  //  timezone
  /** @MongoDB\Field(type="string") */
  protected $time_zone_id;
  
  //  timezone
  /** @MongoDB\Field(type="string") */
  protected $time_zone_name;
  
//  todo: décallage horaire = dst_offset+raw_offset;
  //  dstOffset->Google TimeZoneAPI
  /** @MongoDB\Field(type="integer") */
  protected $dst_offset;
  
  //  rawOffset->Google TimeZoneAPI
  /** @MongoDB\Field(type="integer") */
  protected $raw_offset;
  
  /** @MongoDB\Field(type="integer") */
//  Taxe locale
  protected $tax;

  /** @MongoDB\EmbedOne(targetDocument="XS\CoreBundle\Document\GMaps") */
//  Localisation
  protected $location;

  
  /**
   * Locale constructor.
   */
  public function __construct()
  {
    $this->time_zone_id = "America/New_York";
    $this->location = new GMaps();
  }
  
  /**
   * @return mixed
   */
  public function getLanguage()
  {
    return $this->language;
  }
  
  /**
   * @param mixed $language
   */
  public function setLanguage($language)
  {
    $this->language = $language;
  }
  
  /**
   * @return mixed
   */
  public function getCurrency()
  {
    return $this->currency;
  }
  
  /**
   * @param mixed $currency
   */
  public function setCurrency($currency)
  {
    $this->currency = $currency;
  }
  
  /**
   * @return mixed
   */
  public function getTimeZoneId()
  {
    return $this->time_zone_id;
  }
  
  /**
   * @param mixed $time_zone_id
   */
  public function setTimeZoneId($time_zone_id)
  {
    $this->time_zone_id = $time_zone_id;
  }
  
  /**
   * @return mixed
   */
  public function getTimeZoneName()
  {
    return $this->time_zone_name;
  }
  
  /**
   * @param mixed $time_zone_name
   */
  public function setTimeZoneName($time_zone_name)
  {
    $this->time_zone_name = $time_zone_name;
  }
  
  /**
   * @return mixed
   */
  public function getDstOffset()
  {
    return $this->dst_offset;
  }
  
  /**
   * @param mixed $dst_offset
   */
  public function setDstOffset($dst_offset)
  {
    $this->dst_offset = $dst_offset;
  }
  
  /**
   * @return mixed
   */
  public function getRawOffset()
  {
    return $this->raw_offset;
  }
  
  /**
   * @param mixed $raw_offset
   */
  public function setRawOffset($raw_offset)
  {
    $this->raw_offset = $raw_offset;
  }

  /**
   * @return mixed
   */
  public function getTax()
  {
    return $this->tax;
  }

  /**
   * @param mixed $tax
   */
  public function setTax($tax): void
  {
    $this->tax = $tax;
  }

  /**
   * @return mixed
   */
  public function getLocation()
  {
    return $this->location;
  }

  /**
   * @param mixed $location
   */
  public function setLocation($location): void
  {
    $this->location = $location;
  }
}