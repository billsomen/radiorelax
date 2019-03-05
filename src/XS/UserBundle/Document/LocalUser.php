<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 3/24/2018
 * Time: 6:42 AM
 */

namespace XS\UserBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class LocalUser
 * @package XS\UserBundle\Document
 * @MongoDB\EmbeddedDocument()
 */

class LocalUser
{
//  A class for explicit references to users :)
  /** @MongoDB\ReferenceOne(targetDocument="User") */
// Référence à cet utilisateur
  protected $ref;
  
  /** @MongoDB\Field(type="string") */
// Identifiant
  protected $id;
  
  /** @MongoDB\Field(type="string") */
// Nom de l'utilisateur
  protected $name;
  
  /**
   * @return mixed
   */
  public function getRef()
  {
    return $this->ref;
  }
  
  /**
   * @param mixed $ref
   */
  public function setRef($ref)
  {
    $this->ref = $ref;
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
  
  public function generate(User $user)
  {
//    Generate fields from user :)
    $this->ref = $user;
    $this->id = $user->getId();
    $this->name = $user->getUsername();
  }
}