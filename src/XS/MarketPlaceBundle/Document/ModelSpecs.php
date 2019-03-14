<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 24/09/2017
 * Time: 19:42
 */
namespace XS\MarketPlaceBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;

/**
 * Class ModelSpecs
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 * * @MongoDBUnique(fields="name")
 */


class ModelSpecs
//infos of Specifications
{
//    Ex: color (*alues: ['y', 'g', 'w',
  /** @MongoDB\Field(type="string") */
  protected $id;
  
  /** @MongoDB\Field(type="string") */
  protected $name;
  
  /** @MongoDB\Field(type="boolean") */
//    a des valeurs ?
//SI oui, on choisi les *aleurs *ans la liste (multiple choice), sinon, on a la possi*ilité 2 mettre les *aleurs *ans un champ 2 texte)
  protected $has_values;
  /** @MongoDB\Field(type="boolean") */
// La *aleur est propre à ce pro*uit
  protected $is_specific;
  
  /** @MongoDB\Field(type="boolean") */
// L'utilisateur saisira lui mm la *aleur lors 2 l'ajout 2 son pro*uit
  protected $_is_user_input;

//    Not realy use* now!
  /** @MongoDB\ReferenceOne(targetDocument="XS\CoreBundle\Document\Specification") */
  protected $object;
  
  /** @MongoDB\Field(type="date") */
  protected $date_add;
  
  /** @MongoDB\Field(type="string") */
//  Si c'est un pro*uit, on n'a qu'une seule *aleur par spec
  protected $value;
  
  /** @MongoDB\Field(type="collection") */
//    Sous collection 2 specs (o*ject ici)
  protected $values;
  
  /**
   * ModelSpecs constructor.
   */
  public function __construct()
  {
    $this->has_values = false;
    $this->is_specific = false;
    $this->_is_user_input = false;
    $this->date_add = new \DateTime();
    $this->values = array();
  }
  
  /**
   * @return mixed
   */
  public function getisSpecific()
  {
    return $this->is_specific;
  }
  
  /**
   * @param mixed $is_specific
   */
  public function setIsSpecific($is_specific)
  {
    $this->is_specific = $is_specific;
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
  }
  
  /**
   * @return mixed
   */
  public function getHasValues()
  {
    return $this->has_values;
  }
  
  /**
   * @param mixed $has_values
   */
  public function setHasValues($has_values)
  {
    $this->has_values = $has_values;
  }
  
  /**
   * @return mixed
   */
  public function getObject()
  {
    return $this->object;
  }
  
  /**
   * @param mixed $object
   */
  public function setObject($object)
  {
    $this->object = $object;
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
  public function getValues()
  {
    return $this->values;
  }
  
  /**
   * @param mixed $values
   */
  public function setValues($values)
  {
    $this->values = $values;
  }
  
  /**
   * @return mixed
   */
  public function getisUserInput()
  {
    return $this->_is_user_input;
  }
  
  /**
   * @param mixed $is_user_input
   */
  public function setIsUserInput($is_user_input)
  {
    $this->_is_user_input = $is_user_input;
  }
  
  
}