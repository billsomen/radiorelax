<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/9/2015
 * Time: 2:43 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\CoreBundle\Document\ManagerSystem;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;

/**
 * Class ProductStandard
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\Document
 * @MongoDBUnique(fields="name")
 */

//todo le gros morceau d'aujourd'hui, a consommer sans moderation
class ProductStandard extends ManagerSystem
{
  /** @MongoDB\ReferenceOne(targetDocument="Provider") */
  protected $provider;
  
  /** @MongoDB\Field(type="string") */
  protected $provider_name;
  
  /** @MongoDB\Field(type="string") */
  protected $provider_id;
  
  /** @MongoDB\EmbedMany(targetDocument="ProductStandardModel") */
  protected $models;
  
  /** @MongoDB\Field(type="date") */
//    Date de sortie
  protected $announced_date;
  
  /**
   * ProductStandard constructor.
   */
  public function __construct()
  {
    $this->models = new ArrayCollection();
  }
  
  /**
   * @return mixed
   */
  public function getModels()
  {
    return $this->models;
  }
  
  /**
   * @param mixed $models
   */
  public function setModels($models)
  {
    $this->models = $models;
  }
  
  /**
   * @return mixed
   */
  public function getProvider()
  {
    return $this->provider;
  }
  
  /**
   * @param mixed $provider
   */
  public function setProvider($provider)
  {
    $this->provider = $provider;
  }
  
  /**
   * @return mixed
   */
  public function getProviderName()
  {
    return $this->provider_name;
  }
  
  /**
   * @param mixed $provider_name
   */
  public function setProviderName($provider_name)
  {
    $this->provider_name = $provider_name;
  }
  
  /**
   * @return mixed
   */
  public function getProviderId()
  {
    return $this->provider_id;
  }
  
  /**
   * @param mixed $provider_id
   */
  public function setProviderId($provider_id)
  {
    $this->provider_id = $provider_id;
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
  
}
