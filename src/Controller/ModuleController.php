<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Module;
use App\Form\ModuleForm;

final class ModuleController extends AbstractController
{
    #[Route(path:'/twig/modules', name:'app_modules', methods:['GET'])]
    public function modules(): Response
    {
        return $this->render('module/modules.html.twig');
    }

    #[Route(path:'/twig/view_module/{id}', name:'app_view_module', methods:['GET','POST'])]
    public function viewModule(Request $request, Module $module): Response
    {
        $form = $this->createForm(ModuleForm::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Module mis à jour avec succès !');
        }

        return $this->render('module/view_module.html.twig', [
            'moduleForm' => $form->createView(),
            'module' => $module,
        ]);
    }
}