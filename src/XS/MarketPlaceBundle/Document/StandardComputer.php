<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/9/2015
 * Time: 9:15 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class StandardComputer
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */

//todo: Ici le modele des standards pour les laptops. On fait juste un heritage du StandardPhone...
//todo: Ne pas oublier de mettre des contraintes
//Source: pcmag.com

class StandardComputer extends StandardPhone
{
    /** @MongoDB\Field(type="collection") */
    protected $video_card_brands; //Les differentes configurations

    /** @MongoDB\Field(type="string") */
    protected $video_card_brand; //La marque de la carte graphique...

    /** @MongoDB\Field(type="string") */
    protected $processor_type; //Le type de processeur : i3, i5, i7, dual core, m, etc... :) remplace processor_cores

    /** @MongoDB\Field(type="integer") */
    protected $video_card_capacity; //La capacite de la carte graphique en Go :)

    /** @MongoDB\Field(type="collection") */
    protected $video_card_capacities;

    public function __construct() {
        //On met le nom de la categorie a Tablette...
        $this->setCategory( new Category());
        $this->getCategory()->setName('computer');
        //todo: Le slug est omis pour l'instant...
    }

    /**
     * @return mixed
     */
    public function getVideoCardBrands() {
        return $this->video_card_brands;
    }

    /**
     * @param mixed $video_card_brands
     */
    public function setVideoCardBrands($video_card_brands) {
        $this->video_card_brands = $video_card_brands;
    }

    /**
     * @return mixed
     */
    public function getVideoCardCapacities() {
        return $this->video_card_capacities;
    }

    /**
     * @param mixed $video_card_capacitie
     */
    public function setVideoCardCapacities($video_card_capacitie) {
        $this->video_card_capacities = $video_card_capacitie;
    }


    public function addVideoCardBrand($data){
        $this->video_card_brands[] = $data;
    }

    public function addVideoCardCapacity($data){
        $this->video_card_capacities[] = $data;
    }

    /**
     * @return mixed
     */
    public function getVideoCardBrand() {
        return $this->video_card_brand;
    }

    /**
     * @param mixed $video_card_brand
     */
    public function setVideoCardBrand($video_card_brand) {
        $this->video_card_brand = $video_card_brand;
    }

    /**
     * @return mixed
     */
    public function getVideoCardCapacity() {
        return $this->video_card_capacity;
    }

    /**
     * @param mixed $video_card_capacity
     */
    public function setVideoCardCapacity($video_card_capacity) {
        $this->video_card_capacity = $video_card_capacity;
    }

    /**
     * @return mixed
     */
    public function getProcessorType() {
        return $this->processor_type;
    }

    /**
     * @param mixed $processor_type
     */
    public function setProcessorType($processor_type) {
        $this->processor_type = $processor_type;
    }

}
