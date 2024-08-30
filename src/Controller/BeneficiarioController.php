<?php

namespace App\Controller;

use App\Entity\Beneficiario;
use App\Repository\BeneficiarioRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BeneficiarioController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('beneficiario/index.html.twig', [
            'title' => 'Gerenciar Beneficiarios',
        ]);
    }

    public function show(BeneficiarioRepository $beneficiarioRepository): Response
    {
        $beneficiarios = $beneficiarioRepository->findAll();
        $html = $this->renderView('beneficiario/list.html.twig', ["beneficiarios" => $beneficiarios]);
        return new Response($html, Response::HTTP_OK);
    }

    public function form(BeneficiarioRepository $beneficiarioRepository, $id = NULL): Response
    {
        if($id):
            $beneficiario = $beneficiarioRepository->find($id);
            $title = "Editar Beneficiario";
        else:
            $beneficiario = NULL;
            $title = "Cadastrar Beneficiario";
        endif;
        return $this->render('beneficiario/form.html.twig', ["title" => $title, "beneficiario" => $beneficiario]);
    }

    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $beneficiario = new Beneficiario();

        $beneficiario->setNome($request->request->get('nome'));
        $beneficiario->setEmail($request->request->get('email'));
        $beneficiario->setDataNascimento(new DateTime($request->request->get('data_nascimento')));

        $entityManager->persist($beneficiario);
        $entityManager->flush();

        return $this->json($beneficiario->getId(), Response::HTTP_CREATED);
    }

    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $beneficiario = $entityManager->getRepository(Beneficiario::class)->find($id);

        $beneficiario->setNome($request->request->get('nome'));
        $beneficiario->setEmail($request->request->get('email'));
        $beneficiario->setDataNascimento(new DateTime($request->request->get('data_nascimento')));

        $entityManager->flush();

        return $this->json($beneficiario->getId(), Response::HTTP_OK);
    }

    public function delete(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $beneficiario = $entityManager->getRepository(Beneficiario::class)->find($id);

        try{
            $entityManager->remove($beneficiario);
            $entityManager->flush();

            return $this->json('Deletado com sucesso!', Response::HTTP_OK);
        }catch (\Exception $e){
            return $this->json("O item possui cadastros associados", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
