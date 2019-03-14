<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/9/2015
 * Time: 5:24 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class StandardPhone
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */

//todo: Ici le modele des standards pour les telephones...
//todo: Ne pas oublier de mettre des contraintes
//Source: phonearena.com <- Kala-kala...
class StandardPhone
{
    /** @MongoDB\Field(type="string") */
    protected $brand; //Fabriquant ou marque : Blackberry

    /** @MongoDB\Field(type="string") */
    protected $model; //Le modele : Z10

    /** @MongoDB\Field(type="boolean") */
    protected $locked; //Le telephone est bloque ou debloque ?

    /** @MongoDB\Field(type="collection") */
    protected $carriers; //todo: Pour l'instant on laisse d'abord ceci : les carriers - Orange, Verizon, etc...

    /** @MongoDB\Field(type="integer") */
    protected $battery_talk_time; //Duree de vie de la batterie en utilisation, en heures

    /** @MongoDB\Field(type="integer") */
    protected $battery_stand_by_time; //Duree de vie de la batterie en veille

    /** @MongoDB\Field(type="integer") */
    protected $battery_capacity; //Capacite de la batterie, en mAh

    /** @MongoDB\Field(type="boolean") */
    protected $battery_removable; //la batterie est-elle amovible ?

    /** @MongoDB\Field(type="string") */
    protected $device_type; //Smartphone et consort... :)

    /** @MongoDB\Field(type="collection") */
    protected $os; //Les differents OS de notre phone...

    /** @MongoDB\Field(type="string") */
    protected $dimensions; //Dimensions, separees par des x...

    /** @MongoDB\Field(type="integer") */
    protected $weight; //Poids en gramme

    /** @MongoDB\Field(type="string") */
    protected $display_size; //Daille de l'ecran en pouce ou inches

    /** @MongoDB\Field(type="string") */
    protected $display_resolution; //resolution de l'ecran

    /** @MongoDB\Field(type="string") */
    protected $display_touchscreen; //Ecran tactile : technologie, Multi-touch...

    /** @MongoDB\Field(type="float") */
    protected $camera_back; //Camera en MP...Arriere

    /** @MongoDB\Field(type="float") */
    protected $camera_front; //Camera en MP...Avant

    /** @MongoDB\Field(type="string") */
    protected $camera_flash; //Type de flash : null, technologie

    /** @MongoDB\Field(type="string") */
    protected $os_name; //Nom de l'OS

    /** @MongoDB\Field(type="string") */
    protected $os_version; //Version de l'OS

    /** @MongoDB\Field(type="float") */
    protected $processor_frequency; //Frequence du processeur en GHz (ne pas oublier les 1024)

    /** @MongoDB\Field(type="integer") */
    protected $processor_core; //Nombre de coeurs du processeurs (en chiffres ohhhh...)

    /** @MongoDB\Field(type="string") */
    protected $ram; //La RAM de la bete en Go (ne pas oublier les 1024)...

    /** @MongoDB\Field(type="integer") */
    protected $storage_expansion_value; //en Go...Valeur de l'expansion de la ROM (memoire interne)

//    todo: Temporaire, remplacer par la collection plus tard...
    /** @MongoDB\Field(type="integer") */
    protected $storage_internal; //en Go

    /** @MongoDB\Field(type="collection") */
    protected $storage_built_in; //en Go...ROM i6s 64Go, i6s128Go, etc...

    /** @MongoDB\Field(type="collection") */
    protected $storage_expansion_types; //microSD, microSDHC, etc...

    /** @MongoDB\Field(type="collection") */
    protected $technologies; //Technologies supportees : GSM, UMTS, FDD LTE...etc.

    /** @MongoDB\Field(type="string") */
    protected $technology_data; //Donnee technologiques : 4G LTE, etc...

    /** @MongoDB\Field(type="string") */
    protected $connectivity_bluetooth; //Version de Bluetooth MAX (la premiere ici) supportee

    /** @MongoDB\Field(type="string") */
    protected $connectivity_wifi; //Version de Wifi MAX (la premiere ici) supportee

    /** @MongoDB\Field(type="collection") */
    protected $accessories; //Accessoires du telephone : Ecouteur, chargeur, etc...

    /** @MongoDB\Field(type="collection") */
    protected $colors; //Les coloris disponibles pour ce produit...

//todo Pour plus tard, maintenant, on embarke tout xa...    /** @MongoDB\ReferenceOne(targetDocument="Category") */
    /** @MongoDB\EmbedOne(targetDocument="Category") */
    protected $category;//Categorie principale...

    /**
     * StandardPhone constructor.
     */
    public function __construct() {
        //On met le nom de la categorie a Tablette...
        $this->category = new Category();
        $this->category->setName('phone');
        //todo: Le slug est omis pour l'instant...
    }

    //todo: Everythings done...

    /**
     * @return mixed
     */
    public function getBrand() {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand) {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model) {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getLocked() {
        return $this->locked;
    }

    /**
     * @param mixed $locked
     */
    public function setLocked($locked) {
        $this->locked = $locked;
    }

    /**
     * @return mixed
     */
    public function getCarriers() {
        return $this->carriers;
    }

    /**
     * @param mixed $carriers
     */
    public function setCarriers($carriers) {
        $this->carriers = $carriers;
    }

    public function addCarrier($data){
        $this->carriers[] = $data;
    }

    /**
     * @return mixed
     */
    public function getBatteryTalkTime() {
        return $this->battery_talk_time;
    }

    /**
     * @param mixed $battery_talk_time
     */
    public function setBatteryTalkTime($battery_talk_time) {
        $this->battery_talk_time = $battery_talk_time;
    }

    /**
     * @return mixed
     */
    public function getBatteryStandByTime() {
        return $this->battery_stand_by_time;
    }

    /**
     * @param mixed $battery_stand_by_time
     */
    public function setBatteryStandByTime($battery_stand_by_time) {
        $this->battery_stand_by_time = $battery_stand_by_time;
    }

    /**
     * @return mixed
     */
    public function getBatteryCapacity() {
        return $this->battery_capacity;
    }

    /**
     * @param mixed $battery_capacity
     */
    public function setBatteryCapacity($battery_capacity) {
        $this->battery_capacity = $battery_capacity;
    }

    /**
     * @return mixed
     */
    public function getBatteryRemovable() {
        return $this->battery_removable;
    }

    /**
     * @param mixed $battery_removable
     */
    public function setBatteryRemovable($battery_removable) {
        $this->battery_removable = $battery_removable;
    }

    /**
     * @return mixed
     */
    public function getDeviceType() {
        return $this->device_type;
    }

    /**
     * @param mixed $device_type
     */
    public function setDeviceType($device_type) {
        $this->device_type = $device_type;
    }

    /**
     * @return mixed
     */
    public function getOs() {
        return $this->os;
    }

    /**
     * @param mixed $os
     */
    public function setOs($os) {
        $this->os = $os;
    }

    public function addOs($data){
        $this->os[] = $data;
    }

    /**
     * @return mixed
     */
    public function getDimensions() {
        return $this->dimensions;
    }

    /**
     * @param mixed $dimensions
     */
    public function setDimensions($dimensions) {
        $this->dimensions = $dimensions;
    }

    /**
     * @return mixed
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight) {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getDisplaySize() {
        return $this->display_size;
    }

    /**
     * @param mixed $display_size
     */
    public function setDisplaySize($display_size) {
        $this->display_size = $display_size;
    }

    /**
     * @return mixed
     */
    public function getDisplayResolution() {
        return $this->display_resolution;
    }

    /**
     * @param mixed $display_resolution
     */
    public function setDisplayResolution($display_resolution) {
        $this->display_resolution = $display_resolution;
    }

    /**
     * @return mixed
     */
    public function getDisplayTouchscreen() {
        return $this->display_touchscreen;
    }

    /**
     * @param mixed $display_touchscreen
     */
    public function setDisplayTouchscreen($display_touchscreen) {
        $this->display_touchscreen = $display_touchscreen;
    }

    /**
     * @return mixed
     */
    public function getCameraBack() {
        return $this->camera_back;
    }

    /**
     * @param mixed $camera_back
     */
    public function setCameraBack($camera_back) {
        $this->camera_back = $camera_back;
    }

    /**
     * @return mixed
     */
    public function getCameraFront() {
        return $this->camera_front;
    }

    /**
     * @param mixed $camera_front
     */
    public function setCameraFront($camera_front) {
        $this->camera_front = $camera_front;
    }

    /**
     * @return mixed
     */
    public function getCameraFlash() {
        return $this->camera_flash;
    }

    /**
     * @param mixed $camera_flash
     */
    public function setCameraFlash($camera_flash) {
        $this->camera_flash = $camera_flash;
    }

    /**
     * @return mixed
     */
    public function getProcessorFrequency() {
        return $this->processor_frequency;
    }

    /**
     * @param mixed $processor_frequency
     */
    public function setProcessorFrequency($processor_frequency) {
        $this->processor_frequency = $processor_frequency;
    }

    /**
     * @return mixed
     */
    public function getProcessorCore() {
        return $this->processor_core;
    }

    /**
     * @param mixed $processor_core
     */
    public function setProcessorCore($processor_core) {
        $this->processor_core = $processor_core;
    }

    /**
     * @return mixed
     */
    public function getRam() {
        return $this->ram;
    }

    /**
     * @param mixed $ram
     */
    public function setRam($ram) {
        $this->ram = $ram;
    }

    /**
     * @return mixed
     */
    public function getStorageExpansionValue() {
        return $this->storage_expansion_value;
    }

    /**
     * @param mixed $storage_expansion_value
     */
    public function setStorageExpansionValue($storage_expansion_value) {
        $this->storage_expansion_value = $storage_expansion_value;
    }

    /**
     * @return mixed
     */
    public function getStorageExpansionTypes() {
        return $this->storage_expansion_types;
    }

    /**
     * @param mixed $storage_expansion_types
     */
    public function setStorageExpansionTypes($storage_expansion_types) {
        $this->storage_expansion_types = $storage_expansion_types;
    }

    public function addStorageExpansionType($data){
        $this->storage_expansion_types[] = $data;
    }

    /**
     * @return mixed
     */
    public function getTechnologies() {
        return $this->technologies;
    }

    public function addTechnology($data){
        $this->technologies[] = $data;
    }

    /**
     * @param mixed $technologies
     */
    public function setTechnologies($technologies) {
        $this->technologies = $technologies;
    }

    /**
     * @return mixed
     */
    public function getTechnologyData() {
        return $this->technology_data;
    }

    /**
     * @param mixed $technology_data
     */
    public function setTechnologyData($technology_data) {
        $this->technology_data = $technology_data;
    }

    /**
     * @return mixed
     */
    public function getConnectivityBluetooth() {
        return $this->connectivity_bluetooth;
    }

    /**
     * @param mixed $connectivity_bluetooth
     */
    public function setConnectivityBluetooth($connectivity_bluetooth) {
        $this->connectivity_bluetooth = $connectivity_bluetooth;
    }

    /**
     * @return mixed
     */
    public function getConnectivityWifi() {
        return $this->connectivity_wifi;
    }

    /**
     * @param mixed $connectivity_wifi
     */
    public function setConnectivityWifi($connectivity_wifi) {
        $this->connectivity_wifi = $connectivity_wifi;
    }

    /**
     * @return mixed
     */
    public function getAccessories() {
        return $this->accessories;
    }

    /**
     * @param mixed $accessories
     */
    public function setAccessories($accessories) {
        $this->accessories = $accessories;
    }

    public function addAccessory($data){
        $this->accessories[] = $data;
    }

    /**
     * @return mixed
     */
    public function getStorageBuiltIn() {
        return $this->storage_built_in;
    }

    /**
     * @param mixed $storage_built_in
     */
    public function setStorageBuiltIn($storage_built_in) {
        $this->storage_built_in = $storage_built_in;
    }

    public function addStorageBuiltIn($data){
        $this->storage_built_in[] = $data;
    }

    /**
     * @return mixed
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getColors() {
        return $this->colors;
    }

    /**
     * @param mixed $colors
     */
    public function setColors($colors) {
        $this->colors = $colors;
    }

    public function addColor($data){
        $this->colors[] = $data;
    }

    /**
     * @return mixed
     */
    public function getStorageInternal() {
        return $this->storage_internal;
    }

    /**
     * @param mixed $storage_internal
     */
    public function setStorageInternal($storage_internal) {
        $this->storage_internal = $storage_internal;
    }

    /**
     * @return mixed
     */
    public function getOsName() {
        return $this->os_name;
    }

    /**
     * @param mixed $os_name
     */
    public function setOsName($os_name) {
        $this->os_name = $os_name;
    }

    /**
     * @return mixed
     */
    public function getOsVersion() {
        return $this->os_version;
    }

    /**
     * @param mixed $os_version
     */
    public function setOsVersion($os_version) {
        $this->os_version = $os_version;
    }

}
