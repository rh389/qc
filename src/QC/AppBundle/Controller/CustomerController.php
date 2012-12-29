<?php

namespace QC\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityManager;
use QC\AppBundle\Entity\Customer;

use QC\AppBundle\Form\Type\CustomerType;

use FOS\RestBundle\Controller\FOSRestController;

class CustomerController extends FOSRestController
{
    /**
     * @Route("/customers", name="customers")
     * @Template("QCAppBundle:Customer:list.html.twig")
     */
    public function indexAction()
    {
        $customers = $this->getDoctrine()->getRepository('QCAppBundle:Customer')->findAll();
        return array( 'customers'=>$customers);
    }

    /**
     * @Method("GET")
     * @Route("/customer/{id}", name="customer")
     * @Template("QCAppBundle:Customer:view.html.twig")
     */
    public function viewAction($id)
    {
        //die($this->get('kernel')->getEnvironment());
        $customer = $this->getDoctrine()->getRepository('QCAppBundle:Customer')->find($id);
        $form = $this->createForm(new CustomerType(), $customer);

        return array('customer'=>$customer, 'form'=>$form->createView());
    }

    /**
     * @Method("POST")
     * @Route("/customer/{id}", name="edit_customer", defaults={"_format"="json"})
     */
    public function editAction($id, Request $request){
        /** @var $customer Customer */
        $customer = $this->getDoctrine()->getRepository('QCAppBundle:Customer')->find($id);

        if(!$customer){
            return JsonResponse::create(array(), 404);
        }

        $form = $this->createForm(new CustomerType(), $customer);
        $form->bind($request);

        if ($form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($customer);
            $manager->flush();
            return $this->handleView($this->view(array('success'=>true, 'data'=>$customer)));
        }
        else{
            return JsonResponse::create($form->getErrors(), 400);
        }
    }
}
