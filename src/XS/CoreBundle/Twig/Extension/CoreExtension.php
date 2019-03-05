<?php

namespace XS\CoreBundle\Twig\Extension;

use XS\EducationBundle\Document\Classe;
use XS\EducationBundle\Document\School;
use XS\EducationBundle\Document\SubClasse;
use XS\EducationBundle\Document\Subject;

class CoreExtension extends \Twig_Extension
{
  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return 'core';
  }
  
  public function getFilters(){
    return array(
      new \Twig_SimpleFilter('short_number', array($this, 'shortNumber')),
      new \Twig_SimpleFilter('school_link', array($this, 'schoolLinkFilter')),
      new \Twig_SimpleFilter('school_format', array($this, 'schoolFormat')),
      new \Twig_SimpleFilter('classe_format', array($this, 'classeFormat')),
      new \Twig_SimpleFilter('sub_classe_format', array($this, 'subClasseFormat')),
      new \Twig_SimpleFilter('subject_format', array($this, 'subjectFormat')),
    );
  }
  
  public function shortNumber($n){
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
    return 0;
//    return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
  }
  
  public function schoolLinkFilter(School $school){
    $localisation =  $school->getContacts()->getLocalisation();
    $namespace = $school->getNamespace();
    $link = '/edu/s/'.strtolower($localisation->getCountry().'/'.$localisation->getTown().'/').$namespace;
    return $link;
  }
  
  public function schoolFormat(School $school){
    $localisation =  $school->getContacts()->getLocalisation();
    $data = $school->getName().' - '.$localisation->getCountry().', '.$localisation->getTown().' ('.$localisation->getQuarter().')';
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
}
