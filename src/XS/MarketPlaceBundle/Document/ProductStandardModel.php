<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 24/09/2017
 * Time: 19:12
 */

namespace XS\MarketPlaceBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use XS\CoreBundle\Document\EditionBase;

/**
 * Class ProductStandardModel
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 * * @MongoDBUnique(fields="name")
 */


class ProductStandardModel extends EditionBase
{
    /** @MongoDB\Field(type="date") */
//    Date de sortie
    protected $announced_date;

    /** @MongoDB\Field(type="float") */
//    Prix de sortie in US*
    protected $launching_price;

    /** @MongoDB\EmbedMany(targetDocument="ModelSpecs") */
//    Spécifications disponibles pour ce mo*èle
    protected $specs;

    /**
     * ProductStandardModel constructor.
     */
    public function __construct()
    {
        $this->specs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getAnnouncedDate()
    {
        return $this->announced_date;
    }

    /**
     * @param mixed $announced_date
     */
    public function setAnnouncedDate($announced_date)
    {
        $this->announced_date = $announced_date;
    }

    /**
     * @return mixed
     */
    public function getLaunchingPrice()
    {
        return $this->launching_price;
    }

    /**
     * @param mixed $launching_price
     */
    public function setLaunchingPrice($launching_price)
    {
        $this->launching_price = $launching_price;
    }

    /**
     * @return mixed
     */
    public function getSpecs()
    {
        return $this->specs;
    }

    /**
     * @param mixed $specs
     */
    public function setSpecs($specs)
    {
        $this->specs = $specs;
    }

}