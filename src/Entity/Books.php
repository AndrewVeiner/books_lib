<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Books
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
    private $title;

//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={ "image/jpeg" , "image/png" , "image/tiff" , "image/svg+xml"})
     */
    private $cover;

    /**
     * @ORM\Column(type="date")
     */
    private $year;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Authors", mappedBy="books", cascade={"persist", "remove"})
     * @OrderBy({"id" = "DESC"})
     */
    private $authors;

    /**
     * Books constructor
     */
    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

//    public function getAuthor(): ?string
//    {
//        return $this->authors;
//    }
//
//    public function setAuthor(string $authors): self
//    {
//        $this->authors = $authors;
//
//        return $this;
//    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCover()
    {
        return $this->cover;
    }

    public function setCover($cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Add author
     *
     *
     * @return Books
     */
    public function addAuthor(Authors $authors)
    {
        $this->authors[] = $authors;
        return $this;
    }

    /**
     * Remove author
     *
     * @param \App\Entity\Authors $authors
     */
    public function removeAuthor(Authors $authors)
    {
        $this->authors->removeElement($authors);
    }

    /**
     * Get author
     *
     * @return  \Doctrine\Common\Collections\Collection
     */
    public function GetAuthors(): \Doctrine\Common\Collections\Collection
    {
        return $this->authors;
    }

    public function __toString()
    { return $this->title; }
}
