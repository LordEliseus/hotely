<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Pagination
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $requestStack, $templatePath)
    {
        $this->route = $requestStack->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    /**
     * @return mixed
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @param mixed $templatePath
     * @return Pagination
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     * @return Pagination
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function display(){
        $this->twig->display($this->templatePath,[
            'page' => $this->getCurrentPage(),
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    /**
     * @return false|float
     * @throws Exception
     */
    public function getPages(){
        if (empty($this->entityClass)){
            throw new Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer. Utiliser la méthode setEntityClass()
            de pagination service.");
        }
        // Ex: page = 1 * 10(limite) - 10 = 0
        // Resultat : start = 0
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        return ceil($total / $this->limit);
    }

    /**
     * @return object[]
     * @throws Exception
     */
    public function getData(){
        if (empty($this->entityClass)){
            throw new Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer. Utiliser la méthode setEntityClass()
            de pagination service.");
        }
        // 1 Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        //2 Demander le repository
        $repo = $this->manager->getRepository($this->entityClass);
        return $repo->findBy([], [], $this->limit, $offset);
    }


    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param mixed $entityClass
     * @return Pagination
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return Pagination
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return Pagination
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }
    
}