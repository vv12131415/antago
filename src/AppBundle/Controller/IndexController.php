<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('AppBundle:Company')->findAll();

        $dql = 'SELECT p FROM AppBundle:Product p';
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('AppBundle:Index:index.html.twig', [
            'companies' => $companies,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/companies", name="showCompanies")
     */
    public function companiesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = 'SELECT c FROM AppBundle:Company c';
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('AppBundle:Index:companies.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/companies/{company_name}", name="showCompany")
     */
    public function companyAction(Request $request, $company_name)
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('AppBundle:Company')->findAll();

        $dql = "SELECT p FROM AppBundle:Product p JOIN p.companies c WHERE c.name = '$company_name'";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('AppBundle:Index:company.html.twig', [
            'companies' => $companies,
            'company_name' => $company_name,
            'pagination' => $pagination,
        ]);
    }
}
