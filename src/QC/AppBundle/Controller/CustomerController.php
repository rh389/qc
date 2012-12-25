<?php

namespace QC\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * @Route("/customers")
     * @Template("QCAppBundle:Customer:list.html.twig")
     */
    public function indexAction()
    {
        return array( 'name'=>'Rob');
    }
}
