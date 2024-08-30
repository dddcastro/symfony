<?php

namespace App\Controller;

use App\Entity\Hospital;
use App\Repository\HospitalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HospitalController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('hospital/index.html.twig', [
            'title' => 'Gerenciar Hospitais',
        ]);
    }
    public function show(HospitalRepository $hospitalRepository): Response
    {
        $hospitais = $hospitalRepository->findAll();
        $html = $this->renderView('hospital/list.html.twig', ["hospitais" => $hospitais]);
        return new Response($html, Response::HTTP_OK);
    }
    public function form(HospitalRepository $hospitalRepository, $id = NULL): Response
    {
        if($id):
            $hospital = $hospitalRepository->find($id);
            $title = "Editar Hospital";
        else:
            $hospital = NULL;
            $title = "Cadastrar Hospital";
        endif;
        return $this->render('hospital/form.html.twig', ["title" => $title, "hospital" => $hospital]);
    }
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hospital = new Hospital();

        $hospital->setNome($request->request->get('nome'));
        $hospital->setEndereco($request->request->get('endereco'));

        $entityManager->persist($hospital);
        $entityManager->flush();

        return $this->json($hospital->getId(), Response::HTTP_CREATED);
    }

    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $hospital = $entityManager->getRepository(Hospital::class)->find($id);

        $hospital->setNome($request->request->get('nome'));
        $hospital->setEndereco($request->request->get('endereco'));

        $entityManager->flush();

        return $this->json($hospital->getId(), Response::HTTP_OK);
    }
    public function delete(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $hospital = $entityManager->getRepository(Hospital::class)->find($id);

        try{
            $entityManager->remove($hospital);
            $entityManager->flush();

            return $this->json('Deletado com sucesso!', Response::HTTP_OK);
        }catch (\Exception $e){
            return $this->json("O item possui cadastros associados", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
