<?php

namespace App\Form;

use App\Entity\Books;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('cover', null, [
                'label' => 'upload cover',
                'data_class' => null,
            ])
            ->add('year', DateType::class, [
                'label' => 'Год публикации книги',
                'widget' => 'single_text',
                'format' => 'yyyy'
            ]);


        $builder->add('authors', CollectionType::class, [
            'label' => 'sdfsdf книги',
            'entry_type' => AuthorsType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
        ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Books::class,
            'require_cover' => false,
        ]);

        $resolver->setAllowedTypes('require_cover', 'bool');
//        $resolver->setDefaults([
//            'data_class' => Books::class,
//        ]);
    }
}
