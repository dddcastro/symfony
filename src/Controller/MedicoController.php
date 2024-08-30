<?php

namespace App\Controller;

use App\Entity\Hospital;
use App\Entity\Medico;
use App\Repository\HospitalRepository;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MedicoController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('medico/index.html.twig', [
            'title' => 'Gerenciar Médicos',
        ]);
    }

    public function show(MedicoRepository $medicoRepository): Response
    {
        $medicos = $medicoRepository->findAll();
        $html = $this->renderView('medico/list.html.twig', ["medicos" => $medicos]);
        return new Response($html, Response::HTTP_OK);
    }

    public function form(MedicoRepository $medicoRepository, HospitalRepository $hospitalRepository, $id = NULL): Response
    {
        if($id):
            $medico = $medicoRepository->find($id);
            $title = "Editar Médico";
        else:
            $medico = NULL;
            $title = "Cadastrar Médico";
        endif;

        $hospitais = $hospitalRepository->findAll();
        return $this->render('medico/form.html.twig', ["title" => $title, "medico" => $medico, 'hospitais' => $hospitais]);
    }

    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medico = new Medico();
        $hospital = $entityManager->getRepository(Hospital::class)->find( $request->request->get('hospital') );

        $medico->setNome($request->request->get('nome'));
        $medico->setEspecialidade($request->request->get('especialidade'));
        $medico->setHospital( $hospital );

        $entityManager->persist($medico);
        $entityManager->flush();

        return $this->json($medico->getId(), Response::HTTP_CREATED);
    }
    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $medico = $entityManager->getRepository(Medico::class)->find($id);
        $hospital = $entityManager->getRepository(Hospital::class)->find( $request->request->get('hospital') );

        $medico->setNome($request->request->get('nome'));
        $medico->setEspecialidade($request->request->get('especialidade'));
        $medico->setHospital( $hospital );

        $entityManager->flush();

        return $this->json($medico->getId(), Response::HTTP_OK);
    }

    public function delete(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $medico = $entityManager->getRepository(Medico::class)->find($id);

        try{
            $entityManager->remove($medico);
            $entityManager->flush();

            return $this->json('Deletado com sucesso!', Response::HTTP_OK);
        }catch (\Exception $e){
            return $this->json("O item possui cadastros associados", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
