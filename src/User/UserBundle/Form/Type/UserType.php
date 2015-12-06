<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace User\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

Class UserType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('realname', 'text', [
            'label' => false,
            'attr' => ["class" => "form-control", "placeholder" => "Фамилия, Имя"],
            'data' => isset($options['data']) ? $options['data']->getRealname() : NULL,
            'invalid_message' => 'Заполните поле: "Фамилия, Имя".',
            'error_bubbling' => true,
        ]);
        $builder->add('username', 'email', [
            'label' => false,
            'attr' => ["class" => "form-control", "placeholder" => "email"],
            'data' => isset($options['data']) ? $options['data']->getUsername() : NULL,
            'invalid_message' => 'Заполните поле: "Email".',
            'error_bubbling'=>true,
        ]);
        $builder->add('password', 'repeated', [
            'type' => 'password',
            'invalid_message' => 'Значение паролей не совпадает.',
            'options' => array('attr' => array('class' => 'form-control', "placeholder" => "Пароль")),
            'required' => true,
            'first_options'  => array('label' => false),
            'second_options' => array('label' => false),
            'error_bubbling'=>true,
        ]);
        $builder->add("save", "submit", [
            "label" => "Зарегистрироваться",
            'attr' => ["class" => "btn btn-success"],
        ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\UserBundle\Entity\Users'
        ));
    }

    public function getName()
    {
        return 'Registration';
    }
}
?>