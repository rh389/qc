<?php

namespace QC\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address1')
            ->add('address2')
            ->add('address3')
            ->add('address4')
        ;
    }

    public function getName(){
        return '';
    }
}