<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 23/09/2017
 * Time: 16:25
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UrlDocument
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */


class UrlDocument
{
    //Le nom en App
    /** @MongoDB\Field(type="string") */
    protected $name;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $url;

    /** @MongoDB\Field(type="date") */
    protected $date_add;

    /** @MongoDB\Field(type="date") */
    protected $date_update;

    /**
     * UrlDocument constructor.
     */
    public function __construct()
    {
        $this->date_add = new \DateTime();
        $this->date_update = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setDateUpdate(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $this->setDateUpdate(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getDateAdd()
    {
        return $this->date_add;
    }

    /**
     * @param mixed $date_add
     */
    public function setDateAdd($date_add)
    {
        $this->date_add = $date_add;
    }

    /**
     * @return mixed
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * @param mixed $date_update
     */
    public function setDateUpdate($date_update)
    {
        $this->date_update = $date_update;
    }



}