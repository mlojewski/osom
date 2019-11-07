<?php

namespace App\Admin;

use App\Controller\ResolutionProjectController;
use App\Entity\BoardMemberFunction;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'function',
                EntityType::class,
                [
                    'class' => BoardMemberFunction::class,
                    'choice_label' => 'name'
                ]
            )
        ;
    }
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add(
                'name',
                null,
                [
                    'label' => 'Imię'
                ]
            )
            ->addIdentifier(
                'lastName',
                null,
                [
                    'label' => 'Nazwisko'
                ]
            )
        ;
    }
}
