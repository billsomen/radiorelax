<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class DefaultController extends Controller
{
  public function indexAction($_locale)
  {
    if($_locale == "en_EN"){
      $_locale = "en";
      return $this->redirectToRoute("main_homepage", array(
        '_locale' => $_locale
      ));
    }
    $kernel = $this->get('kernel');
    $path = $kernel->locateResource('@MainBundle/Resources/translations/messages.en.yml');
    $translations = Yaml::parse(file_get_contents($path));
    return $this->render('MainBundle:Default:index.html.twig', array(
      'locale' => $_locale,
      'translations' => $translations
    ));
  }
}
