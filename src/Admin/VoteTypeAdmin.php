<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VoteTypeAdmin extends AbstractAdmin
{
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'type',
                TextType::class,
                [
                    'label' => 'Typ głosu'
                ]
            )
            ->add(
                'value',
                IntegerType::class,
                [
                    'label' => 'wartość'
                ]
            )
        ;
    }
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier(
                'type',
                null,
                [
                    'label' => 'Typ'
                ]
            )
            ->add(
                'value',
                null,
                [
                    'label' => 'Wartość'
                ]
            )
        ;
    }
}
