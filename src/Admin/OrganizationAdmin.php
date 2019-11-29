<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;

class OrganizationAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $mapper)
    {
        $mapper->add(
            'logo',
            ModelListType::class,
            [
                'label' => 'Logo',
                'required' => false
            ],
            [
                'link_parameters' => [
                    'context' => 'default'
                ]
            ]
        )
        ->add(
            'footer',
            ModelListType::class,
            [
                'label' => 'Stopka',
                'required' => false
            ],
            [
                'link_parameters' => [
                    'context' => 'footer'
                ]
            ]
        );
    }
    //TODO okodować załączanie logotypów i footerów do raportu
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier(
                'name',
                null,
                [
                    'label' => 'Nazwa'
                ]
            )
        ;
    }
}