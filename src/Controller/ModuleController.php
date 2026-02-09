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

    #[Route(path:'/twig/view_module?id={id}', name:'app_view_module', methods:['GET','POST'])]
    public function viewModule(Request $request, Module $module): Response
    {
        $form = $this->createForm(ModuleForm::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            //Erreurs champs vides
            if (!$form->get('name')->getData()) {
                $this->addFlash('error', 'Le nom du module est obligatoire.');
            }
            if (!$form->get('code')->getData()) {
                $this->addFlash('error', 'Le code du module est obligatoire.');
            }
            if (!$form->get('teachingBlock')->getData()) {
                $this->addFlash('error', 'Le bloc d\'enseignement est obligatoire.');
            }
            if (!$form->get('description')->getData()) {
                $this->addFlash('error', 'La description du module est obligatoire.');
            }
            //Erreurs de prérequis
            /*if ($form->get('teachingBlock')->getData() && $block_id) {
                $selectedBlock = $form->get('teachingBlock')->getData();
                if ($selectedBlock->getId() !== $block_id) {
                    $this->addFlash('error', 'Le bloc d\'enseignement sélectionné ne correspond pas au bloc pré-rempli.');
                }
            }*/
            if ($form->get('hoursCount')->getData() !== null) {
                $hoursCount = $form->get('hoursCount')->getData();
                if ($hoursCount < 0) {
                    $this->addFlash('error', 'Le nombre d\'heures doit être un nombre positif.');
                }
            }
            if ($form->get('parent')->getData()) {
                $parentModule = $form->get('parent')->getData();
                if ($parentModule->getId() === $module->getId()) {
                    $this->addFlash('error', 'Un module ne peut pas être son propre parent.');
                }
            }
            if ($form->get('hoursCount')->getData()) {
                $hoursCount = $form->get('hoursCount')->getData();
                if (!is_int($hoursCount)) {
                    $this->addFlash('error', 'Le nombre d\'heures doit être un entier.');
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $this->addFlash('success', 'Module mis à jour avec succès !');
        }

        return $this->render('module/view_module.html.twig', [
            'moduleForm' => $form->createView(),
            'module' => $module,
        ]);
    }

    #[Route(path:'/twig/add_module', name:'app_add_module', methods:['GET','POST'])]
    public function addModule(Request $request, EntityManagerInterface $entityManager): Response
    {
        $module = new Module();
        $block_id = $request->query->getInt('block_id');

        if ($block_id) {
            $block = $entityManager->getRepository(TeachingBlock::class)->find($block_id);
            if ($block) {
                $module->setTeachingBlock($block);
            }
        }
        $form = $this->createForm(ModuleForm::class, $module);
        $form->handleRequest($request);


        if ($form->isSubmitted() && !$form->isValid()) {
            //Erreurs champs vides
            if (!$form->get('name')->getData()) {
                $this->addFlash('error', 'Le nom du module est obligatoire.');
            }
            if (!$form->get('code')->getData()) {
                $this->addFlash('error', 'Le code du module est obligatoire.');
            }
            if (!$form->get('teachingBlock')->getData()) {
                $this->addFlash('error', 'Le bloc d\'enseignement est obligatoire.');
            }
            if (!$form->get('description')->getData()) {
                $this->addFlash('error', 'La description du module est obligatoire.');
            }
            //Erreurs de prérequis
            if ($form->get('teachingBlock')->getData() && $block_id) {
                $selectedBlock = $form->get('teachingBlock')->getData();
                if ($selectedBlock->getId() !== $block_id) {
                    $this->addFlash('error', 'Le bloc d\'enseignement sélectionné ne correspond pas au bloc pré-rempli.');
                }
            }
            if ($form->get('hoursCount')->getData() !== null) {
                $hoursCount = $form->get('hoursCount')->getData();
                if ($hoursCount < 0) {
                    $this->addFlash('error', 'Le nombre d\'heures doit être un nombre positif.');
                }
            }
            if ($form->get('parent')->getData()) {
                $parentModule = $form->get('parent')->getData();
                if ($parentModule->getId() === $module->getId()) {
                    $this->addFlash('error', 'Un module ne peut pas être son propre parent.');
                }
            }
            if ($form->get('hoursCount')->getData()) {
                $hoursCount = $form->get('hoursCount')->getData();
                if (!is_int($hoursCount)) {
                    $this->addFlash('error', 'Le nombre d\'heures doit être un entier.');
                }
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();
            $this->addFlash('success', 'Nouveau module ajouté avec succès !');

            $entityManager->persist($module);
            $entityManager->flush();
            return $this->redirectToRoute('app_modules');
        }

        return $this->render('module/add_module.html.twig', [
            'moduleForm' => $form->createView(),
            'block_id' => $block_id,
        ]);
    }

    #[Route(path:'/twig/delete_module?id={id}', name:'app_delete_module', methods:['GET','POST'])]
    public function deleteModule(Request $request, EntityManagerInterface $entityManager, Module $module): Response
    {
        if ($this->isCsrfTokenValid('delete_module'.$module->getId(), $request->request->get('_token'))) {
            // Check for dependencies
            if (!$module->getInstructors()->isEmpty()) {
                $this->addFlash('error', 'Impossible de supprimer le module car il est associé à des instructeurs.');
                return $this->redirectToRoute('app_view_module', ['id' => $module->getId()]);
            }
            if (!$module->getCourses()->isEmpty()) {
                $this->addFlash('error', 'Impossible de supprimer le module car il est associé à des cours.');
                return $this->redirectToRoute('app_view_module', ['id' => $module->getId()]);
            }
            if (!$module->getChildren()->isEmpty()) {
                $this->addFlash('error', 'Impossible de supprimer le module car il a des sous-modules.');
                return $this->redirectToRoute('app_view_module', ['id' => $module->getId()]);
            }

            try {
                $entityManager->remove($module);
                $entityManager->flush();
                $this->addFlash('success', 'Module supprimé avec succès !');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la suppression du module : ' . $e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide. Suppression annulée.');
        }

        return $this->redirectToRoute('app_modules');
    }
}
