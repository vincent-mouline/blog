<?php

namespace App\Controller;


use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use App\Entity\Category;

/**
 * Class BlogController
 *
 * @package App\Controller
 */
class BlogController extends AbstractController
{

    /**
     * Show all row from article's entity
     *
     * @Route("/blog", name="blog_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/blog/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     * @return Response A response instance
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        $category = $article->getCategory();

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with ' . $slug . ' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
                'category' => $category,
            ]
        );
    }

    /**
     *
     * @Route("/blog/category/{name}", name="show_category")
     * @param category $category
     * @return Response
     */
    public function showByCategory(Category $category)
    {
//        $category = $this->getDoctrine()
//            ->getRepository(Category::class)
//            ->findOneBy(['name' => mb_strtolower($category)]);
        $articles = $category->getArticles();
//

//        $articles = $this->getDoctrine()
//            ->getRepository(Article::class)
//            ->findBy(['category' => $category], ['id' => 'DESC'], 3);


        return $this->render('blog/category.html.twig', [
                'articles' => $articles
            ]
        );
    }

    /**
     * Add category by form
     *
     * @Route("/category", name="category")
     * @param Request $request
     * @return Response A response instance
     */
    public function addCategory(Request $request): Response
    {
        $category = new Category;
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryFormData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoryFormData);
            $entityManager->flush();
            return $this->redirectToRoute('category');


        }

        return $this->render(
            'blog/category.html.twig', [
                'form' => $form->createView(),
                'categories' => $categories,
            ]
        );
    }

}
