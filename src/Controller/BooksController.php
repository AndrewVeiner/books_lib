<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Books;
use App\Form\BooksType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    /**
     * @Route("/", name="books")
     */
    public function index(): Response
    {   $em = $this->getDoctrine()->getManager();

        $books = $em->getRepository(Books::class)->findAll();

        return $this->render('books/index.html.twig', [
            'controller_name' => 'BooksController',
            'books' => $books
        ]);
    }

    /**
     * @Route("/books/singleView/{books}", name="singleView_books")
     */
    public function singleView(Books $books)
    {
        return $this->render('books/singleView.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/books/create", name="create_books")
     */
    public function creat(Request $request)
    {

        $book = new Books();
        $form = $this->createForm(BooksType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            echo "<prep>";
            $file = $request->files->get('books')['cover'];
            $uploads_directory = $this->getParameter('uploads_directory');

            $filename = md5(uniqid())  . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            //_route,_controller,_route_params,_firewall_context
            $book->setCover($filename);
            $authors = $form->get('authors')->getData();
            for ($i = 0; $i < count($authors); $i++) {
                $book->addAuthor($authors[$i]);
                $authors[$i]->addBook($book);
            }
            //$string_version = implode(',', $authors);

            $book = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('books');
        }

        return $this->render('books/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/books/update/{books}", name="update_book")
     */
    public function update(Request $request, Books $books)
    {
        $form = $this->createForm(BooksType::class, $books, [
            'action' => $this->generateUrl('update_book', [
                'books' => $books->getId()
            ]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $books = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('books');
        }

        return $this->render('books/form.html.twig', [
           'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/books/delete/{books}", name="delete_book")
     */
    public function delete(Books $books)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($books);
        $em->flush();

        return $this->redirectToRoute('books');
    }

}
