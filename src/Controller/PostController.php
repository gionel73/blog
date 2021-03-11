<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/{category_id}", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository, $category_id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $posts = $postRepository->findBy(['category' => $category_id], ['created_at' => 'DESC']);
        $categoryName = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['id' => $category_id]);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'categoryName' => $categoryName,
            'category_id' => $category_id
        ]);
    }

    /**
     * @Route("/new/{category_id}", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request, $category_id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $post = new Post();
        $post->setCategory($this->getDoctrine()->getRepository(Category::class)->findOneBy(['id' => $category_id])); 

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();        

        $form = $this->createForm(PostType::class, $post, ['categories' => $categories]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index', ['category_id' => $category_id]);
        }
        
        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'category_id' => $category_id
        ]);
    }
    
    /**
     * @Route("/{id}/{category_id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post, $category_id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'category_id' => $category_id
        ]);
    }

    /**
     * @Route("/{id}/edit/{category_id}", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post, $category_id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $form = $this->createForm(PostType::class, $post, ['categories' => $categories, 'category_id' => $category_id]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index', ['category_id' => $category_id]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'category_id' => $category_id
        ]);
    }

    /**
     * @Route("/{id}/{category_id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post, $category_id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index', ['category_id' => $category_id]);
    }
}
