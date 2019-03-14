<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 8/29/2015
 * Time: 12:54 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Opended
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */

//Cette classe defini les duree d'ouvertures, ainsi que les dates...
class Opened
{
  /** @MongoDB\Field(type="string") */
  protected $week; //Ouvertures la semaine : Lun-Vendredi
  
  /** @MongoDB\Field(type="string") */
  protected $week_end; //Ouvertues les week-end
  
  /**
   * Set week
   *
   * @param string $week
   * @return self
   */
  public function setWeek($week)
  {
    $this->week = $week;
    return $this;
  }
  
  /**
   * Get week
   *
   * @return string $week
   */
  public function getWeek()
  {
    return $this->week;
  }
  
  /**
   * Set weekEnd
   *
   * @param string $weekEnd
   * @return self
   */
  public function setWeekEnd($weekEnd)
  {
    $this->week_end = $weekEnd;
    return $this;
  }
  
  /**
   * Get weekEnd
   *
   * @return string $weekEnd
   */
  public function getWeekEnd()
  {
    return $this->week_end;
  }
}
