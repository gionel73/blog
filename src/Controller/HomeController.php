<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Post;
use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(['status' => 1], ['created_at' => 'DESC'], 6, 0);

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'posts' => $posts,
            'home' => true
        ]);
    }

    /**
     * @Route("/home/{id}/{?category_id}", name="home_post_show", methods={"GET"})
     */
    public function show(Post $post, $category_id = null): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('home/show.html.twig', [
            'post' => $post,
            'categories' => $categories,
            'category_id' => $category_id,
            'home' => true
        ]);
    }

    /**
     * @Route("/home/{page<[1-9]\d*>}/{category_id}", name="home_posts_list", methods={"GET"})
     */
    public function list(int $page, int $category_id, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Post::class)->getPostsByCategoryId($category_id);
        $pagination = $paginator->paginate(
            $queryBuilder,
            $page,
            3
        );

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $categoryName = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['id' => $category_id]);
        
        return $this->render('home/list.html.twig', [
            'pagination' => $pagination,
            'categories' => $categories,
            'category_id' => $category_id,
            'categoryName' => $categoryName,
            'home' => true
        ]);
    }


}
