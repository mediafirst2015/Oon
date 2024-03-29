<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'Email','attr' => ['placeholder' => 'Email']))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'пароли не совпадают',
                'first_name'      => 'pass',
                'second_name'     => 'confirm',
                'first_options'  => array('label' => 'Пароль','attr' => ['placeholder' => 'Пароль']),
                'second_options' => array('label' => 'Повторите пароль','attr' => ['placeholder' => 'Повторите пароль']),))
            ->add('lastName', null, array('label' => 'Фамилия','attr' => ['placeholder' => 'Фамилия']))
            ->add('firstName', null, array('label' => 'Имя','attr' => ['placeholder' => 'Имя']))
            ->add('surName', null, array('label' => 'Отчество','attr' => ['placeholder' => 'Отчество']))
            ->add('company', null, array('label' => 'Название организации','attr' => ['placeholder' => 'Название организации']))
            ->add('phone', null, array('label' => 'телефон','attr' => ['placeholder' => 'Телефон для связи']))
            ->add('submit', 'submit', array('label' => 'Сохранить'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_user';
    }
}
