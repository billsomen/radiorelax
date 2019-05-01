<?php

namespace MainBundle\Controller;

use MainBundle\Document\TempUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use XS\UserBundle\Document\Locale;

class DefaultController extends Controller
{
  public function indexAction($_locale)
  {
    $user = $this->getUser();

    if(!empty($user)){
      $locale = $user->getLocale();
      if(empty($locale) or empty($locale->getLanguage())){
        $locale = new Locale();
        $user->setLocale($locale);
        $dm = $this->get("doctrine.odm.mongodb.document_manager");
        $dm->persist($user);
        $dm->flush();
      }
      return $this->redirectToRoute("core_artists_homepage", array(
        "_locale" => $locale->getLanguage()
      ));
    }
    return $this->redirectToRoute("core_artists_homepage");

    $dm = $this->get("doctrine.odm.mongodb.document_manager");
    $user = new TempUser();
    $user->setName('ngongangsomen@gmail.com');
    $dm->persist($user);
    $dm->flush();

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
