<?php

namespace App\Admin;

use App\Entity\BoardMemberFunction;
use App\Entity\Organization;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAdmin extends AbstractAdmin
{
    private $tokenStorage;
    
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    
    public function __construct($code, $class, $baseControllerName, TokenStorageInterface $tokenStorage, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->tokenStorage = $tokenStorage;
        $this->encoder = $encoder;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
//dd($this->tokenStorage->getToken()->getUser());
        $formMapper
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Imię'
                ]
            )
            ->add(
                'last_name',
                TextType::class,
                [
                    'label' => 'Nazwisko'
                ]
            )
            ->add(
                'email',
                TextType::class,
                [
                    'label' => 'email'
                ]
            )
            ->add(
                'function',
                EntityType::class,
                [
                    'class' => BoardMemberFunction::class,
                    'choice_label' => 'name'
                ]
            );
        if (in_array('ROLE_SUPER_ADMIN', $this->tokenStorage->getToken()->getUser()->getRoles())){
            $formMapper->add('organization',
                EntityType::class,
                [
                    'class' => Organization::class,
                    'choice_label' => 'name'
                ]
            )
                ->add(
                    'roles',
                    ChoiceType::class,
                    [
                        'choices' => [
                            'user'  => "ROLE_USER",
                            'admin' => "ROLE_ADMIN",
                        ],
                        'label' => 'Rola',
                        'multiple' => true
                    ]
                )
            ;
        }
        
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
            ->add(
                'organization.name',
                null,
                [
                    'label' => 'Organizacja'
                ]
            )
        ;
    }
    
    public function createQuery($context = 'list')
        //metoda do listowania tylko wybranych rzeczy w tym adminie na podstawie zapytania sql
    {
       if (in_array('ROLE_SUPER_ADMIN', $this->tokenStorage->getToken()->getUser()->getRoles())){
            return parent::createQuery($context);
        }
        /** @var QueryBuilder $query */
        $query = parent::createQuery($context);
        $query
            ->join($query->getRootAliases()[0].'.organization', 'organization')
            ->andWhere('organization.id = :id')
            ->setParameter('id', $this->tokenStorage->getToken()->getUser()->getOrganization()->getId());
        return $query;
        // ten query->getRoot... zwraca dokładnie to, o co chodzi w tym konkretnie adminie, czyli tu usera
        // https://sonata-project.org/bundles/admin/master/doc/reference/action_list.html#customizing-the-query-used-to-generate-the-list
    }
    
    public function prePersist($object)
    {
        if (!in_array('ROLE_SUPER_ADMIN', $this->tokenStorage->getToken()->getUser()->getRoles())){
            $org = $this->tokenStorage->getToken()->getUser()->getOrganization();
            $object->setOrganization($org);
        }
        $password = bin2hex(random_bytes(10));
        $encodedPassword = $this->encoder->encodePassword($object, $password);
        $object->setPassword($encodedPassword);
    }
}
