<?php

namespace MainBundle\Controller\Core;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MusicController extends Controller
{
  public function indexAction($name)
  {
    return $this->render('', array('name' => $name));
  }
}
