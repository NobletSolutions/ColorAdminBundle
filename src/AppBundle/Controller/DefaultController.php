<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $array  = range(1, 100);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($array, 1, 10);
        // replace this example code with whatever you need
        return $this->render('AppBundle::index.html.twig', array(
            'pagination'=>$pagination,
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
