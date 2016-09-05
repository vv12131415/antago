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
        $companies = $em->getRepository('AdminBundle:Company')->findAll();
        $homepage = $em->getRepository('AdminBundle:Title')->findOneByName('homepage');

        $title = $homepage->getTitle();

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

        $homepage = $em->getRepository('AdminBundle:Title')->findOneByName('homepage');
        $homepageTitle = $homepage->getTitle();

        if (!empty($homepageTitle)) {
            $title = $homepageTitle;
        }

        $companiesList = $em->getRepository('AdminBundle:Title')->findOneByName('companiesList');

        $companiesListTitle = $companiesList->getTitle();

        if (!empty($companiesListTitle)) {
            $title = $companiesListTitle;
        }

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
        $companiesAll = $em->getRepository('AdminBundle:Company')->findAll();

        $homepage = $em->getRepository('AdminBundle:Title')->findOneByName('homepage');
        $homepageTitle = $homepage->getTitle();

        if (!empty($homepageTitle)) {
            $title = $homepageTitle;
        }

        $companiesList = $em->getRepository('AdminBundle:Title')->findOneByName('companiesList');

        $companiesListTitle = $companiesList->getTitle();

        if (!empty($companiesListTitle)) {
            $title = $companiesListTitle;
        }

        $company = $em->getRepository('AdminBundle:Company')->findOneByName($company_name);

        $companyTitle = $company->getTitle();

        if (!empty($companyTitle)) {
            $title = $companyTitle;
        }

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
            $product = $em->getRepository('AdminBundle:Product')->find($productId);
            $productTitle = $product->getTitle();

            if (!empty($productTitle)) {
                $title = $productTitle;
            }

            $dql = "SELECT p FROM AdminBundle:Product p JOIN p.companies c WHERE c.name = '$company_name' AND p.id = '$productId'";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                5
            );
        }

        return $this->render('AppBundle:Index:company.html.twig', [
            'companies' => $companiesAll,
            'company_name' => $company_name,
            'pagination' => $pagination,
            'title' => $title,
        ]);
    }
}
