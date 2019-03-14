<?php

namespace XS\MarketPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InvoiceController extends Controller
{
  public function indexAction($name)
  {
    return $this->render('', array('name' => $name));
  }
  
  
}
