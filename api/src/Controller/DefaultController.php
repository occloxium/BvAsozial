<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/test-mark", name="mark")
     */
    public function index ()
    {
        return $this->render('base.html.twig');
    }
}