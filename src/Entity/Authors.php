<?php

namespace App\Entity;

use App\Repository\AuthorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;


/**
 * @ORM\Entity(repositoryClass=AuthorsRepository::class)
 */
class Authors
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Books", inversedBy="authors")
     */
    private $books;


    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBook(): ArrayCollection
    {
        return $this->books;
    }

//    /**
//     * @return Collection|Books[]
//     */
//    public function getBooks(): Collection
//    {
//        return $this->books;
//    }

    public function addBook(Books $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
        }

        return $this;
    }

    public function removeBook(Books $book): self
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
        }

        return $this;
    }

    public function __toString()
    { return $this->name; }

}
