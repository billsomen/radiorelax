<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/10/2015
 * Time: 10:32 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Room
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */
//Todo: les quantites des differentes parties d'une maison...
//todo: source - http://www.learnenglish.de/vocabulary/rooms.html
class Room
{
  //Cette classe ne fait que donner le nombre de chaque type de room
  /** @MongoDB\Field(type="integer") */
  protected $attic;
  
  /** @MongoDB\Field(type="integer") */
  protected $ballroom;
  
  /** @MongoDB\Field(type="integer") */
  protected $kitchen;
  
  /** @MongoDB\Field(type="integer") */
  protected $box_room;
  
  /** @MongoDB\Field(type="integer") */
  protected $cellar;
  
  /** @MongoDB\Field(type="integer") */
  protected $cloakroom;
  
  /** @MongoDB\Field(type="integer") */
  protected $conservatory;
//ok
  /** @MongoDB\Field(type="integer") */
  protected $dining_room;
  
  /** @MongoDB\Field(type="integer") */
  protected $drawing_room;
  
  /** @MongoDB\Field(type="integer") */
  protected $games_room;
  
  /** @MongoDB\Field(type="integer") */
  protected $hall;
  
  /** @MongoDB\Field(type="integer") */
  protected $landing;
  
  /** @MongoDB\Field(type="integer") */
  protected $larder;
  
  /** @MongoDB\Field(type="integer") */
  protected $library;
  
  /** @MongoDB\Field(type="integer") */
  protected $music_room;
//ok
  /** @MongoDB\Field(type="integer") */
  protected $office;
  
  /** @MongoDB\Field(type="integer") */
  protected $pantry;
  
  /** @MongoDB\Field(type="integer") */
  protected $parlour;
//ok
  /** @MongoDB\Field(type="integer") */
  protected $living_room;
  
  /** @MongoDB\Field(type="integer") */
  protected $spare_room;
  
  /** @MongoDB\Field(type="integer") */
  protected $bed_room;
  
  /** @MongoDB\Field(type="integer") */
  protected $guest_room;
//ok
  /** @MongoDB\Field(type="integer") */
  protected $toilet;
  
  /** @MongoDB\Field(type="integer") */
  protected $utility_room;
  
  /**
   * Room constructor.
   */
  public function __construct() {
    $this->office = 0;
    $this->kitchen = 0;
    $this->toilet = 0;
    $this->living_room = 0;
    $this->dining_room = 0;
    $this->bed_room = 0;
  }
  
  /**
   * @return mixed
   */
  public function getAttic() {
    return $this->attic;
  }
  
  /**
   * @param mixed $attic
   */
  public function setAttic($attic) {
    $this->attic = $attic;
  }
  
  /**
   * @return mixed
   */
  public function getBallroom() {
    return $this->ballroom;
  }
  
  /**
   * @param mixed $ballroom
   */
  public function setBallroom($ballroom) {
    $this->ballroom = $ballroom;
  }
  
  /**
   * @return mixed
   */
  public function getBoxRoom() {
    return $this->box_room;
  }
  
  /**
   * @param mixed $box_room
   */
  public function setBoxRoom($box_room) {
    $this->box_room = $box_room;
  }
  
  /**
   * @return mixed
   */
  public function getCellar() {
    return $this->cellar;
  }
  
  /**
   * @param mixed $cellar
   */
  public function setCellar($cellar) {
    $this->cellar = $cellar;
  }
  
  /**
   * @return mixed
   */
  public function getCloakroom() {
    return $this->cloakroom;
  }
  
  /**
   * @param mixed $cloakroom
   */
  public function setCloakroom($cloakroom) {
    $this->cloakroom = $cloakroom;
  }
  
  /**
   * @return mixed
   */
  public function getConservatory() {
    return $this->conservatory;
  }
  
  /**
   * @param mixed $conservatory
   */
  public function setConservatory($conservatory) {
    $this->conservatory = $conservatory;
  }
  
  /**
   * @return mixed
   */
  public function getDiningRoom() {
    return $this->dining_room;
  }
  
  /**
   * @param mixed $dining_room
   */
  public function setDiningRoom($dining_room) {
    $this->dining_room = $dining_room;
  }
  
  /**
   * @return mixed
   */
  public function getDrawingRoom() {
    return $this->drawing_room;
  }
  
  /**
   * @param mixed $drawing_room
   */
  public function setDrawingRoom($drawing_room) {
    $this->drawing_room = $drawing_room;
  }
  
  /**
   * @return mixed
   */
  public function getGamesRoom() {
    return $this->games_room;
  }
  
  /**
   * @param mixed $games_room
   */
  public function setGamesRoom($games_room) {
    $this->games_room = $games_room;
  }
  
  /**
   * @return mixed
   */
  public function getHall() {
    return $this->hall;
  }
  
  /**
   * @param mixed $hall
   */
  public function setHall($hall) {
    $this->hall = $hall;
  }
  
  /**
   * @return mixed
   */
  public function getLanding() {
    return $this->landing;
  }
  
  /**
   * @param mixed $landing
   */
  public function setLanding($landing) {
    $this->landing = $landing;
  }
  
  /**
   * @return mixed
   */
  public function getLarder() {
    return $this->larder;
  }
  
  /**
   * @param mixed $larder
   */
  public function setLarder($larder) {
    $this->larder = $larder;
  }
  
  /**
   * @return mixed
   */
  public function getLibrary() {
    return $this->library;
  }
  
  /**
   * @param mixed $library
   */
  public function setLibrary($library) {
    $this->library = $library;
  }
  
  /**
   * @return mixed
   */
  public function getMusicRoom() {
    return $this->music_room;
  }
  
  /**
   * @param mixed $music_room
   */
  public function setMusicRoom($music_room) {
    $this->music_room = $music_room;
  }
  
  /**
   * @return mixed
   */
  public function getOffice() {
    return $this->office;
  }
  
  /**
   * @param mixed $office
   */
  public function setOffice($office) {
    $this->office = $office;
  }
  
  /**
   * @return mixed
   */
  public function getPantry() {
    return $this->pantry;
  }
  
  /**
   * @param mixed $pantry
   */
  public function setPantry($pantry) {
    $this->pantry = $pantry;
  }
  
  /**
   * @return mixed
   */
  public function getParlour() {
    return $this->parlour;
  }
  
  /**
   * @param mixed $parlour
   */
  public function setParlour($parlour) {
    $this->parlour = $parlour;
  }
  
  /**
   * @return mixed
   */
  public function getLivingRoom() {
    return $this->living_room;
  }
  
  /**
   * @param mixed $living_room
   */
  public function setLivingRoom($living_room) {
    $this->living_room = $living_room;
  }
  
  /**
   * @return mixed
   */
  public function getSpareRoom() {
    return $this->spare_room;
  }
  
  /**
   * @param mixed $spare_room
   */
  public function setSpareRoom($spare_room) {
    $this->spare_room = $spare_room;
  }
  
  /**
   * @return mixed
   */
  public function getGuestRoom() {
    return $this->guest_room;
  }
  
  /**
   * @param mixed $guest_room
   */
  public function setGuestRoom($guest_room) {
    $this->guest_room = $guest_room;
  }
  
  /**
   * @return mixed
   */
  public function getToilet() {
    return $this->toilet;
  }
  
  /**
   * @param mixed $toilet
   */
  public function setToilet($toilet) {
    $this->toilet = $toilet;
  }
  
  /**
   * @return mixed
   */
  public function getUtilityRoom() {
    return $this->utility_room;
  }
  
  /**
   * @param mixed $utility_room
   */
  public function setUtilityRoom($utility_room) {
    $this->utility_room = $utility_room;
  }
  
  /**
   * @return mixed
   */
  public function getBedRoom()
  {
    return $this->bed_room;
  }
  
  /**
   * @param mixed $bed_room
   */
  public function setBedRoom($bed_room)
  {
    $this->bed_room = $bed_room;
  }
  
  /**
   * @return mixed
   */
  public function getKitchen()
  {
    return $this->kitchen;
  }
  
  /**
   * @param mixed $kitchen
   */
  public function setKitchen($kitchen)
  {
    $this->kitchen = $kitchen;
  }
}
