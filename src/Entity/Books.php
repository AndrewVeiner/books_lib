<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cover = 'default.png';

    /**
     * @ORM\Column(type="integer")
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

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year): self
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

    /**
     * Unmapped property to handle file uploads
     * @ORM\Column(nullable=true)
     */
    private $file;

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        $this->upload();
        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }
        $filename = md5(uniqid())  . '.' . $this->getFile()->guessExtension();

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and target filename as params
        $this->getFile()->move(
            'uploads',
            $filename
        );
        $this->setCover($filename);

        // set the path property to the filename where you've saved the file

        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    /**
     * Lifecycle callback to upload the file to the server.
     */
    public function lifecycleFileUpload(): void
    {
        $this->upload();
    }
}
