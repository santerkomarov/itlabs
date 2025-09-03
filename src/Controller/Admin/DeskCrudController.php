<?php

namespace App\Controller\Admin;

use App\Entity\Desk;
use App\Entity\Guest;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class DeskCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Desk::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Стол')
            ->setEntityLabelInPlural('Столы')
            ->setPageTitle(Crud::PAGE_INDEX, 'Столы')
            ->setPageTitle(Crud::PAGE_DETAIL, fn(Desk $d) => 'Стол '.$d->getNum())
            ->setPageTitle(Crud::PAGE_NEW, 'Создать стол')
            ->setPageTitle(Crud::PAGE_EDIT, 'Редактировать стол')
            ->setDefaultSort(['id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        // Общие поля
        $id       = IdField::new('id')->setLabel('ID');
        $num   = IntegerField::new('num', 'Номер стола');
        $desc     = TextField::new('description', 'Описание');
        $maxGuests = IntegerField::new('maxGuests', 'Макс количество человек');

        // Вычисляемые колонки «Гостей» и «Присутствует гостей»
        $guestsDef = IntegerField::new('guestsDef', 'Гостей')
            ->formatValue(fn($v, Desk $desk) => $desk->getGuestsList()->count());

        $guestsNow = IntegerField::new('guestsNow', 'Присутствует гостей')
            ->formatValue(fn($v, Desk $desk) => $desk->getGuestsList()->filter(
                fn(Guest $g) => $g->isPresent()
            )->count());

        if ($pageName === Crud::PAGE_INDEX) {
            return [$id->onlyOnIndex(), $num, $desc->hideOnForm(), $maxGuests, $guestsDef, $guestsNow];
        }

        if ($pageName === Crud::PAGE_DETAIL) {
            return [$id, $num, $desc, $maxGuests, $guestsDef, $guestsNow];
        }

        // На странице "новый/редактировать" — ТОЛЬКО изменяемые поля
        return [$num, $desc, $maxGuests];
    }
}
