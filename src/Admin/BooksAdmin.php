<?php

namespace App\Admin;

use App\Entity\Authors;

use App\Entity\Books;
use App\Form\AuthorsType;
use Doctrine\ORM\PersistentCollection;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Form\Type\Filter\NumberType;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelHiddenType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Form\Type\ModelTypeList;
use Sonata\Form\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class BooksAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class);
        $formMapper->add('description', TextareaType::class);
        $formMapper->add('year', IntegerType::class);
        $formMapper->add('authors', ModelType::class, [
            'multiple'              => true,
            'expanded'              => false,
            'by_reference' => false,
            'class'                 => Authors::class,
            'property'              => 'name',
        ]);
        $formMapper->add('file', FileType::class, [
            'required' => false,
            'label' => 'upload cover',
            'data_class' => null,
            ]); // Add picture near this field

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
        $datagridMapper->add('description');
        $datagridMapper->add('year');
        $datagridMapper->add('authors');
        $datagridMapper->add('cover');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
        $listMapper->addIdentifier('description');
        $listMapper->addIdentifier('year');
        $listMapper->addIdentifier('cover');
        $listMapper->addIdentifier('authors');
    }
}