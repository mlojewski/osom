<?php

namespace App\Admin;

use App\Event\ResolutionProjectFormFilled;
use App\Repository\OrganizationRepository;
use App\Service\Mailer;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ResolutionProjectAdmin extends AbstractAdmin
{
    private $mailer;
    private $tokenStorage;
    private $organizationRepository;
    
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        Mailer $mailer,
        TokenStorageInterface $tokenStorage,
        OrganizationRepository $organizationRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->mailer                   = $mailer;
        $this->tokenStorage             = $tokenStorage;
        $this->organizationRepository   = $organizationRepository;
        $this->eventDispatcher          = $eventDispatcher;
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
                CKEditorType::class,
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
                'targetGroup',
                ChoiceType::class,
                [
                    'choices' => [
                        'Zarząd'  => 'ROLE_BOARD',
                        'Wszyscy' => 'ALL'
                    ],
                    'label' => 'Wybierz grupę docelową'
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
    //w metodzie recreate lub repersist tam wypuścić event z sensowną nazwą rozpoczął się proces tworzenia projektu uchwały, tam przekazuję częściowo stworzoną uchwałę, robię listener i tam z token storage wyciągam i wkładam do metody prepersist.
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
    
    public function prePersist($object)
    {
        $event = new ResolutionProjectFormFilled($this->tokenStorage->getToken()->getUser()->getOrganization()->getId(), $object);
        $this->eventDispatcher->dispatch($event, ResolutionProjectFormFilled::NAME );
    }
    
    public function postUpdate($resolutionProject): void
    {
        if ($resolutionProject->getIsPublished() === true) {
            $this->mailer->sendToRecipients(
                $resolutionProject->getId(),
                $this->tokenStorage->getToken()->getUser()->getOrganization()->getId(),
                $resolutionProject->getTargetGroup()
            );
        }
    }
    
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('generateReport', 'generate-report/'.$this->getRouterIdParameter());
        $collection->add('generate-resolution', 'generate-resolution/'.$this->getRouterIdParameter());
    }
}
