<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 23/09/2017
 * Time: 17:09
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProCatSub
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */


class ProCatSub
{
    /** @MongoDB\Field(type="string") */
    protected $val;

    /**
     * @return mixed
     */
    public function getVal()
    {
        return $this->val;
    }

    /**
     * @param mixed $val
     */
    public function setVal($val)
    {
        $this->val = $val;
    }
}