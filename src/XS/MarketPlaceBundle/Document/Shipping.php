<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/6/2015
 * Time: 10:47 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\CoreBundle\Document\Localisation;

/**
 * Class Shipping
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

class Shipping
{
    /** @MongoDB\Field(type="date") */
    //Date de livraison
    protected $date_delivery;

    /** @MongoDB\Field(type="date") */
//    ajout
    protected $date;

    /** @MongoDB\EmbedOne(targetDocument="XS\CoreBundle\Document\Localisation") */
    protected $from;

    /** @MongoDB\EmbedOne(targetDocument="XS\CoreBundle\Document\Localisation") */
    protected $to;

    /**
     * Shipping constructor.
     */
    public function __construct()
    {
        $this->date_delivery = new \DateTime();
        $this->date =  new \DateTime();
        $this->from = new Localisation();
        $this->to = new Localisation();
    }

    /**
     * @return mixed
     */
    public function getDateDelivery() {
        return $this->date_delivery;
    }

    /**
     * @param mixed $date_delivery
     */
    public function setDateDelivery($date_delivery) {
        $this->date_delivery = $date_delivery;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }
}
