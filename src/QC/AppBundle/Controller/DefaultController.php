<?php

namespace QC\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('QCAppBundle:Default:index.html.twig', array( 'name'=>'Rob'
        ));
    }

    /**
     * @Route("/something")
     * @Template()
     */
    public function somethingAction()
    {
        return $this->render('QCAppBundle:Default:index.html.twig', array( 'name'=>'Rob'
        ));
    }
}
