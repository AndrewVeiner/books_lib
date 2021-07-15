<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Books;
use App\Form\AuthorsType;
use App\Repository\BooksRepository;
use Doctrine\Common\Util\Debug;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/authors/create", name="create_only_authors")
     */
    public function create_only_authors(Request $request, BooksRepository $booksRepository): Response
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
            $books_str = $request->get('_books');
            $books = explode(',', $books_str);

            for ($i = 0; $i < count($books); $i++) {
                $existBooks = $booksRepository->findOneBy(['title' => (string)$books[$i]]);
                if ($existBooks != null){
                    $existBooks->addAuthor($authors);
                }

            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('index_authors');
        }

        return $this->render('authors/form.html.twig', [
            'form' => $form->createView(),
            'books' => ""
        ]);
    }

    /**
     * @Route("/authors/update/{books}/{authors}", name="update_authors", methods={"GET", "POST"})
     */
    public function update(Request $request, Books $books, Authors $authors, BooksRepository $booksRepository)
    {

        $booksList = $authors->getBooks();
        $oldBooksTitleStr = "";
        for ($i = 0; $i < count($booksList); $i++) {
            $oldBooksTitleStr = $oldBooksTitleStr .  $booksList[$i]->getTitle() . ',';
        }
        $form = $this->createForm(AuthorsType::class, $authors, [
            'action' => $this->generateUrl('update_authors', [
                'books' => $books->getID(),
                'authors' => $authors->getId()
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $books_str = $request->get('_books');
            $booksNew = explode(',', $books_str);
            $countBooks = count($booksList);
            for ($i = 0; $i < $countBooks ; $i++) {
                $booksList[$i]->removeAuthor($authors);
            }
            for ($i = 0; $i < count($booksNew); $i++) {
                $existBooks = $booksRepository->findOneBy(['title' => (string)$booksNew[$i]]);
                if ($existBooks != null){
                    $existBooks->addAuthor($authors);
                }

            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('books');
        }


        return $this->render('authors/form.html.twig', [
            'form' => $form->createView(),
            'books' => $oldBooksTitleStr
        ]);
    }


    /**
     * @Route("/authors/update/{authors}", name="update_authors_in_list", methods={"GET", "POST"})
     */
    public function update_in_list(Request $request, Authors $authors, BooksRepository $booksRepository)
    {
        $booksList = $authors->getBooks();
        $oldBooksTitleStr = "";
        for ($i = 0; $i < count($booksList); $i++) {
            $oldBooksTitleStr = $oldBooksTitleStr .  $booksList[$i]->getTitle() . ',';
        }



        $form = $this->createForm(AuthorsType::class, $authors, [
            'action' => $this->generateUrl('update_authors_in_list', [
                'authors' => $authors->getId()
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $books_str = $request->get('_books');
            $books = explode(',', $books_str);
            $countBooks = count($booksList);
            for ($i = 0; $i < $countBooks ; $i++) {
                $booksList[$i]->removeAuthor($authors);
            }
            for ($i = 0; $i < count($books); $i++) {
                $existBooks = $booksRepository->findOneBy(['title' => (string)$books[$i]]);
                if ($existBooks != null){
                    $existBooks->addAuthor($authors);
                }

            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('index_authors');
        }


        return $this->render('authors/form.html.twig', [
            'form' => $form->createView(),
            'books' => $oldBooksTitleStr
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


    /**
     * @Route("/authors-name", name="authors_name", methods={"GET", "POST"})
     */
    public function authors_name ()
    {
        $em = $this->getDoctrine()->getManager();
        $authors = $em->getRepository(Authors::class)->findAll();
        $authors_name = array();
        for ($i = 0; $i < count($authors); $i++) {
            array_push($authors_name, $authors[$i]->getName());
        }


        return new JsonResponse($authors_name);

    }


}
