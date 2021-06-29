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
            $authors = $form->getData();
            $authors->addBook($books);
            $em = $this->getDoctrine()->getManager();

            $em->persist($authors);
            $em->flush();

            return $this->redirectToRoute('singleView_books', ['id' => $books->getId()]);
        }

        return $this->render('authors/form.html.twig', [
            'form' => $form->createView(),
            'books' => $books
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
     * @Route("/authors/delete/{books}/{authors}", name="delete_authors")
     */
    public function delete(Books $books, Authors $authors): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($authors);
        $em->flush();


        return $this->redirectToRoute('singleView_books', ['id' => $books->getId()]);
    }


}
