<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Entity\Books;
use App\Form\BooksType;
use App\Repository\BooksRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    /**
     * @Route("/", name="books")
     */
    public function index(BooksRepository $booksRepository, Request $request): Response
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
    public function creat(Request $request)
    {

        $book = new Books();
        $form = $this->createForm(BooksType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('books')['cover'];
            $uploads_directory = $this->getParameter('uploads_directory');
            if ($file != null) {
                $filename = md5(uniqid())  . '.' . $file->guessExtension();
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $book->setCover($filename);
            } else {
                $book->setCover('default.png');
            }

            $authors = $form->get('authors')->getData();
            $AllAuthors = $this->getDoctrine()->getManager()->getRepository(Authors::class)->findAll();

            for ($i = 0; $i < count($authors); $i++) {
                if (in_array((string)$authors[$i], $AllAuthors)) {
                    $key = array_search((string)$authors[$i], $AllAuthors);
                    $book->addAuthor($AllAuthors[$key]);
                    $AllAuthors[$key]->addBooks($book);
                } else {
                    $book->addAuthor($authors[$i]);
                    $authors[$i]->addBooks($book);
                }
            }

//            $book = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($book);
            $em->flush();
            $AllAuthors = $this->getDoctrine()->getManager()->getRepository(Authors::class)->findAll();
            for ($i = 0; $i < count($AllAuthors); $i++) {
                if (count($AllAuthors[$i]->getBooks()) == 0)
                {
                    $this->getDoctrine()->getManager()->remove($AllAuthors[$i]);
                }
            }
            $em->flush();

            return $this->redirectToRoute('books');
        }

        return $this->render('books/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/books/update/{books}", name="update_book", methods={"GET", "POST"})
     */
    public function update(Request $request, Books $books)
    {
        $oldFileNamePath = $request->get('books')->getCover();
        $form = $this->createForm(BooksType::class, $books);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            \Doctrine\Common\Util\Debug::dump($oldFileNamePath);
            $file = $request->files->get('books')['cover'];
            $em = $this->getDoctrine()->getManager();
            if ($file != null) {
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $uploads_directory = $this->getParameter('uploads_directory');
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $books->setCover($filename);

            } else {
                $books->setCover($oldFileNamePath);
            }

            $authors = $form->get('authors')->getData();
            $AllAuthors = $this->getDoctrine()->getManager()->getRepository(Authors::class)->findAll();

            for ($i = 0; $i < count($authors); $i++) {
                if (in_array((string)$authors[$i], $AllAuthors)) {
                    $key = array_search((string)$authors[$i], $AllAuthors);
                    $books->addAuthor($AllAuthors[$key]);
                    $AllAuthors[$key]->addBooks($books);
                } else {
                    $books->addAuthor($authors[$i]);
                    $authors[$i]->addBooks($books);
                }
            }
            $em->flush();

            return $this->redirectToRoute('books');
        }


        return $this->render('books/form.html.twig', [
           'form' => $form->createView()
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

        //$coverFile = $form->get('cover')->getData();
        $file = $form->get('cover')->getData();
        $em = $this->getDoctrine()->getManager();
//        \Doctrine\Common\Util\Debug::dump($file);

        if ($file != null) {
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $uploads_directory = $this->getParameter('uploads_directory');
            $file->move(
                $uploads_directory,
                $filename
            );
            $books->setCover($filename);

        } else {
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
        $authors = $books->GetAuthors();
        for ($i = 0; $i < count($authors); $i++) {
            $authors[$i]->removeBooks($books);
            if (count($authors[$i]->getBooks()) == 0)
            {
                $em->remove($authors[$i]);
            }
        }
        $em->remove($books);
        $em->flush();
        return $this->redirectToRoute('books');
    }




    /**
     * @Route("/generate_book", name="generate_book")
     */
    public function GenerateBook(): Response
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
        \Doctrine\Common\Util\Debug::dump(array_rand($titles, 1));
        $book->setTitle($titles[array_rand($titles, 1)]);
        $book->setYear(rand(1000, 2021));
        $rand = array_rand($descriptions, 70);
        \Doctrine\Common\Util\Debug::dump($rand);
        $random = '';
        foreach ($rand as $r) $random =$random . $descriptions[$r] . ' ';
        $book->setDescription($random);
        $count_authors = rand(0,4);
        $em = $this->getDoctrine()->getManager();
        $AllAuthors = $this->getDoctrine()->getManager()->getRepository(Authors::class)->findAll();

        for ($i = 0; $i < $count_authors; $i++){
            if (in_array((string)$authors_name[array_rand($authors_name, 1)], $AllAuthors)) {
                $key = array_search((string)$authors_name[array_rand($authors_name, 1)], $AllAuthors);
                $book->addAuthor($AllAuthors[$key]);
                $AllAuthors[$key]->addBooks($book);
            } else {
                $author = new Authors();
                $author->setName($authors_name[array_rand($authors_name, 1)]);
                $book->addAuthor($author);
                $author->addBooks($book);
                $em->persist($author);
            }
        }
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('books');
    }

}
