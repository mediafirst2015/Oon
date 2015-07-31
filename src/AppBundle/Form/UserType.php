<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $rolesChoices = array(
            'ROLE_USER' => 'Пользователь',
            'ROLE_OPERATOR' => 'администратор',
            'ROLE_UNCONFIRMED' => 'заявка',
        );
        $builder
            ->add('username', null, array('label' => 'Email'))
            ->add('lastName', null, array('label' => 'Фамилия'))
            ->add('firstName', null, array('label' => 'Имя'))
            ->add('surName', null, array('label' => 'Отчество'))
            ->add('company', null, array('label' => 'Название организации'))
            ->add('phone', null, array('label' => 'Контактный телефон'))
            ->add('roles', 'choice', array('label' => 'Роль', 'choices' => $rolesChoices, 'multiple' => true, ))
            ->add('submit', 'submit', array('label' => 'Сохранить', 'attr' => array('class' => 'btn-primary')))
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
