<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LogoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null, array('label' => 'Название'))
            ->add('image','iphp_file', array('label' => 'Логотип'))
            ->add('submit', 'submit', array('label' => 'Сохранить', 'attr' => array('class' => 'btn-primary')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Logo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_logo';
    }
}
