<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Books;
use App\Form\BooksType;
use App\Repository\AuthorsRepository;
use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    /**
     * @Route("/", name="books")
     */
    public function index(Request $request): Response
    {

        $repository = $this->getDoctrine()->getRepository(Books::class);

        $filters = array_filter($request->query->all(), function($field) {
            return boolval($field);
        });

        $em = $this->getDoctrine()->getManager();
        return $this->render('books/index.html.twig', [
            'controller_name' => 'BooksController',
            'books' => $repository->findBy($filters),
            'filters' => $request->query->all(),
        ]);
    }

    /**
     * @Route("/books/singleView/{id}", name="singleView_books", methods={"GET","POST"}, requirements={"id":"\d+"})
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
    public function creat(Request $request, AuthorsRepository $authorsRepository)
    {

        $book = new Books();
        $form = $this->createForm(BooksType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('books')['file'];

            if ($file == null) {
                $book->setCover('default.png');
            }
            $em = $this->getDoctrine()->getManager();

            $authors_str = $request->get('_authors');
            $authors = explode(',', $authors_str);


            for ($i = 0; $i < count($authors); $i++) {
                $existAuthors = $authorsRepository->findOneBy(['name' => (string)$authors[$i]]);
                if ($existAuthors != null){
                    $book->addAuthor($existAuthors);
                    $em->persist($existAuthors);
                } else {
                    $newAuthor = new Authors();
                    $newAuthor->setName($authors[$i]);
                    $book->addAuthor($newAuthor);
                }
            }

            $em->persist($book);
            $em->flush();
            for ($i = 0; $i < count($authors); $i++) {
                if (count($authorsRepository->findOneBy(['name' => (string)$authors[$i]])->getBooks()) == 0)
                {
                    $this->getDoctrine()->getManager()->remove($authors[$i]);
                }
            }
            $em->flush();
            return $this->redirectToRoute('books');
        }

        return $this->render('books/form.html.twig', [
            'form' => $form->createView(),
            'auth' => ""
        ]);
    }

    /**
     * @Route("/books/update/{books}", name="update_book", methods={"GET", "POST"})
     */
    public function update(Request $request, Books $books, AuthorsRepository $authorsRepository)
    {
        $oldFileNamePath = $request->get('books')->getCover();
        $oldAuthors = $books->GetAuthors();
        $oldAuthorsNameStr = "";
        for ($i = 0; $i < count($oldAuthors); $i++) {
            $oldAuthorsNameStr = $oldAuthorsNameStr .  $oldAuthors[$i]->getName() . ',';
        }

        $form = $this->createForm(BooksType::class, $books);
        $form->handleRequest($request);
        for ($i = 0; $i < count($oldAuthors); $i++) {
            $books->removeAuthor($oldAuthors[$i]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $em = $this->getDoctrine()->getManager();
            if ($file == null) {
                $books->setCover($oldFileNamePath);
            }

            $authors_str = $request->get('_authors');
            $authors = explode(',', $authors_str);
            for ($i = 0; $i < count($authors); $i++) {
                $existAuthors = $authorsRepository->findOneBy(['name' => (string)$authors[$i]]);
                if ($existAuthors != null){
                    $books->addAuthor($existAuthors);
                } else {
                    $newAuthor = new Authors();
                    $newAuthor->setName($authors[$i]);
                    $books->addAuthor($newAuthor);
                }

            }
            $em->flush();

            return $this->redirectToRoute('books');
        }


        return $this->render('books/form.html.twig', [
           'form' => $form->createView(),
            'auth' => $oldAuthorsNameStr,
        ]);
    }

    /**
     * @Route("/books/update_inline/{books}", name="update_inline_book", methods={"POST"})
     */
    public function update_inline(Request $request, Books $books)
    {
        $oldFileNamePath = $request->get('books')->getCover();
        $form = $this->createForm(BooksType::class, $books);
        $form->handleRequest($request);
        $file = $form->get('file')->getData();
        $em = $this->getDoctrine()->getManager();
        if ($file == null) {
            $books->setCover($oldFileNamePath);
        }
        $em->flush();

        return $this->redirectToRoute('books');

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

    /**
     * @Route("/get_more_then_2_authors", name="get_more_then_2_authors")
     */
    public function getMoreThen2Authors(BooksRepository $booksRepository): Response
    {
        return $this->render('books/index.html.twig', [
            'books' => $booksRepository->Get2Authors()
        ]);
    }



    /**
     * @Route("/generate_book", name="generate_book")
     */
    public function GenerateBook(AuthorsRepository $authorsRepository): Response
    {
        $titles = array("451° по Фаренгейту", "Мастер и Маргарита", "Шантарам", "Три товарища", "Цветы для Элджернона", "Портрет Дориана Грея");
        $authors_name = array('Рей Брэдбери', 'Джордж Оруэлл', 'Михаил Булгаков', 'Грегори Дэвид Робертс', 'Эрих Мария Ремарк', 'Дэниел Киз');
        $descriptions = array('Мастер', 'мирового', 'масштаба', 'совмещающий', 'в', 'литературе',
            'несовместимое.', 'Создатель', 'таких', 'ярчайших', 'шедевров', 'как', 'Марсианские',
            'хроники', '451°', 'по', 'Фаренгейту', 'Вино', 'из', 'одуванчиков', 'и', 'так', 'далее',
            'и', 'так', 'далее.', 'Лауреат', 'многочисленных', 'премий.', 'Это', 'Рэй', 'Брэдбери.',
            'Его', 'увлекательные', 'истории', 'Один', 'из', 'самых', 'загадочных', 'и', 'удивительных',
            'романов', 'XX', 'века.', 'Роман', 'Мастер', 'и', 'Маргарита', '-', 'визитная', 'карточка',
            'Михаила', 'Афанасьевича', 'Булгакова.', 'Более', 'десяти', 'лет', 'Булгаков', 'работал', 'над',
            'книгой', 'которая', 'стала', 'его', 'романом-судьбой', 'романом-завещанием.', 'В', 'Мастере', 'и',
            'Маргарите', 'есть', 'все:', 'веселое', 'Трое', 'друзей', '-', 'Робби', 'отчаянный', 'автогонщик',
            'Кестер', 'и', 'последний', 'романтик', 'Ленц', 'прошли', 'Первую', 'мировую', 'войну.', 'Вернувшись',
            'в', 'гражданскую', 'жизнь', 'они', 'основали', 'небольшую', 'автомастерскую.', 'И', 'хотя', 'призраки',
            'прошлого', 'преследуют', 'их', 'они', 'не', 'унывают', '-', 'ведь', 'что', 'может', 'быть', 'лучше',
            'дружбы', 'крепкой', 'и');
        $book = new Books();
        $book->setCover('default.png');
        $book->setTitle($titles[array_rand($titles, 1)]);
        $book->setYear(rand(1000, 2021));
        $rand = array_rand($descriptions, 70);
        $random = '';
        foreach ($rand as $r) $random =$random . $descriptions[$r] . ' ';
        $book->setDescription($random);
        $count_authors = rand(0,4);
        $em = $this->getDoctrine()->getManager();

        for ($i = 0; $i < $count_authors; $i++){
            $author_name_rand = $authors_name[array_rand($authors_name, 1)];
            $existAuthors = $authorsRepository->findOneBy(['name' => $author_name_rand]);
            if ($existAuthors != null){
                $book->addAuthor($existAuthors);
                $existAuthors->addBooks($book);
            } else {
                $author = new Authors();
                $author->setName($author_name_rand);
                $book->addAuthor($author);
                $author->addBooks($book);
                $em->persist($author);
            }
        }
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('books');
    }

    /**
     * @Route("/books-name", name="books_name", methods={"GET", "POST"})
     */
    public function books_name ()
    {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository(Books::class)->findAll();
        $books_name = array();
        for ($i = 0; $i < count($books); $i++) {
            array_push($books_name, $books[$i]->getTitle());
        }


        return new JsonResponse($books_name);

    }
}
