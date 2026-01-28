<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Module;
use App\Form\ModuleForm;
use App\Entity\TeachingBlock;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TeachingBlockRepository;
use App\Repository\ModuleRepository;

final class ModuleController extends AbstractController
{
    #[Route(path:'/twig/modules', name:'app_modules', methods:['GET'])]
    public function modules(TeachingBlockRepository $teachingBlockRepository, ModuleRepository $moduleRepository): Response
    {
        $teachingBlocks = $teachingBlockRepository->findAll();
        $modules = $moduleRepository->findAll();

        return $this->render('module/modules.html.twig', [
            'teachingBlocks' => $teachingBlocks,
            'modules' => $modules,
        ]);
    }

    #[Route(path:'/twig/view_module/{id}', name:'app_view_module', methods:['GET','POST'])]
    public function viewModule(Request $request, Module $module): Response
    {
        $form = $this->createForm(ModuleForm::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $this->addFlash('success', 'Module mis à jour avec succès !');
        }

        return $this->render('module/view_module.html.twig', [
            'moduleForm' => $form->createView(),
            'module' => $module,
        ]);
    }

    #[Route(path:'/twig/add_module{block_id}', name:'app_add_module', methods:['GET','POST'])]
    public function addModule(Request $request, ?int $block_id = null, EntityManagerInterface $entityManager): Response
    {
        $module = new Module();
        $block = new TeachingBlock();

        if ($block_id) {
            $module->setTeachingBlock($block);
        }

        $form = $this->createForm(ModuleForm::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();
            // Here you would typically save the new module to the database
            $this->addFlash('success', 'Nouveau module ajouté avec succès !');

            $entityManager->persist($module);
            $entityManager->flush();
            return $this->redirectToRoute('app_modules');
        }

        return $this->render('module/add_module.html.twig', [
            'moduleForm' => $form->createView(),
        ]);
    }
}