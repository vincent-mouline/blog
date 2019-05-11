<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BlogController
 *
 * @package App\Controller
 */
class BlogController extends AbstractController
{

    /**
     * Show index
     *
     * @Route("/blog", name="blog_index")
     * @return         Response
     */
    public function index()
    {
        return $this->render(
            'blog/index.html.twig', [
            'owner' => 'Vinny',
            ]
        );
    }

    /**
     * @Route("/blog/show/{slug}",
     *     name="blog_show",
     *     requirements={"slug" = "[a-z0-9\-]+"},
     *     defaults={"slug"= null}
     * )
     *
     * @param  $slug
     * @return Response
     */
    public function show($slug)
    {
        if (!empty($slug)) {
            $slug = str_replace('-', ' ', $slug);
            $slug = ucwords($slug);
        }

        return $this->render(
            'blog/show.html.twig', [
            'article' => $slug
            ]
        );
    }

}
