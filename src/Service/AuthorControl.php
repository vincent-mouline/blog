<?php


namespace App\Service;


use App\Entity\Article;
use Symfony\Component\Security\Core\Security;

class AuthorControl
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function editControl(Article $article): bool
    {
        $user = $this->security->getUser()->getUsername();
        if ($article->getAuthor()->getUsername() == $user || $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        } else {
            return false;
        }
    }

}
