<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BannerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Название места'))
            ->add('type', null, array('label' => 'тип'))
            ->add('format', null, array('label' => 'Формат'))
            ->add('title', null, array('label' => 'Название места'))
            ->add('img', null, array('label' => 'Картинка'))
            ->add('city', null, array('label' => 'Город'))
            ->add('area','choice',  array(
                'empty_value' => false,
                'choices' => array(
                    'СВАО' => 'СВАО',
                    'ВАО'  => 'ВАО' ,
                    'ЮВАО' => 'ЮВАО',
                    'ЮАО'  => 'ЮАО' ,
                    'ЮЗАО' => 'ЮЗАО',
                    'ЗАО'  => 'ЗАО' ,
                    'СЗАО' => 'СЗАО',
                    'САО'  => 'САО' ,
                    'ЦАО'  => 'ЦАО' ,
                ),
                'label' => 'Область',
                'required'  => false,
            ))
            ->add('adrs', null, array('label' => 'Адрес'))
            ->add('light','choice',  array(
                'empty_value' => false,
                'choices' => array(
                    '1' => '1',
                    '0' => '0',
                ),
                'label' => 'Свет',
                'required'  => false,
            ))
            ->add('grp', null, array('label' => 'GRP'))
            ->add('ots', null, array('label' => 'OTS'))
            ->add('side','choice',  array(
                'empty_value' => false,
                'choices' => array(
                    'A' => 'A',
                    'B' => 'B',
                ),
                'label' => 'Сторона',
                'required'  => false,
            ))

            ->add('price', null, array('label' => 'Цена без налога'))
            ->add('price2', null, array('label' => 'Ценс с налогом'))
            ->add('taxType', null, array('label' => 'тип налога'))
            ->add('longitude', null, array('label' => 'Долгота GPS'))

            ->add('latitude', null, array('label' => 'Широта GPS'))
            ->add('body', null, array('label' => 'Описание баннера'))
//            ->add('enabled','choice',  array(
//                'empty_value' => false,
//                'choices' => array(
//                    '1' => 'Активен',
//                    '0' => 'Заблокирован',
//                ),
//                'label' => 'Активность',
//                'required'  => false,
//            ))
            ->add('hot','choice',  array(
                'empty_value' => false,
                'choices' => array(
                    '0' => 'Обычное',
                    '1' => 'Горячее',
                ),
                'label' => 'Горячее предложение',
                'required'  => false,
            ))
            ->add('submit', 'submit', array('label' => 'Сохранить', 'attr' => array('class' => 'btn-primary')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Banner'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_banner';
    }
}
