<?php

namespace QC\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address1', 'text', array(
                'label'=>'Address 1'
            ))
            ->add('address2', 'text', array(
                'label'=>'Address 2'
            ))
            ->add('address3', 'text', array(
                'label'=>'Address 3'
            ))
            ->add('address4', 'text', array(
                'label'=>'Address 4'
            ))
        ;
    }

    public function getName(){
        return '';
    }
}