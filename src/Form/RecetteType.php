<?php 

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Ingredients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('temps')
            ->add('description')
            ->add('prix')
            ->add('difficulte')
            ->add('ingredients', EntityType::class, [
                'class' => Ingredients::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                // 'attr' => ['size' => 3]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
