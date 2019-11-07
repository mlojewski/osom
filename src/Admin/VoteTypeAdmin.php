<?php

namespace App\Admin;

use App\Controller\ResolutionProjectController;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
        ;
    }
}
