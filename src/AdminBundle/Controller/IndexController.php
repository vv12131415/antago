<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(Request $request)
    {
        return $this->render('AdminBundle:Index:admin.html.twig', []);
    }

    /**
     * @Route("/admin/search", name="adminSearch")
     * @Method({"POST"})
     */
    public function SearchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $homepage = $em->getRepository('AdminBundle:Title')->findOneByName('homepage');
        $homepageTitle = $homepage->getTitle();

        $companiesList = $em->getRepository('AdminBundle:Title')->findOneByName('companiesList');
        $companiesListTitle = $companiesList->getTitle();

        $string = $request->request->get('string');
        $stringArr = parse_url($string);

        if (!array_key_exists('path', $stringArr)) {
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }

        $path = $stringArr['path'];
        $path = explode('/', $path);

        foreach ($path as $key => $value) {
            if (empty($value)) {
                unset($path[$key]);
            }
        }

        if (empty($path)) {
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }
        if ('companies' == $path[1] && empty($path[2])) {
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }

        $companyName = $path[2];
        $company = $em->getRepository('AdminBundle:Company')->findOneByName($companyName);

        if (!$company) {
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }

        $companyTitle = $company->getTitle();

        if (!array_key_exists('query', $stringArr)) {
            return $this->render('AdminBundle:Index:search.html.twig', [
                'homepageTitle' => $homepageTitle,
                'companyListTitle' => $companiesListTitle,
                'companyName' => $companyName,
                'companyTitle' => $companyTitle,
            ]);
        }

        $query = $stringArr['query'];
        $query = explode('=', $query);

        $productId = $query[1];
        $product = $em->getRepository('AdminBundle:Product')->find($productId);
        $productName = $product->getName();

        if (!$product) {
            return $this->somethingWentWrong($homepageTitle, $companiesListTitle);
        }

        $productTitle = $product->getTitle();

        return $this->render('AdminBundle:Index:search.html.twig', [
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
        $this->addFlash(
            'warning',
            'Something went wrong. Did you put right/full URL? (Only homepage and companies list titles is available to change)'
        );

        return $this->render('AdminBundle:Index:search.html.twig', [
            'homepageTitle' => $homepageTitle,
            'companyListTitle' => $companiesListTitle,
        ]);
    }

    /**
     * @Route("/admin/change", name="adminChange")
     * @Method({"POST"})
     */
    public function changeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $homepageTitle = $request->request->get('homepageTitle');
        $homepage = $em->getRepository('AdminBundle:Title')->findOneByName('homepage');
        $homepage->setTitle($homepageTitle);
        $em->persist($homepage);

        $companyListTitle = $request->request->get('companyListTitle');
        $companyList = $em->getRepository('AdminBundle:Title')->findOneByName('companiesList');
        $companyList->setTitle($companyListTitle);
        $em->persist($companyList);

        if ($request->request->get('companyTitle')) {
            $companyTitle = $request->request->get('companyTitle');
            $companyName = $request->request->get('companyName');
            $company = $em->getRepository('AdminBundle:Company')->findOneByName($companyName);
            $company->setTitle($companyTitle);
            $em->persist($company);
        }

        if ($request->request->get('productTitle')) {
            $productTitle = $request->request->get('productTitle');
            $productName = $request->request->get('productName');
            $product = $em->getRepository('AdminBundle:Product')->findOneByName($productName);
            $product->setTitle($productTitle);
            $em->persist($product);
        }

        $em->flush();

        return $this->redirectToRoute('admin');
    }
}
