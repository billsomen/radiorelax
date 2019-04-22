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
//  Les albums que j'ai açheté en tant qu'auditeur
  protected $albums;

  /** @MongoDB\ReferenceMany(targetDocument="Album") */
//  Les albums aimés par l'auditeur
//Faut pas oublier de mettre des previews dans çette partie ou juste des liens vers les albums dans la vue auditeur :)
  protected $favorites_albums;

  /** @MongoDB\ReferenceMany(targetDocument="Music") */
//  Musiques achetéesz
  protected $musics;

  /** @MongoDB\ReferenceMany(targetDocument="Music") */
//  Musiques favorites
  protected $favorites_musics;

  /**
   * Listener constructor.
   */
  public function __construct()
  {
    $this->albums = new ArrayCollection();
    $this->musics = new ArrayCollection();
    $this->favorites_albums = new ArrayCollection();
    $this->favorites_musics = new ArrayCollection();
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

  public function hasAlbum(Album $album){
    foreach ($this->albums as $album_user){
      if($album->getId() == $album_user->getId()){
        return true;
      }
    }

    return false;
  }

  public function hasAlbumId($album_id){
    foreach ($this->albums as $album_user){
      if($album_id == $album_user->getId()){
        return true;
      }
    }

    return false;
  }

  public function hasFavoriteAlbumId($album_id){
    foreach ($this->favorites_albums as $album_user){
      if($album_id == $album_user->getId()){
        return true;
      }
    }

    return false;
  }

  public function hasFavoriteMusicId($album_id){
    foreach ($this->favorites_musics as $album_user){
      if($album_id == $album_user->getId()){
        return true;
      }
    }

    return false;
  }

  /**
   * @return mixed
   */
  public function getFavoritesAlbums()
  {
    return $this->favorites_albums;
  }

  /**
   * @param mixed $favorites_albums
   */
  public function setFavoritesAlbums($favorites_albums): void
  {
    $this->favorites_albums = $favorites_albums;
  }

  /**
   * @return mixed
   */
  public function getMusics()
  {
    return $this->musics;
  }

  /**
   * @param mixed $musics
   */
  public function setMusics($musics): void
  {
    $this->musics = $musics;
  }

  /**
   * @return mixed
   */
  public function getFavoritesMusics()
  {
    return $this->favorites_musics;
  }

  /**
   * @param mixed $favorites_musics
   */
  public function setFavoritesMusics($favorites_musics): void
  {
    $this->favorites_musics = $favorites_musics;
  }
}