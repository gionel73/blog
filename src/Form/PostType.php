<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['attr' => ['class' => 'm-2']])
            ->add('category', ChoiceType::class,[
                'choices' => $options['categories'],
                'choice_value' => function(?Category $category) {
                    return $category ? $category->getId() : '';
                },
                'choice_label' => function(?Category $category) {
                    return $category ? $category->getName() : '';
                },
                'attr' => ['class' => 'm-2']
                ] 
            )
            ->add('description', TextareaType::class, ['attr' => ['class' => 'w-100 mb-2', 'rows' => '10']])
            ->add('author', TextType::class, ['attr' => ['class' => 'm-2']])
            ->add('status', null, ['label' => 'Published' ,'attr' => ['class' => 'm-2']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'categories' => null,
            'category_id' => null
        ]);
    }
}
