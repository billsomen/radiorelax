<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/6/2015
 * Time: 12:12 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\CoreBundle\Document\ManagerSystem;

/**
 * Class PriceList
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

class PriceList extends ManagerSystem
{
    /** @MongoDB\Field(type="float") */
    protected $amount_min; //Quantite min commandee

    /** @MongoDB\Field(type="float") */
    protected $unit_price; //Prix unitaire pour la quantite min commandee

    /**
     * @return mixed
     */
    public function getAmountMin() {
        return $this->amount_min;
    }

    /**
     * @param mixed $amount_min
     */
    public function setAmountMin($amount_min) {
        $this->amount_min = $amount_min;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice() {
        return $this->unit_price;
    }

    /**
     * @param mixed $unit_price
     */
    public function setUnitPrice($unit_price) {
        $this->unit_price = $unit_price;
    }

}
