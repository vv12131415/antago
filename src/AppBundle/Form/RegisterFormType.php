<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterFormType extends AbstractType
{
    public function getName()
    {
        return 'user_register';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }
}
