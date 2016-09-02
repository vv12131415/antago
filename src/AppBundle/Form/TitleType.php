<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 6/6/16
 * Time: 7:32 PM
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TitleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('string', TextType::class, [
                'required'  => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => 'ApiBundle\Entity\Title',
            'csrf_protection'   => false,
            'allow_extra_fields' => true
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Title';
    }
}
