<?php

namespace App\Controller;

use App\Entity\Beneficiario;
use App\Entity\Consulta;
use App\Entity\Hospital;
use App\Entity\Medico;
use App\Repository\BeneficiarioRepository;
use App\Repository\ConsultaRepository;
use App\Repository\HospitalRepository;
use App\Repository\MedicoRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConsultaController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('consulta/index.html.twig', [
            'title' => 'Gerenciar Consultas',
        ]);
    }

    public function show(ConsultaRepository $consultaRepository): Response
    {
        $consultas = $consultaRepository->findAll();
        $html = $this->renderView('consulta/list.html.twig', ["consultas" => $consultas]);
        return new Response($html, Response::HTTP_OK);
    }

    public function form(ConsultaRepository $consultaRepository, MedicoRepository $medicoRepository, HospitalRepository $hospitalRepository, BeneficiarioRepository $beneficiarioRepository,  $id = NULL): Response
    {
        if($id):
            $consulta = $consultaRepository->find($id);
            $title = "Editar Consulta";
        else:
            $consulta = NULL;
            $title = "Cadastrar Consulta";
        endif;

        $hospitais = $hospitalRepository->findAll();
        $medicos = $medicoRepository->findAll();
        $beneficiarios = $beneficiarioRepository->findAll();
        return $this->render('consulta/form.html.twig', ["title" => $title, "medicos" => $medicos, 'hospitais' => $hospitais, 'consulta' => $consulta, 'beneficiarios' => $beneficiarios]);
    }

    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $consulta = new Consulta();
        $hospital = $entityManager->getRepository(Hospital::class)->find( $request->request->get('hospital') );
        $medico = $entityManager->getRepository(Medico::class)->find( $request->request->get('medico') );
        $beneficiario = $entityManager->getRepository(Beneficiario::class)->find($request->request->get('beneficiario') );

        $consulta->setData( new DateTime($request->request->get('data')) );
        $consulta->setStatus($request->request->get('status'));
        $consulta->setHospital( $hospital );
        $consulta->setMedico( $medico );
        $consulta->setBeneficiario( $beneficiario );

        $entityManager->persist($consulta);
        $entityManager->flush();

        return $this->json($medico->getId(), Response::HTTP_CREATED);
    }

    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $consulta = $entityManager->getRepository(Consulta::class)->find($id);
        $hospital = $entityManager->getRepository(Hospital::class)->find( $request->request->get('hospital') );
        $medico = $entityManager->getRepository(Medico::class)->find( $request->request->get('medico') );
        $beneficiario = $entityManager->getRepository(Beneficiario::class)->find($request->request->get('beneficiario') );

        $consulta->setData( new DateTime($request->request->get('data')) );
        $consulta->setStatus($request->request->get('status'));
        $consulta->setHospital( $hospital );
        $consulta->setMedico( $medico );
        $consulta->setBeneficiario( $beneficiario );

        $entityManager->flush();

        return $this->json($consulta->getId(), Response::HTTP_OK);
    }

    public function delete(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $consulta = $entityManager->getRepository(Consulta::class)->find($id);

        try{
            $entityManager->remove($consulta);
            $entityManager->flush();

            return $this->json('Deletado com sucesso!', Response::HTTP_OK);
        }catch (\Exception $e){
            return $this->json("O item possui cadastros associados", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
