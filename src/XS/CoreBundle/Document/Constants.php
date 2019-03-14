<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 10/18/2017
 * Time: 9:21 PM
 */

namespace XS\CoreBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;

/**
 * Class Constants
 * @package XS\CoreBundle\Document
 * @MongoDB\Document
 * @MongoDBUnique("key")
 */


class Constants
{
  /** @MongoDB\Id() */
  protected $id;
  
  /** @MongoDB\Field(type="string")
   */
//  Par_exemple : reg_num_cursor (the current cursor of registered users, to generate their matricule)
  protected $key;
  
  /** @MongoDB\Increment() */
  protected $value;
  
  /** @MongoDB\Field(type="date") */
  protected $date_add;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\CloudOfficeBundle\Document\User") */
  protected $author;
  
  /**
   * Constants constructor.
   */
  public function __construct()
  {
    $this->date_add = new \DateTime();
  }
  
  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }
  
  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  
  /**
   * @return mixed
   */
  public function getKey()
  {
    return $this->key;
  }
  
  /**
   * @param mixed $key
   */
  public function setKey($key)
  {
    $this->key = $key;
  }
  
  /**
   * @return mixed
   */
  public function getValue()
  {
    return $this->value;
  }
  
  /**
   * @param mixed $value
   */
  public function setValue($value)
  {
    $this->value = $value;
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
  public function getAuthor()
  {
    return $this->author;
  }
  
  /**
   * @param mixed $author
   */
  public function setAuthor($author)
  {
    $this->author = $author;
  }

}