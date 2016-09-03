<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Title;
use AppBundle\Form\TitleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $productId = $request->query->get('product');

        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('AppBundle:Company')->findAll();

        if (null == $productId){


            $dql = "SELECT p FROM AppBundle:Product p JOIN p.companies c WHERE c.name = '$company_name'";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                5
            );
        } else {
            $dql = "SELECT p FROM AppBundle:Product p JOIN p.companies c WHERE c.name = '$company_name' AND p.id = '$productId'";
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


    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(Request $request)
    {
        return $this->render('AppBundle:Index:admin.html.twig', []);
    }

    /**
     * @Route("/admin/search", name="adminSearch")
     * @Method({"POST"})
     */
    public function SearchAction(Request $request)
    {
        //TODO:check user input
        $em = $this->getDoctrine()->getManager();

        $homepage = $em->getRepository('AppBundle:Title')->findOneByName('homepage');
        $homepageTitle = $homepage->getTitle();

        $companiesList = $em->getRepository('AppBundle:Title')->findOneByName('companiesList');
        $companiesListTitle = $companiesList->getTitle();

        $string = $request->request->get('string');
        $stringArr = parse_url($string);

        if(!array_key_exists('path', $stringArr)){
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }

        $path = $stringArr['path'];
        $path = explode('/', $path);

        foreach ($path as $key => $value) {
            if (empty($value)) {
                unset($path[$key]);
            }}

        if(empty($path)){
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }
        if('companies' == $path[1] && empty($path[2])){
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }

        $companyName = $path[2];
        $company = $em->getRepository('AppBundle:Company')->findOneByName($companyName);

        if(!$company){
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }

        $companyTitle = $company->getTitle();

        if(!array_key_exists('query', $stringArr)) {
            return $this->render('AppBundle:Index:search.html.twig', [
                'homepageTitle' => $homepageTitle,
                'companyListTitle' => $companiesListTitle,
                'companyName' => $companyName,
                'companyTitle' => $companyTitle,
            ]);
        }
        //TODO: don't forget to persist and push

        $query = $stringArr['query'];
        $query = explode('=', $query);

        $productId = $query[1];
        $product = $em->getRepository('AppBundle:Product')->find($productId);
        $productName = $product->getName();

        if(!$product){
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }

        $productTitle = $product->getTitle();

        return $this->render('AppBundle:Index:search.html.twig', [
            'homepageTitle' => $homepageTitle,
            'companyListTitle' => $companiesListTitle,
            'companyName' => $companyName,
            'companyTitle' => $companyTitle,
            'productName' => $productName,
            'productTitle' => $productTitle,
        ]);



    }

    protected function somethingWentWrong($homepageTitle, $companiesListTitle)
    {
        return $this->render('AppBundle:Index:search.html.twig', [
            'homepageTitle' => $homepageTitle,
            'companyListTitle' => $companiesListTitle,
        ]);
    }

    /**
     * @Route("/admin/change", name="adminChange")
     * @Method({"GET", "POST"})
     */
    public function changeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $homepageTitle = $request->request->get('homepageTitle');
        $homepage = $em->getRepository('AppBundle:Title')->findOneByName('homepage');
        $homepage->setTitle($homepageTitle);
        $em->persist($homepage);
        
        $companyListTitle = $request->request->get('companyListTitle');
        $companyList = $em->getRepository('AppBundle:Title')->findOneByName('companiesList');
        $companyList->setTitle($companyListTitle);
        $em->persist($companyList);

        if($request->request->get('companyTitle')){
            $companyTitle = $request->request->get('companyTitle');
            $companyName = $request->request->get('companyName');
            $company = $em->getRepository('AppBundle:Company')->findOneByName($companyName);
            $company->setTitle($companyTitle);
            $em->persist($company);
        }

        if($request->request->get('productTitle')){
            $productTitle = $request->request->get('productTitle');
            $productName = $request->request->get('productName');
            $product = $em->getRepository('AppBundle:Product')->findOneByName($productName);
            $product->setTitle($productTitle);
            $em->persist($product);
        }


        $em->flush();

        return $this->redirectToRoute('admin');
    }
}
