<?php

namespace App\Admin;

use App\Entity\Authors;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\NumberType;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
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
        $formMapper->add('authors');
//        $formMapper->add('cover', FileType::class, [ 'data_class' => null ]);
        $formMapper->add('cover', FileType::class, ['required' => false, 'label' => 'Fichier', 'data_class' => null]); // Add picture near this field

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