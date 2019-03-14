<?php

namespace MainBundle\Twig\Extension;

use RadioRelax\CoreBundle\Document\Album;
use XS\AfrobankBundle\Document\Amount;
use XS\EducationBundle\Document\Classe;
use XS\EducationBundle\Document\School;
use XS\EducationBundle\Document\SchoolYear;
use XS\EducationBundle\Document\SubClasse;
use XS\EducationBundle\Document\Subject;
use XS\MarketPlaceBundle\Document\StandardHouse;

class MainExtension extends \Twig_Extension
{
  const STATUS_COMPLETED = 'completed';
  const STATUS_REJECTED = 'rejected';
  const STATUS_ONGOING = 'ongoing';
  const STATUS_PENDING = 'pending';

  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return 'education';
  }

  public function getFilters(){
    return array(
      new \Twig_SimpleFilter('school_year_format', array($this, 'schoolYearFormat')),
      new \Twig_SimpleFilter('short_number', array($this, 'shortNumber')),
      new \Twig_SimpleFilter('school_link', array($this, 'schoolLinkFilter')),
      new \Twig_SimpleFilter('school_format', array($this, 'schoolFormat')),
      new \Twig_SimpleFilter('amount_format', array($this, 'amountFormat')),
      new \Twig_SimpleFilter('amount_frequency_format', array($this, 'amountFrequencyFormat')),
      new \Twig_SimpleFilter('amount_short_format', array($this, 'amountShortFormat')),
      new \Twig_SimpleFilter('house_room_format', array($this, 'houseRoomFormat')),
      new \Twig_SimpleFilter('classe_format', array($this, 'classeFormat')),
      new \Twig_SimpleFilter('sub_classe_format', array($this, 'subClasseFormat')),
      new \Twig_SimpleFilter('subject_format', array($this, 'subjectFormat')),
      new \Twig_SimpleFilter('status_css', array($this, 'statusCss')),
      new \Twig_SimpleFilter('cloudinary_img', array($this, 'cloudinaryImg')),
      new \Twig_SimpleFilter('cloudinary_file', array($this, 'cloudinaryFile')),
      new \Twig_SimpleFilter('gmaps_address', array($this, 'gmapsAddress')),
      new \Twig_SimpleFilter('duration_format', array($this, 'durationFormat')),
      new \Twig_SimpleFilter('album_list_number_format', array($this, 'albumListNumberFormat')),
      new \Twig_SimpleFilter('album_duration', array($this, 'albumDuration')),
    );
  }

  public function durationFormat($seconds){
//    from duration to formatted duration in custom mdde :)
    $format = "";
    $hours = floor($seconds/3600);
    $minutes = floor($seconds%3600/60);
    $seconds = $seconds-$hours*3600-$minutes*60;

    if($hours > 0){
      if($seconds < 10){
        $seconds = "0".$seconds;
      }
      if($minutes < 10){
        $minutes = "0".$minutes;
      }
      $format = $hours.'h'.$minutes.":".$seconds;
    }
    else{
      if($minutes > 0){
        if($seconds < 10){
          $seconds = "0".$seconds;
        }
        $format = $minutes.":".$seconds;
      }
      else{
        $format = $seconds.'s';
      }
    }

    return $format;
  }

  public function albumDuration(Album $album){
//    Get the total duration of an album from all related songs
    $seconds = 0;
    $format = "";
    foreach ($album->getMusics() as $m){
      $seconds += $m->getDuration();
    }
    return $this->durationFormat($seconds);
  }

  public function albumListNumberFormat($int){
//    from item to two çhars length format
    if($int < 10){
      $int = "0".$int;
    }
    return $int;
  }

  public function shortNumber($n){
    $n_format = 0;
    $suffix = '';
    if ($n > 0 && $n < 1000) {
      // 1 - 999
      $n_format = floor($n);
      $suffix = '';
    } else if ($n >= 1000 && $n < 1000000) {
      // 1k-999k
      $n_format = floor($n / 1000);
      $suffix = 'K+';
    } else if ($n >= 1000000 && $n < 1000000000) {
      // 1m-999m
      $n_format = floor($n / 1000000);
      $suffix = 'M+';
    } else if ($n >= 1000000000 && $n < 1000000000000) {
      // 1b-999b
      $n_format = floor($n / 1000000000);
      $suffix = 'B+';
    } else if ($n >= 1000000000000) {
      // 1t+
      $n_format = floor($n / 1000000000000);
      $suffix = 'T+';
    }
//    return 0;
    return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
  }

  public function schoolLinkFilter(School $school){
    $localisation =  $school->getContacts()->getLocalisation();
    $namespace = $school->getNamespace();
    $link = '/edu/s/'.strtolower($localisation->getCountry().'/'.$localisation->getTown().'/').$namespace;
    return $link;
  }

  public function schoolFormat(School $school){
    $data = $school->getName();
    $localisation =  $school->getGMaps()->getFormattedAddress();
    if(!empty($localisation)){
      $data .= ' - '.$localisation;
    }
    return $data;
  }

  public function statusCss($status){
    $css = 'default';
    switch($status){
      case self::STATUS_COMPLETED:
        $css = 'success';
        break;
      case self::STATUS_REJECTED:
        $css = 'danger';
        break;
    }

    return $css;
  }

  public function schoolYearFormat(SchoolYear $school_year){
    $data = ''.$school_year->getName();
    if(empty($data)){
      $data .= $school_year->getDateStart()->format('m/Y').' - '.$school_year->getDateEnd()->format('m/Y');
    }
    return $data;
  }

  public function houseRoomFormat(StandardHouse $house){
    $text = '';
    if($house->getParking() > 0){
      $text .= $house->getParking().' place';
      if($house->getParking() > 1){
        $text .= 's';
      }
      $text .= ' de parking';
    }

    if($house->getRoom()->getBedRoom() > 0){
      if(!empty($text)){
        $text .= ', ';
      }
      $text .= $house->getRoom()->getBedRoom().' chambre';
      if($house->getRoom()->getBedRoom() > 1){
        $text .= 's';
      }
    }

    if($house->getRoom()->getToilet() > 0){
      if(!empty($text)){
        $text .= ', ';
      }
      $text .= $house->getRoom()->getToilet().' salle';
      if($house->getRoom()->getToilet() > 1){
        $text .= 's';
      }
      $text .= ' de bain';
    }

    if($house->getRoom()->getKitchen() > 0){
      if(!empty($text)){
        $text .= ', ';
      }
      $text .= $house->getRoom()->getKitchen().' cuisine';
      if($house->getRoom()->getKitchen() > 1){
        $text .= 's';
      }
    }

    if($house->getRoom()->getOffice() > 0){
      if(!empty($text)){
        $text .= ', ';
      }
      $text .= $house->getRoom()->getOffice().' bureau';
      if($house->getRoom()->getOffice() > 1){
        $text .= 'x';
      }
    }

    if($house->getRoom()->getDiningRoom() > 0){
      if(!empty($text)){
        $text .= ', ';
      }
      $text .= $house->getRoom()->getDiningRoom().' salon';
      if($house->getRoom()->getDiningRoom() > 1){
        $text .= 's';
      }
    }

    if($house->getRoom()->getDiningRoom() > 0){
      if(!empty($text)){
        $text .= ', ';
      }
      $text .= $house->getRoom()->getDiningRoom().' salle';
      if($house->getRoom()->getDiningRoom() > 1){
        $text .= 's';
      }
      $text .= ' à manger';
    }

    return $text;

  }

  public function amountFormat(Amount $amount, $decimals=2){
    $data =  $amount->getCurrency().' '.number_format($amount->getValue(), $decimals);
    return $data;
  }

  public function amountFrequencyFormat(Amount $amount, $decimals=2){
//    Format the amount and set the frequency too
    $data =  $amount->getCurrency().' '.number_format($amount->getValue(), $decimals).' / '.$amount->getPaymentFrequency();
    return $data;
  }

  public function amountShortFormat(Amount $amount){
    $data =  $amount->getCurrency().' '.$this->shortNumber($amount->getValue());
    return $data;
  }

  public function classeFormat(Classe $classe){
    $data = $classe->getName().' ('.$classe->getShortName().') - '.$classe->getLanguage();
    return $data;
  }

  public function subClasseFormat(SubClasse $sub_classe){
    $classe = $sub_classe->getClasse();
    $data = $classe->getName().' ('.$classe->getShortName().') '.$sub_classe->getSection().' - '.$classe->getLanguage();
    return $data;
  }

  public function subjectFormat(Subject $subject){
    $data = $subject->getName().' ('.$subject->getShortName().')';
    return $data;
  }

  public function cloudinaryImg($public_id, $type='list', $params = array()){
//    Echo the image according to some params
    \Cloudinary::config(array(
      "cloud_name" => "glpi-ifactory",
      "api_key" => "793644589386261",
      "api_secret" => "ImMHuMMK7VMRUjAsXktKuLRCJeg"
    ));


    $options = null;

    switch($type){
      case 'list':
        $options = array(
          "secure"=>true,
          "transformation" => array(
            array(
              "width" => 50,
              "height" => 50,
//          "crop" =>1 "thumb",
//          "gravity" => "face",
              "radius" => 1,
//          "effect"=>"shadow",
//          "x"=>5, "y"=>5,
              "border" => "1px_solid_rgb:3598DC",
//          "effect" => "sepia",
//          "background"=>"white",
              "fetch_format"=>"auto",
              "crop"=>"pad",
              "quality"=>"auto"
            ),
//        array( "angle" => 10 )
          )
        );
        break;
      case 'show':
        $options = array(
          "secure"=>true,
          "transformation" => array(
            array(
              "width" => 240,
              "height" => 240,
              "radius" => 10,
//          "x"=>5, "y"=>5,
//              "border" => "0.5px_solid_rgb:ff4865",
//          "effect" => "sepia",
              "background"=>"rgb:ff4865",
              "fetch_format"=>"auto",
              "crop"=>"pad",
              "quality"=>"auto"
            ),
          )
        );
        break;
    }

    $url = "//res.cloudinary.com/dghcv4vjh/image/upload/c_scale,h_100/v1523025451/cll2csmvlydqwp9v9vhc.png";
    if(empty($public_id)){
      $public_id = "cll2csmvlydqwp9v9vhc";
    }

    try{
      $url = \Cloudinary::cloudinary_url($public_id, $options);
    }catch(\Exception $exception){

    }
    return $url;
  }

  public function cloudinaryFile($public_id, $type="video"){
//    Echo the image according to some params
    \Cloudinary::config(array(
      "cloud_name" => "glpi-ifactory",
      "api_key" => "793644589386261",
      "api_secret" => "ImMHuMMK7VMRUjAsXktKuLRCJeg"
    ));

    $options = array(
      "secure"=>true,
      "resource_type"=>$type,
//      Starts at 10% of the audio :)
      "start_offset"=>"10p",
//      PLay 30seçonds of the audio
      "duration"=>"30"
    );

    $url = null;

    try{
      $url = \Cloudinary::cloudinary_url($public_id, $options);
    }catch(\Exception $exception){

    }
    return $url;
  }

  public function gmapsAddress($gmaps){
//    Show the formatted Address or NONE if none!
    $address = "Aucune Adresse";
    if(isset($gmaps)){
      $address = $gmaps->getFormattedAddress();
    }

    return $address;
  }


}
