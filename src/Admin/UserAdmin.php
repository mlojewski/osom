<?php

namespace App\Admin;

use App\Entity\BoardMemberFunction;
use App\Entity\Organization;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserAdmin extends AbstractAdmin
{
    private $tokenStorage;
    
    public function __construct($code, $class, $baseControllerName, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->tokenStorage = $tokenStorage;
    }

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
            );
        if (in_array('ROLE_ADMIN', $this->tokenStorage->getToken()->getUser()->getRoles())){
            $formMapper->add('organization',
                EntityType::class,
                [
                    'class' => Organization::class,
                    'choice_label' => 'name'
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
       if (in_array('ROLE_ADMIN', $this->tokenStorage->getToken()->getUser()->getRoles())){
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
}
