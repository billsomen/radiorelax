<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 1/20/2019
 * Time: 3:02 PM
 */

namespace MainBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Music
 * @MongoDB\Document()
 * @package RadioRelax\CoreBundle\Document
 */


class Music extends FilesTemplate
{
  /** @MongoDB\ReferenceOne(targetDocument="Album") */
//  ALl the musics of the album
  protected $album;

  /** @MongoDB\Field("string") */
//  Lien vers la sourçe de l'élément
  protected $src;

  /** @MongoDB\Field("string") */
//  Rang de la musique dans l'album
  protected $rank;

  /** @MongoDB\Field("integer") */
//  Le bit_rate de la musique : b/s (bit/seçonde)
  protected $bit_rate;

  /**
   * Music constructor.
   * @param $name string
   */
  public function __construct($name=null)
  {
    @parent::__construct();
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getAlbum()
  {
    return $this->album;
  }

  /**
   * @param mixed $album
   */
  public function setAlbum($album): void
  {
    $this->album = $album;
  }

  /**
   * @return mixed
   */
  public function getSrc()
  {
    return $this->src;
  }

  /**
   * @param mixed $src
   */
  public function setSrc($src): void
  {
    $this->src = $src;
  }

  /**
   * @return mixed
   */
  public function getBitRate()
  {
    return $this->bit_rate;
  }

  /**
   * @param mixed $bit_rate
   */
  public function setBitRate($bit_rate): void
  {
    $this->bit_rate = $bit_rate;
  }

  /**
   * @return mixed
   */
  public function getRank()
  {
    return $this->rank;
  }

  /**
   * @param mixed $rank
   */
  public function setRank($rank): void
  {
    $this->rank = $rank;
  }
}