<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{

    public function index ()
    {
        return new Response('<html><head></head><body><h1>Hello World.</h1></body></html>');
    }
}