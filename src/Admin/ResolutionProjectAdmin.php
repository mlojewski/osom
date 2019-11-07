<?php

namespace App\Admin;

use App\Controller\ResolutionProjectController;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ResolutionProjectAdmin extends AbstractAdmin
{
    private $mailer;
    private $resolutionProjectController;
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        \Swift_Mailer $mailer,
        ResolutionProjectController $resolutionProjectController
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->mailer = $mailer;
        $this->resolutionProjectController = $resolutionProjectController;
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'number',
                TextType::class,
                [
                    'label' => 'Numer projektu uchwały'
                ]
            )
            ->add(
                'title',
                TextAreaType::class,
                [
                    'label' => 'Tytuł uchwały'
                ]
            )
            ->add(
                'content',
                TextAreaType::class,
                [
                    'label' => 'Treść projektu uchwały'
                ]
            )
            ->add(
                'deadline',
                DateTimePickerType::class,
                [
                    'label' => 'Deadline głosowania'
                ]
            )
            ->add(
                'isPublished',
                CheckboxType::class,
                [
                    'label' => 'Czy opublikowana',
                    'required' => false
                ]
            )
        ;
    }
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier(
                'number',
                null,
                [
                    'label' => 'numer projektu uchwały'
                ]
            )
            ->add(
                'title',
                null,
                [
                    'label' => ' Tytuł'
                ]
            )
            ->add(
                'content',
                null,
                [
                    'label' => 'treść projektu uchwały'
                ]
            )
            ->add(
                'deadline',
                null,
                [
                    'label' => 'deadline'
                ]
            )
            ->add(
                'isPublished',
                null,
                [
                    'label' => 'Czy opublikowano'
                ]
            )
            ->add(
                '_action',
                null,
                [
                    'actions' => [
                        'generateReport' => [
                            'template' => '/CRUD/list__action_report.html.twig'
                        ],
                        'generate_resolution' => [
                            'template' => '/CRUD/list__action_resolution.html.twig'
                        ]
                    ]
                ]
            )
        ;
    }
    
    public function postUpdate($resolutionProject)
    {
        if ($resolutionProject->getIsPublished() === true) {
            $this->resolutionProjectController->sendToRecipients($resolutionProject->getId());
        }
    }
    
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('generateReport', 'generate-report/'.$this->getRouterIdParameter());
        $collection->add('generate-resolution', 'generate-resolution/'.$this->getRouterIdParameter());
    }
}
