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
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $dql = 'SELECT p FROM AppBundle:Product p';
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('AppBundle:Index:index.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/{category_name}", name="showCategory")
     */
    public function categoryAction(Request $request, $category_name)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $dql = "SELECT p FROM AppBundle:Product p JOIN p.categories c WHERE c.name = '$category_name'";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('AppBundle:Index:category.html.twig', [
            'categories' => $categories,
            'category_name' => $category_name,
            'pagination' => $pagination,
        ]);
    }
}
