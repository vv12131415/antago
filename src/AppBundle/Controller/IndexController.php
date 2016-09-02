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

        return $this->render('AppBundle:Index:admin.html.twig', [

        ]);
    }

    /**
     * @Route("/admin/search", name="adminSearch")
     * @Method({"POST"})
     */
    public function SearchAction(Request $request)
    {
        $response = new JsonResponse();

        $em = $this->getDoctrine()->getManager();

        $homepage =

        $string = $request->request->get('string');

        $stringArr = parse_url($string);

        $path = $stringArr['path'];

        $path = explode('/', $path);

        $companyName = $path[2];

        $company = $em->getRepository('AppBundle:Company')->findOneByName($companyName);

        if(!$company){
            return $this->render('AppBundle:Index:search.html.twig', [
                'companyTitle' => $companyTitle,
                'productTitle' => $productTitle,
            ]);
        }
        var_dump($company);die();

        $companyTitle = $company->getTitle();



        if(array_key_exists('query', $stringArr)) {
            $query = $stringArr['query'];

            $query = explode('=', $query);

            $productUrl = $query[1];

            $product = $em->getRepository('AppBundle:Product')->find($productUrl);

            $productTitle = $product->getTitle();

            return $this->render('AppBundle:Index:search.html.twig', [
                'companyTitle' => $companyTitle,
                'productTitle' => $productTitle,
            ]);
        }
        //TODO: don't forget to persist and push

        //var_dump($product);die();


        return $this->render('AppBundle:Index:search.html.twig', [
            'companyTitle' => $companyTitle,
        ]);
    }

    /**
     * @Route("/admin/change", name="adminChange")
     * @Method({"GET", "POST"})
     */
    public function ChangeAction(Request $request)
    {
        $request->request->get('title');
    }
}
