<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 12/25/2018
 * Time: 5:39 PM
 */

namespace MainBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
/**
 * Class TempUser
 * @package MainBundle\Document
 * @MongoDB\Document
 * @MongoDBUnique("email")
 */

class TempUser
{
  /** @MongoDB\Id()*/
  protected $id;

  /** @MongoDB\Field(type="string")*/
  protected $name;

  /** @MongoDB\Field(type="string")*/
  protected $type;

  /** @MongoDB\Field(type="string")*/
  protected $email;

  /** @MongoDB\Field(type="string")*/
  protected $link_portfolio;

  /**
   * TempUser constructor.
   * @param $type
   */
  public function __construct($type = "listener")
  {
    $this->type = $type;
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
  public function setName($name): void
  {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * @param mixed $type
   */
  public function setType($type): void
  {
    $this->type = $type;
  }

  /**
   * @return mixed
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param mixed $email
   */
  public function setEmail($email): void
  {
    $this->email = $email;
  }

  /**
   * @return mixed
   */
  public function getLinkPortfolio()
  {
    return $this->link_portfolio;
  }

  /**
   * @param mixed $link_portfolio
   */
  public function setLinkPortfolio($link_portfolio): void
  {
    $this->link_portfolio = $link_portfolio;
  }
}