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
            ->add('username', null, array('label' => 'Email', 'attr' => array('placeholder' => 'Email') ))
            ->add('lastName', null, array('label' => 'Фамилия', 'attr' => array('placeholder' => 'Фамилия*')))
            ->add('firstName', null, array('label' => 'Имя', 'attr' => array('placeholder' => 'Имя*')))
            ->add('surName', null, array('label' => 'Отчество', 'attr' => array('placeholder' => 'Отчество')))
            ->add('company', null, array('label' => 'Название организации', 'attr' => array('placeholder' => 'Название организации')))
            ->add('phone', null, array('label' => 'Контактный телефон', 'attr' => array('placeholder' => 'Телефон*')))
            ->add('hot', null, array('label' => 'Подписаться на горячие предложения'))
            ->add('password', 'repeated', array(
                'first_name' => 'pass',
                'second_name' => 'confirm',
                'type' => 'password',
                'invalid_message' => 'пароли не совпадают',
                'first_options'  => array('label' => 'Пароль', 'attr' => array('placeholder' => 'Пароль*')),
                'second_options' => array('label' => 'Повторите пароль', 'attr' => array('placeholder' => 'Повторите парооль*')),))
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
