<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Shop\InformationBundle\Form\Type;

use Shop\InformationBundle\Form\DataTransformer\UsersToIdTransformer;

use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UserIDType extends AbstractType {
    
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $transformer = new UsersToIdTransformer($this->om);
        $builder->addViewTransformer($transformer);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected issue does not exist',
        ));
    }
    
    public function getParent()
    {
        return HiddenType::class;
    }
}