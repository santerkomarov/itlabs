<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Guest;
use App\Entity\Desk;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use App\Controller\HomeController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // сразу переходим к списку Столов
        $url = $this->container->get(AdminUrlGenerator::class)
            ->setController(DeskCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Административная панель');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Домой', 'fas fa-home', '/');
        yield MenuItem::linkToCrud('Столы', 'fas fa-th-large', Desk::class);
        yield MenuItem::linkToCrud('Гости', 'fas fa-users', Guest::class);
    }
}
