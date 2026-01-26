<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\InterventionForm;
use Doctrine\DBAL\Connection;
use Knp\Component\Pager\PaginatorInterface;

final class InterventionController extends AbstractController
{
    #[Route(path: '/listeInterventions', name: 'listeInterventions', methods: ['GET'])]
    public function listeInterventions(Request $request, Connection $connection, PaginatorInterface $paginator): Response
    {

        $form = $this->createForm(InterventionForm::class);
        $form->handleRequest($request);

        $qb = $connection->createQueryBuilder();
        $qb
            ->select(
                'c.id',
                'c.start_date',
                'c.end_date',
                'c.remotely',
                'it.name AS interventionType',
                'm.name AS moduleName',
                'u.first_name',
                'u.last_name'
            )
            ->from('course', 'c')
            ->innerJoin('c', 'intervention_type', 'it', 'c.intervention_type_id = it.id')
            ->innerJoin('c', 'module', 'm', 'c.module_id = m.id')
            ->innerJoin('c', 'course_instructor', 'ci', 'ci.course_id = c.id')
            ->innerJoin('ci', 'instructor', 'i', 'ci.instructor_id = i.id')
            ->innerJoin('i', 'user', 'u', 'i.user_id_id = u.id');


        if ($form->isSubmitted()) {
            if ($form->isValid()){
                $data = $form->getData();

                if (!empty($data['DateDebut'])) {
                    $qb->andWhere('c.start_date >= :dateDebut')
                    ->setParameter('dateDebut', $data['DateDebut']->format('Y-m-d H:i:s'));
                }

                if (!empty($data['DateFin'])) {
                    $qb->andWhere('c.end_date <= :dateFin')
                    ->setParameter('dateFin', $data['DateFin']->format('Y-m-d H:i:s'));
                }

                if (!empty($data['Module'])) {
                    $qb->andWhere('m.id = :module')
                    ->setParameter('module', $data['Module']->getId());
                }
            }
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        $results = $qb->executeQuery()->fetchAllAssociative();

        $pagination = $paginator->paginate(
            $results,
            $page,
            $limit
        );


        return $this->render('interventions/interventions.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
}
