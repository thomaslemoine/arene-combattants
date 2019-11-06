<?php

namespace App\Form;

use App\Entity\Fighter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FighterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('strength')
            ->add('intelligence')
            ->add('pv')
            ->add('created_at')
            ->add('updated_at')
            ->add('killed_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fighter::class,
        ]);
    }
}
