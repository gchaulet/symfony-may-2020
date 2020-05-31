<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends AbstractController
{
    private $session;
    
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/{name}", name="blog_index")
     */
    public function index($name)
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $this->session->get('posts'),
        ]);
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add()
    {
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'A random title '. rand(1, 500),
            'text' => 'Some random text nr '. rand(1,500)
        ];
        $this->session->set('posts', $posts);


    }

    /**
     * @Route("/show/{id}", name="blog_show")
     */
    public function show($id)
    {
        $posts = $this->session->get('posts');
        if(!$posts || !isset($posts[$id])) {
            throw new NotFoundHttpException('Message not found');
        }

        return $this->render('blog/post.html.twig', [
            'id' => $id,
            'post' => $posts[$id]
        ]);

    }
}
