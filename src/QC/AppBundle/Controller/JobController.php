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

use QC\AppBundle\Form\Type\CustomerAddressType;

use FOS\RestBundle\Controller\FOSRestController;

class JobController extends FOSRestController
{
    /**
     * @Route("/jobs", name="jobs")
     * @Template("QCAppBundle:Job:list.html.twig")
     */
    public function indexAction()
    {
        /** @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $qb \Doctrine\ORM\QueryBuilder **/
        $qb = $em->createQueryBuilder();

        $query = $em->createQuery('SELECT j.id AS jobId, c.name AS customerName FROM QCAppBundle:Job j LEFT JOIN j.customer c ORDER BY j.id DESC')->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        /** @var $rows Customer[] */
        $rows = ($query->getResult());

        //$customers = $this->getDoctrine()->getRepository('QCAppBundle:Customer')->findAll();
        return array( 'rows'=>$rows);
    }
}