<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 3/24/2019
 * Time: 6:49 PM
 */

namespace MainBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Listener
 * @MongoDB\EmbeddedDocument()
 * @package MainBundle\Document
 */

class Listener
{
//  Par défaut, tout le monde peut y accéder, donc pas besoin d'en vérifier l'accès
  /** @MongoDB\ReferenceMany(targetDocument="Album") */
//  Les albums de l'artist
  protected $albums;

  /**
   * Listener constructor.
   */
  public function __construct()
  {
    $this->albums = new ArrayCollection();
  }

  /**
   * @return mixed
   */
  public function getAlbums()
  {
    return $this->albums;
  }

  /**
   * @param mixed $albums
   */
  public function setAlbums($albums): void
  {
    $this->albums = $albums;
  }

}