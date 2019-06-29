<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\AuthorControl;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/article")
 */
class ArticleController extends AbstractController
{

    private $authorControl;

    /**
     * ArticleController constructor.
     * @param AuthorControl $authorControl
     */
    public function __construct(AuthorControl $authorControl)
    {
        $this->authorControl = $authorControl;
    }

    /**
     * @Route("/", name="article_index", methods={"GET"})
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAllWithCategories(),
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     * @param Request $request
     * @param Slugify $slugify
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function new(Request $request, Slugify $slugify, \Swift_Mailer $mailer): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $article->setSlug($slugify->generate($article->getTitle()));
            $author = $this->getUser();
            $article->setAuthor($author);
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre article a bien été enregistré !'
            );

            $destination = getenv('MAILTO');
            $sender = getenv('MAILFROM');
            $message = (new \Swift_Message('Un nouvel article vient d\'être publié !'))
                ->setFrom($sender)
                ->setTo($destination)
                ->setBody(
                    $this->renderView(
                        'emails/news.html.twig',
                        ['article' => $article]),
                    'text/html'
                );

            $mailer->send($message);
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     * @param Article $article
     * @return Response
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    public function edit(Request $request, Article $article, Slugify $slugify): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->authorControl->editControl($article)) {

            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $article->setSlug($slugify->generate($article->getTitle()));
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'success',
                    'Votre article à été modifié !'
                );
                return $this->redirectToRoute('article_index', [
                    'id' => $article->getId(),
                ]);
            }
            return $this->render('article/edit.html.twig', [
                'article' => $article,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('blog_index');
        }
    }


    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    public
    function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash(
                'warning',
                'Votre article a été supprimé !'
            );
        }

        return $this->redirectToRoute('article_index');
    }
}
