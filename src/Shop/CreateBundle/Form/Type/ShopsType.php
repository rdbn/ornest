<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Shop\CreateBundle\Form\Type;

use Shop\CreateBundle\Form\Type\ShopsTagsType as TagsType;
use User\UserBundle\Form\EventListener\AddCityFieldSubscriber;
use User\UserBundle\Form\EventListener\AddCountryFieldSubscriber;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

Class ShopsType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', HiddenType::class);
        $builder->add('uniqueName', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => "form-control",
                "placeholder" => "Адресс магазина *",
            ],
        ]);
        $builder->add('shopname', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => "form-control",
                "placeholder" => "Название магазина *",
            ],
        ]);
        $builder->add('shopTags', TagsType::class, [
            'label' => false,
            'attr' => [
                'class' => "form-control",
                "placeholder" => "Теги магазина *",
            ],
        ]);
        $builder->add('phone', NumberType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => "form-control",
                "placeholder" => "Телефон",
            ],
        ]);
        $builder->add('email', EmailType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => "form-control",
                "placeholder" => "email",
            ],
        ]);
        $builder->add('description', TextareaType::class, [
            'label' => false,
            'required' => false,
            'attr' => ['class' => 'form-control', 'rows' => '8', 'placeholder' => 'кратко о магазине'],
        ]);
        $builder->add("save", SubmitType::class, [
            "label" => "Создать",
            'attr' => [
                "class" => "btn btn-success center-block",
            ],
        ]);
        
        $factory = $builder->getFormFactory();
        $citySubscriber = new AddCityFieldSubscriber($factory);
        $countrySubscriber = new AddCountryFieldSubscriber($factory);
        $builder->addEventSubscriber($citySubscriber);
        $builder->addEventSubscriber($countrySubscriber);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Shop\CreateBundle\Entity\Shops'
        ));
    }
}