<?php

namespace App\Form;

use App\Entity\Books;

use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Название книги'])
            ->add('description', TextType::class, ['label' => 'Описание книги'])
            ->add('file', FileType::class, [
                'label' => 'upload cover',
                'data_class' => null,
                'required' => false,
            ])
            ->add('year', NumberType::class, [
                'label' => 'Год публикации книги',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Books::class,
            'require_cover' => false,
        ]);

        $resolver->setAllowedTypes('require_cover', 'bool');
    }
}
