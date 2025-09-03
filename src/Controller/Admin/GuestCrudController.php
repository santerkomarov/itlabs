<?php

namespace App\Controller\Admin;

use App\Entity\Guest;
use App\Entity\Desk;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class GuestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Guest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Гость')
            ->setEntityLabelInPlural('Гости')
            ->setPageTitle(Crud::PAGE_INDEX, 'Гости')
            ->setPageTitle(Crud::PAGE_NEW, 'Добавление гостя')
            ->setPageTitle(Crud::PAGE_EDIT, 'Редактирование гостя')
            ->setDefaultSort(['id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex()->setLabel('ID');

        yield BooleanField::new('isPresent', 'Присутствие')
            ->renderAsSwitch(true);

        yield TextField::new('name', 'ФИО');

        // "Стол" -> ссылка
        yield AssociationField::new('desk', 'Стол')
            ->setRequired(false)
            ->setCrudController(DeskCrudController::class) // к какому CRUD вести
            ->setFormTypeOption('choice_label', function (?Desk $desk) {
                return $desk ? ('Стол '.$desk->getNum()) : '';
            });
    }
}
