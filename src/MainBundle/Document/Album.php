<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 1/4/2019
 * Time: 10:05 AM
 */

namespace MainBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Album
 * @MongoDB\Document()
 * @package RadioRelax\CoreBundle\Document
 */

class Album extends FilesTemplate
{
  /** @MongoDB\Field(type="boolean") */
//  L'album est çelui de sons sans albums :)
  protected $is_solo;

  /** @MongoDB\ReferenceMany(targetDocument="Music") */
//  ALl the musics of the album
  protected $musics;

  /**
   * Album constructor.
   * @param $name string
   */
  public function __construct($name = null)
  {
    @parent::__construct();
    $this->name = $name;
    $this->musics = new ArrayCollection();
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
  public function isSolo()
  {
    return $this->is_solo;
  }

  /**
   * @param mixed $is_solo
   */
  public function setIsSolo($is_solo): void
  {
    $this->is_solo = $is_solo;
  }

}
