<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\Title;
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
        $companies = $em->getRepository('AdminBundle:Company')->findAll();
        $title = $em->getRepository('AdminBundle:Title')->findOneByName('homepage');

        $dql = 'SELECT p FROM AdminBundle:Product p';
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('AppBundle:Index:index.html.twig', [
            'title' => $title,
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

        $title = $em->getRepository('AdminBundle:Title')->findOneByName('companiesList');

        $dql = 'SELECT c FROM AdminBundle:Company c';
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('AppBundle:Index:companies.html.twig', [
            'title' => $title,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/companies/{company_name}", name="showCompany")
     */
    public function companyAction(Request $request, $company_name)
    {
        $productId = $request->query->get('product');

        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('AdminBundle:Company')->findAll();

        if (null == $productId) {
            $dql = "SELECT p FROM AdminBundle:Product p JOIN p.companies c WHERE c.name = '$company_name'";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                5
            );
        } else {
            $dql = "SELECT p FROM AdminBundle:Product p JOIN p.companies c WHERE c.name = '$company_name' AND p.id = '$productId'";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                5
            );
        }

        //var_dump($pagination[1]);die();

        return $this->render('AppBundle:Index:company.html.twig', [
            'companies' => $companies,
            'company_name' => $company_name,
            'pagination' => $pagination,
        ]);
    }
}
