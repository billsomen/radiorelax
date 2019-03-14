<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/10/2015
 * Time: 7:06 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class StandardHardDrive
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */
//todo: La plupart des modeles de sources viennent de : carmudi.cm :)
//todo: Les vraies specs viennet des brands_sites...

class StandardCar extends StandardPhone
{
//ok
    /** @MongoDB\Field(type="string") */
    protected $gender; //todo: Car, Moto, Truck ???

    /** @MongoDB\Field(type="string") */
    protected $type; //todo: de service, utilitaire, etc...
//ok
    /** @MongoDB\Field(type="integer") */
    protected $year; //todo: de service, utilitaire, etc...
//ok
    /** @MongoDB\Field(type="integer") */
    protected $kilometres = 0; //Nombre de kilometres au compteur
//ok
    /** @MongoDB\Field(type="string") */
    protected $transmission; //Automatique ou manuelle ?

    /** @MongoDB\Field(type="string") */
    protected $style; //Luxe, ecologique, familiale, sportive, etc..
//ok
    /** @MongoDB\Field(type="string") */
    protected $fuel; //Type de carburants supportes...

    /** @MongoDB\Field(type="collection") */
    protected $fuels; //Type de carburants supportes...

    /** @MongoDB\Field(type="collection") */
    protected $exteriors; //Camera de recul, radar, dispositif d'attelage, jantes alliages, etc...

    /** @MongoDB\Field(type="collection") */
    protected $interiors; //Interieurs, AM/FM Radio, Air conditionne, Airbag, navigation, etc...

    /** @MongoDB\Field(type="collection") */
    protected $equipements; //Les equipement: miroirs electrique, fermeture automatique porte, regulateur vitesse, alarmes, etc..
//ok
    /** @MongoDB\Field(type="integer") */
    protected $doors; //nombre de portes
//ok
    /** @MongoDB\Field(type="integer") */
    protected $seats; //nombre de sieges
//ok
    /** @MongoDB\Field(type="string") */
    protected $wheel_position; //Position du volant : left or right

    /** @MongoDB\Field(type="string") */
    protected $traction_type; //Type de motricite ; Traction avant, arriere, AWD (All wheels driving)

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
    public function getKilometres() {
        return $this->kilometres;
    }

    /**
     * @param mixed $kilometres
     */
    public function setKilometres($kilometres) {
        $this->kilometres = $kilometres;
    }

    /**
     * @return mixed
     */
    public function getTransmission() {
        return $this->transmission;
    }

    /**
     * @param mixed $transmission
     */
    public function setTransmission($transmission) {
        $this->transmission = $transmission;
    }

    /**
     * @return mixed
     */
    public function getStyle() {
        return $this->style;
    }

    /**
     * @param mixed $style
     */
    public function setStyle($style) {
        $this->style = $style;
    }

    /**
     * @return mixed
     */
    public function getFuels() {
        return $this->fuels;
    }

    /**
     * @param mixed $fuels
     */
    public function setFuels($fuels) {
        $this->fuels = $fuels;
    }

    /**
     * @return mixed
     */
    public function getExteriors() {
        return $this->exteriors;
    }

    /**
     * @param mixed $exteriors
     */
    public function setExteriors($exteriors) {
        $this->exteriors = $exteriors;
    }

    /**
     * @return mixed
     */
    public function getInteriors() {
        return $this->interiors;
    }

    /**
     * @param mixed $interiors
     */
    public function setInteriors($interiors) {
        $this->interiors = $interiors;
    }

    /**
     * @return mixed
     */
    public function getEquipements() {
        return $this->equipements;
    }

    /**
     * @param mixed $equipements
     */
    public function setEquipements($equipements) {
        $this->equipements = $equipements;
    }

    /**
     * @return mixed
     */
    public function getDoors() {
        return $this->doors;
    }

    /**
     * @param mixed $doors
     */
    public function setDoors($doors) {
        $this->doors = $doors;
    }

    /**
     * @return mixed
     */
    public function getSeats() {
        return $this->seats;
    }

    /**
     * @param mixed $seats
     */
    public function setSeats($seats) {
        $this->seats = $seats;
    }

    /**
     * @return mixed
     */
    public function getWheelPosition() {
        return $this->wheel_position;
    }

    /**
     * @param mixed $wheel_position
     */
    public function setWheelPosition($wheel_position) {
        $this->wheel_position = $wheel_position;
    }

    /**
     * @return mixed
     */
    public function getTractionType() {
        return $this->traction_type;
    }

    /**
     * @param mixed $traction_type
     */
    public function setTractionType($traction_type) {
        $this->traction_type = $traction_type;
    }

    public function addFuel($data){
        $this->fuels[] = $data;
    }

    public function addExterior($data){
        $this->exteriors[] = $data;
    }

    public function addInterior($data){
        $this->interiors[] = $data;
    }

    public function addEquipment($data){
        $this->equipements[] = $data;
    }

    public function __construct() {
        //On met le nom de la categorie a Tablette...
        $this->setCategory( new Category());
        $this->getCategory()->setName('car');
        //todo: Le slug est omis pour l'instant...
    }

    /**
     * @return mixed
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year) {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getFuel() {
        return $this->fuel;
    }

    /**
     * @param mixed $fuel
     */
    public function setFuel($fuel) {
        $this->fuel = $fuel;
    }



}
