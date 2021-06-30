<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Books;
use App\Form\AuthorsType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorsController extends AbstractController
{

    /**
     * @Route("/authors", name="index_authors")
     */
    public function index_authors(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $authors = $em->getRepository(Authors::class)->findAll();

        return $this->render('authors/index.html.twig', [
            'controller_name' => 'BooksController',
            'authors' => $authors
        ]);
    }


    /**
     * @Route("/authors/create/{books}", name="create_authors")
     */
    public function create(Request $request, Books $books): Response
    {
        $authors = new Authors();
        $form = $this->createForm(AuthorsType::class, $authors, [
           'action' => $this->generateUrl('create_authors', [
               'books' => $books->getID()
           ]),
           'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author = $form->getData();
            $AllAuthors = $this->getDoctrine()->getManager()->getRepository(Authors::class)->findAll();

            if (in_array((string)$author, $AllAuthors)) {
                $key = array_search((string)$author, $AllAuthors);
                $books->addAuthor($AllAuthors[$key]);
                $AllAuthors[$key]->addBooks($books);
            } else {
                $books->addAuthor($author);
                $author->addBooks($books);
            }


//            $authors = $form->getData();
//            $authors->addBooks($books);
            $em = $this->getDoctrine()->getManager();

//            $em->persist($authors);
            $em->flush();

            return $this->redirectToRoute('singleView_books', ['id' => $books->getId()]);
        }

        return $this->render('authors/form.html.twig', [
            'form' => $form->createView(),
            'books' => $books
        ]);
    }


    /**
     * @Route("/authors/create", name="create_only_authors")
     */
    public function create_only_authors(Request $request): Response
    {
        $authors = new Authors();
        $form = $this->createForm(AuthorsType::class, $authors, [
            'action' => $this->generateUrl('create_only_authors', [
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('index_authors');
        }

        return $this->render('authors/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/authors/update/{books}/{authors}", name="update_authors", methods={"GET", "POST"})
     */
    public function update(Request $request, Books $books, Authors $authors)
    {

        $form = $this->createForm(AuthorsType::class, $authors, [
            'action' => $this->generateUrl('update_authors', [
                'books' => $books->getID(),
                'authors' => $authors->getId()
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('books');
        }


        return $this->render('authors/form.html.twig', [
            'form' => $form->createView(),
            'books' => $books
        ]);
    }


    /**
     * @Route("/authors/update/{authors}", name="update_authors_in_list", methods={"GET", "POST"})
     */
    public function update_in_list(Request $request, Authors $authors)
    {

        $form = $this->createForm(AuthorsType::class, $authors, [
            'action' => $this->generateUrl('update_authors_in_list', [
                'authors' => $authors->getId()
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('index_authors');
        }


        return $this->render('authors/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/authors/update_inline/{books}/{authors}", name="update_inline_authors", methods={"GET", "POST"})
     */
    public function update_inline(Request $request, Books $books, Authors $authors)
    {

        $form = $this->createForm(AuthorsType::class, $authors, [
            'action' => $this->generateUrl('update_authors', [
                'books' => $books->getID(),
                'authors' => $authors->getId()
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('books');

    }


    /**
     * @Route("/authors/delete/{books}/{authors}", name="delete_authors_in_singleView_books")
     */
    public function delete_in_singleView_books(Books $books, Authors $authors): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($authors);
        $em->flush();


        return $this->redirectToRoute('singleView_books', ['id' => $books->getId()]);
    }


    /**
     * @Route("/authors/delete/{authors}", name="delete_authors_in_list_authors")
     */
    public function delete_in_list_authors(Authors $authors): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($authors);
        $em->flush();


        return $this->redirectToRoute('index_authors');
    }


}
