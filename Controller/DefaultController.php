<?php

namespace Hexim\HeximZcashBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('HeximZcashBundle:Default:index.html.twig');
    }
}
