<?php


namespace MainBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Node
 *
 * @MongoDB\Document()
 * @package MainBundle\Document
 */

class Node
{

  const MAX_LEVEL = 5;

//  nombre maximum d'enfants par noeud
  const LEVEL_MAX_SIZE = 5;

  /** @MongoDB\Id() */
  protected $id;

  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
//  Utitilisateur
  protected $user;

  /** @MongoDB\ReferenceOne(targetDocument="Node") */
//  Id de l'utitilisateur parent
  protected $parent;

  /** @MongoDB\Field(type="integer") */
// Niveau dans le réseaul MLM : 0 -> 4 (on en a 5). Chaque noeud en a 5
  protected $level;

  /** @MongoDB\Field(type="date") */
// Date à laquelle on a ajouté le noeud; généralement par parainage :)
  protected $date_add;

  /** @MongoDB\ReferenceMany(targetDocument="Node") */
//  Id des utilisateurqs qui sont en dessous en de moi
  protected $children;

  /**
   * Node constructor.
   */
  public function __construct($level=-1)
  {
    if($level < self::MAX_LEVEL){
      $this->level = $level;
    }
    else{
      $this->level = -1;
    }
    $this->children = new ArrayCollection();
    $this->date_add = new \DateTime();
  }


  /**
   * @return mixed
   */
  public function getParent()
  {
    return $this->parent;
  }

  /**
   * @param mixed $parent
   */
  public function setParent($parent): void
  {
    $this->parent = $parent;
  }

  /**
   * @return mixed
   */
  public function getLevel()
  {
    return $this->level;
  }

  /**
   * @param mixed $level
   */
  public function setLevel($level): void
  {
    $this->level = $level;
  }

  /**
   * @return mixed
   */
  public function getChildren()
  {
    return $this->children;
  }

  /**
   * @param mixed $children
   */
  public function setChildren($children): void
  {
    $this->children = $children;
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
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param mixed $user
   */
  public function setUser($user): void
  {
    $this->user = $user;
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
  public function setDateAdd($date_add): void
  {
    $this->date_add = $date_add;
  }

  public function addChild(Node $node){
//    Ajout d'un noeud çomme enfant à çelui-çi et définition du parent du noeud
//    vérifier : niveau & taille
    if(empty($node->getParent())){
      if($this->level < self::MAX_LEVEL-1){
        if(count($this->getChildren()) < self::LEVEL_MAX_SIZE){
          $node->setParent($this);
          $this->getChildren()->add($node);
          $node->setLevel($this->getLevel()+1);
          return true;
        }
      }
    }
    return false;
  }

}