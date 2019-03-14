<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/10/2015
 * Time: 10:24 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\MarketPlaceBundle\Document\Category;

/**
 * Class StandardHouse
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */
//todo: La plupart des modeles de sources viennent de : lamudi.cm :)
//todo: Les vraies specs viennet des brands_sites...


class StandardHouse
{
  /** @MongoDB\Field(type="string") */
  protected $gender; //todo: En developpement, maison en chantier, apparts, maisons, terrains, local commercial
  
  /** @MongoDB\Field(type="string") */
  protected $builder; //todo: Qui a construit ? Quel BTP : Foma, Djemo, etc...
  
  /** @MongoDB\Field(type="string") */
  protected $designer; //todo: Qui est le designeur
//ok
  /** @MongoDB\Field(type="string") */
  protected $type; //todo: Cf Lamudi->sous_categories, relatif : bureaux, boutiques, details, showroom, +++hotel, cyber, etc...
//ok
  /** @MongoDB\Field(type="integer") */
  protected $surface_total; //Surface totale de la propriete en m2, de toute la propriete
//ok
  /** @MongoDB\Field(type="integer") */
  protected $surface_living; //Surface habitable (de la maison) en m2
  
  /** @MongoDB\Field(type="boolean") */
//  Dans un campus :: Dortoir
  protected $in_school;
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\EducationBundle\Document\School") */
  protected $school;
  
  /** @MongoDB\Field(type="float") */
  protected $distance_school;
  
  /** @MongoDB\EmbedMany(targetDocument="Room") */
  protected $rooms; //Nombres de salles de : bain, chambres, toilettes, etc...
//ok
  
  /** @MongoDB\EmbedOne(targetDocument="Room") */
  protected $room; //Nombres de salles de : bain, chambres, toilettes, etc...
//ok
  /** @MongoDB\Field(type="integer") */
  protected $parking; //Nombre de places de parking
  
  //ok
  
  //Longueur du local
  /** @MongoDB\Field(type="integer") */
  protected $length;
  
//  Largeur
  /** @MongoDB\Field(type="integer") */
  protected $width;
  
//  Hauteur
  /** @MongoDB\Field(type="integer") */
  protected $height;
  
//ok
  /** @MongoDB\Field(type="integer") */
  protected $distance_to_road; //A combien de m de la route ? (m)
//ok
  /** @MongoDB\Field(type="boolean") */
  protected $furnished; //Meublé ?
//ok
  /** @MongoDB\Field(type="string") */
  protected $dimensions_L_l_h; //Dimensions, separees par des x... (Encombrement)
  
  /**
   * @return mixed
   */
  public function getGender() {
    return $this->gender;
  }
  
  /**
   * @param mixed $gender
   */
  public function setGender($gender) {
    $this->gender = $gender;
  }
  
  /**
   * @return mixed
   */
  public function getBuilder() {
    return $this->builder;
  }
  
  /**
   * @param mixed $builder
   */
  public function setBuilder($builder) {
    $this->builder = $builder;
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
  public function getSurfaceTotal() {
    return $this->surface_total;
  }
  
  /**
   * @param mixed $surface_total
   */
  public function setSurfaceTotal($surface_total) {
    $this->surface_total = $surface_total;
  }
  
  /**
   * @return mixed
   */
  public function getSurfaceLiving() {
    return $this->surface_living;
  }
  
  /**
   * @param mixed $surface_living
   */
  public function setSurfaceLiving($surface_living) {
    $this->surface_living = $surface_living;
  }
  
  /**
   * @return mixed
   */
  public function getRooms() {
    return $this->rooms;
  }
  
  /**
   * @param mixed $rooms
   */
  public function setRooms($rooms) {
    $this->rooms = $rooms;
  }
  
  /**
   * @return mixed
   */
  public function getParking() {
    return $this->parking;
  }
  
  /**
   * @param mixed $parking
   */
  public function setParking($parking) {
    $this->parking = $parking;
  }
  
  /**
   * @return mixed
   */
  public function getDistanceToRoad() {
    return $this->distance_to_road;
  }
  
  /**
   * @param mixed $distance_to_road
   */
  public function setDistanceToRoad($distance_to_road) {
    $this->distance_to_road = $distance_to_road;
  }
  
  /**
   * @return mixed
   */
  public function getFurnished() {
    return $this->furnished;
  }
  
  /**
   * @param mixed $furnished
   */
  public function setFurnished($furnished) {
    $this->furnished = $furnished;
  }
  
  /**
   * @return mixed
   */
  public function getDimensionsLLH() {
    return $this->dimensions_L_l_h;
  }
  
  /**
   * @param mixed $dimensions_L_l_h
   */
  public function setDimensionsLLH($dimensions_L_l_h) {
    $this->dimensions_L_l_h = $dimensions_L_l_h;
  }
  
  /**
   * @return mixed
   */
  public function getDesigner() {
    return $this->designer;
  }
  
  /**
   * @param mixed $designer
   */
  public function setDesigner($designer) {
    $this->designer = $designer;
  }
  
  public function __construct()
  {
    $this->rooms = new \Doctrine\Common\Collections\ArrayCollection();
    $this->room = new Room();
    $this->parking = 0;
    $this->length = 0;
    $this->width = 0;
    $this->height = 0;
    $this->distance_to_road = 0;
  }
  
  /**
   * Add room
   *
   * @param Room $room
   */
  public function addRoom(Room $room)
  {
    $this->rooms->add($room);
  }
  
  /**
   * Remove room
   *
   * @param Room $room
   */
  public function removeRoom(Room$room)
  {
    $this->rooms->removeElement($room);
  }
  
  /**
   * @return mixed
   */
  public function getRoom() {
    return $this->room;
  }
  
  /**
   * @param mixed $room
   */
  public function setRoom($room) {
    $this->room = $room;
  }
  
  /**
   * @return mixed
   */
  public function getInSchool()
  {
    return $this->in_school;
  }
  
  /**
   * @param mixed $in_school
   */
  public function setInSchool($in_school)
  {
    $this->in_school = $in_school;
  }
  
  /**
   * @return mixed
   */
  public function getSchool()
  {
    return $this->school;
  }
  
  /**
   * @param mixed $school
   */
  public function setSchool($school)
  {
    $this->school = $school;
  }
  
  /**
   * @return mixed
   */
  public function getDistanceSchool()
  {
    return $this->distance_school;
  }
  
  /**
   * @param mixed $distance_school
   */
  public function setDistanceSchool($distance_school)
  {
    $this->distance_school = $distance_school;
  }
  
  /**
   * @return mixed
   */
  public function getLength()
  {
    return $this->length;
  }
  
  /**
   * @param mixed $length
   */
  public function setLength($length)
  {
    $this->length = $length;
  }
  
  /**
   * @return mixed
   */
  public function getWidth()
  {
    return $this->width;
  }
  
  /**
   * @param mixed $width
   */
  public function setWidth($width)
  {
    $this->width = $width;
  }
  
  /**
   * @return mixed
   */
  public function getHeight()
  {
    return $this->height;
  }
  
  /**
   * @param mixed $height
   */
  public function setHeight($height)
  {
    $this->height = $height;
  }
}
