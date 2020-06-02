<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{
    private $em;
    private $microPostRepository;
    private $formFactory;

    public function __construct(MicroPostRepository $microPostRepository, FormFactoryInterface $formFactory, EntityManagerInterface $em)
    {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->em = $em;
    }
    /**
     * @Route("/", name="micro_post_index")
     */
    public function index()
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $this->microPostRepository->findAll(),
        ]);
    }

     /**
     * @Route("/add", name="micro_post_add")
     */
    public function add(Request $request)
    {
        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($microPost);
            $this->em->flush();

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render('micro_post/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     */
    public function post(MicroPost $post)
    {
      
        return $this->render('micro_post/post.html.twig', [
            'post' => $post
        ]);
    }
}
