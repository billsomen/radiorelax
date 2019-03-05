<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 8/24/2015
 * Time: 4:04 PM
 */

namespace XS\UserBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
/**
 * Class Telephone
 * @package XS\UserBundle\Document
 * @MongoDB\EmbeddedDocument
 */

//Ne pas confondre. Il s'agit ici du contact telephonique de l'Utilisateur.
class Telephone
{
  /** @MongoDB\Field(type="string")*/
  protected $country_code;
  
  /** @MongoDB\Field(type="integer")*/
  protected $number;
  
  /** @MongoDB\Field(type="string")*/
  protected $formatted_number;
  
  
  /**
   * @return mixed
   */
  public function getCountryCode() {
    return $this->country_code;
  }
  
  /**
   * @param mixed $country_code
   */
  public function setCountryCode($country_code) {
    $this->country_code = $country_code;
    //On met une fois aj le numero formate
    $this->setFormattedNumber();
  }
  
  /**
   * @return mixed
   */
  public function getNumber() {
    return $this->number;
  }
  
  /**
   * @param mixed $number
   */
  public function setNumber($number) {
    $this->number = $number;
    //On met une fois aj le numero formate
    $this->setFormattedNumber();
  }
  
  public function getFullNumber(){
    return $this->country_code.$this->number;
  }
  
  public function getFormattedNumber(){
    $num = $this->number;
    $formatted = substr($num,0,3)."-".substr($num,3,3)."-".substr($num,6);
    return $formatted;
  }
  
public function getJoinedNumber(){
    $formatted = str_replace('+', '00', $this->country_code).$this->getNumber();
    return $formatted;
  }
  

  public function setFormattedNumber() {
    $this->formatted_number = $this->getFormattedNumber();
  }
  
  public function __construct(){
    $this->country_code = '+1';
  }
  
}