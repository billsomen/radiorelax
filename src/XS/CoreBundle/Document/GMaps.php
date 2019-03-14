<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 21/08/2015
 * Time: 09:57
 */


namespace XS\CoreBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class GMaps
 * @package XS\CoreBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class GMaps {
  //Ne pas oublier d'implementer la classe BigMap, qui contiendra les localisations telechargeables de chaque projet,
  // et meme de toute l'application.
  
  /** @MongoDB\Field(type="string") */
  protected $lng;
  
  /** @MongoDB\Field(type="string") */
  protected $lat;
  
  /** @MongoDB\Field(type="string") */
  protected $formatted_address;
  
  /** @MongoDB\Field(type="string") */
  protected $place_id;

//  ln->long_name, sn->short_name
  /** @MongoDB\Field(type="string") */
  protected $sn_locality;
  
  /** @MongoDB\Field(type="string") */
  protected $ln_locality;
  
  /** @MongoDB\Field(type="string") */
  protected $sn_country;
  
  /** @MongoDB\Field(type="string") */
  protected $ln_country;
  
  /** @MongoDB\Field(type="string") */
  protected $sn_route;
  
  /** @MongoDB\Field(type="string") */
  protected $ln_route;
  
  /** @MongoDB\Field(type="string") */
  protected $sn_postal_code;
  
  /** @MongoDB\Field(type="string") */
  protected $ln_postal_code;
  
  /** @MongoDB\Field(type="string") */
  protected $sn_premise;
  
  /** @MongoDB\Field(type="string") */
  protected $ln_premise;
  
  /** @MongoDB\Field(type="string") */
  protected $sn_street_number;
  
  /** @MongoDB\Field(type="string") */
  protected $ln_street_number;

//  State
  /** @MongoDB\Field(type="string") */
  protected $sn_administrative_area_level_1;
  
  /** @MongoDB\Field(type="string") */
  protected $ln_administrative_area_level_1;

//  Region / County
  /** @MongoDB\Field(type="string") */
  protected $sn_administrative_area_level_2;
  
  /** @MongoDB\Field(type="string") */
  protected $ln_administrative_area_level_2;

  /** @MongoDB\Field(type="string") */
  protected $country;

  /** @MongoDB\Field(type="string") */
  protected $town;
  
  function __construct(){
    $this->lat = 45.4946761;
    $this->lng = -73.5644848;
  }
  
  /**
   * @return mixed
   */
  public function getLng()
  {
    return $this->lng;
  }
  
  /**
   * @param mixed $lng
   */
  public function setLng($lng)
  {
    $this->lng = $lng;
  }
  
  /**
   * @return mixed
   */
  public function getLat()
  {
    return $this->lat;
  }
  
  /**
   * @param mixed $lat
   */
  public function setLat($lat)
  {
    $this->lat = $lat;
  }
  
  /**
   * @return mixed
   */
  public function getFormattedAddress()
  {
    return $this->formatted_address;
  }
  
  /**
   * @param mixed $formatted_address
   */
  public function setFormattedAddress($formatted_address)
  {
    $this->formatted_address = $formatted_address;
  }
  
  /**
   * @return mixed
   */
  public function getPlaceId()
  {
    return $this->place_id;
  }
  
  /**
   * @param mixed $place_id
   */
  public function setPlaceId($place_id)
  {
    $this->place_id = $place_id;
  }
  
  /**
   * @return mixed
   */
  public function getSnLocality()
  {
    return $this->sn_locality;
  }
  
  /**
   * @param mixed $sn_locality
   */
  public function setSnLocality($sn_locality)
  {
    $this->sn_locality = $sn_locality;
  }
  
  /**
   * @return mixed
   */
  public function getLnLocality()
  {
    return $this->ln_locality;
  }
  
  /**
   * @param mixed $ln_locality
   */
  public function setLnLocality($ln_locality)
  {
    $this->ln_locality = $ln_locality;
  }
  
  /**
   * @return mixed
   */
  public function getSnCountry()
  {
    return $this->sn_country;
  }
  
  /**
   * @param mixed $sn_country
   */
  public function setSnCountry($sn_country)
  {
    $this->sn_country = $sn_country;
  }
  
  /**
   * @return mixed
   */
  public function getLnCountry()
  {
    return $this->ln_country;
  }
  
  /**
   * @param mixed $ln_country
   */
  public function setLnCountry($ln_country)
  {
    $this->ln_country = $ln_country;
  }
  
  /**
   * @return mixed
   */
  public function getSnRoute()
  {
    return $this->sn_route;
  }
  
  /**
   * @param mixed $sn_route
   */
  public function setSnRoute($sn_route)
  {
    $this->sn_route = $sn_route;
  }
  
  /**
   * @return mixed
   */
  public function getLnRoute()
  {
    return $this->ln_route;
  }
  
  /**
   * @param mixed $ln_route
   */
  public function setLnRoute($ln_route)
  {
    $this->ln_route = $ln_route;
  }
  
  /**
   * @return mixed
   */
  public function getSnPostalCode()
  {
    return $this->sn_postal_code;
  }
  
  /**
   * @param mixed $sn_postal_code
   */
  public function setSnPostalCode($sn_postal_code)
  {
    $this->sn_postal_code = $sn_postal_code;
  }
  
  /**
   * @return mixed
   */
  public function getLnPostalCode()
  {
    return $this->ln_postal_code;
  }
  
  /**
   * @param mixed $ln_postal_code
   */
  public function setLnPostalCode($ln_postal_code)
  {
    $this->ln_postal_code = $ln_postal_code;
  }
  
  /**
   * @return mixed
   */
  public function getSnPremise()
  {
    return $this->sn_premise;
  }
  
  /**
   * @param mixed $sn_premise
   */
  public function setSnPremise($sn_premise)
  {
    $this->sn_premise = $sn_premise;
  }
  
  /**
   * @return mixed
   */
  public function getLnPremise()
  {
    return $this->ln_premise;
  }
  
  /**
   * @param mixed $ln_premise
   */
  public function setLnPremise($ln_premise)
  {
    $this->ln_premise = $ln_premise;
  }
  
  /**
   * @return mixed
   */
  public function getSnStreetNumber()
  {
    return $this->sn_street_number;
  }
  
  /**
   * @param mixed $sn_street_number
   */
  public function setSnStreetNumber($sn_street_number)
  {
    $this->sn_street_number = $sn_street_number;
  }
  
  /**
   * @return mixed
   */
  public function getLnStreetNumber()
  {
    return $this->ln_street_number;
  }
  
  /**
   * @param mixed $ln_street_number
   */
  public function setLnStreetNumber($ln_street_number)
  {
    $this->ln_street_number = $ln_street_number;
  }
  
  /**
   * @return mixed
   */
  public function getSnAdministrativeAreaLevel1()
  {
    return $this->sn_administrative_area_level_1;
  }
  
  /**
   * @param mixed $sn_administrative_area_level_1
   */
  public function setSnAdministrativeAreaLevel1($sn_administrative_area_level_1)
  {
    $this->sn_administrative_area_level_1 = $sn_administrative_area_level_1;
  }
  
  /**
   * @return mixed
   */
  public function getLnAdministrativeAreaLevel1()
  {
    return $this->ln_administrative_area_level_1;
  }
  
  /**
   * @param mixed $ln_administrative_area_level_1
   */
  public function setLnAdministrativeAreaLevel1($ln_administrative_area_level_1)
  {
    $this->ln_administrative_area_level_1 = $ln_administrative_area_level_1;
  }
  
  /**
   * @return mixed
   */
  public function getSnAdministrativeAreaLevel2()
  {
    return $this->sn_administrative_area_level_2;
  }
  
  /**
   * @param mixed $sn_administrative_area_level_2
   */
  public function setSnAdministrativeAreaLevel2($sn_administrative_area_level_2)
  {
    $this->sn_administrative_area_level_2 = $sn_administrative_area_level_2;
  }
  
  /**
   * @return mixed
   */
  public function getLnAdministrativeAreaLevel2()
  {
    return $this->ln_administrative_area_level_2;
  }
  
  /**
   * @param mixed $ln_administrative_area_level_2
   */
  public function setLnAdministrativeAreaLevel2($ln_administrative_area_level_2)
  {
    $this->ln_administrative_area_level_2 = $ln_administrative_area_level_2;
  }
  
  public function getLatLng(){
    return $this->getLat().','.$this->getLng();
  }

  /**
   * @return mixed
   */
  public function getCountry()
  {
    return $this->country;
  }

  /**
   * @param mixed $country
   */
  public function setCountry($country): void
  {
    $this->country = $country;
  }

  /**
   * @return mixed
   */
  public function getTown()
  {
    return $this->town;
  }

  /**
   * @param mixed $town
   */
  public function setTown($town): void
  {
    $this->town = $town;
  }
}
