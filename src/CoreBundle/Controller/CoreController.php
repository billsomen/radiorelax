<?php

namespace RadioRelax\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction($_locale)
    {
        return $this->render('RadioRelaxCoreBundle:Core:artistes.html.twig', array('locale' => $_locale));
    }

    public function artistsAction($name)
    {
        
        return $this->render('RadioRelaxCoreBundle:Core:profile_artistes.html.twig', array('name' => $name));
    }
}
