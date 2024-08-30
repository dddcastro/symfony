<?php

namespace App\Controller;

use App\Entity\Consulta;
use App\Entity\Observacao;
use App\Repository\ConsultaRepository;
use App\Repository\ObservacaoRepository;
use Cassandra\Date;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ObservacaoController extends AbstractController
{
    public function index(ConsultaRepository $consultaRepository, $consulta_id): Response
    {
        $consulta = $consultaRepository->find($consulta_id);
        $editavel = $consulta->getData()->format("Y-m-d") >= date("Y-m-d");

        return $this->render('observacao/index.html.twig', [
            'title' => 'Visualizar Observações da Consulta',
            'consulta_id' => $consulta_id,
            'editavel' => $editavel,
        ]);
    }
    public function show(ObservacaoRepository $observacaoRepository, ConsultaRepository $consultaRepository, $consulta_id): Response
    {
        $consulta = $consultaRepository->find($consulta_id);
        $observacoes = $observacaoRepository->findBy(['consulta' => $consulta]);
        $editavel = $consulta->getData()->format("Y-m-d") >= date("Y-m-d");

        $html = $this->renderView('observacao/list.html.twig', ["observacoes" => $observacoes, 'consulta_id' => $consulta_id, 'editavel' => $editavel]);
        return new Response($html, Response::HTTP_OK);
    }

    public function form(ObservacaoRepository $observacaoRepository, ConsultaRepository $consultaRepository, $consulta_id, $id = NULL): Response
    {
        if($id):
            $observacao = $observacaoRepository->find($id);
            $title = "Editar Observação";
        else:
            $observacao = NULL;
            $title = "Cadastrar Observação";
        endif;

        $consulta = $consultaRepository->find($consulta_id);
        return $this->render('observacao/form.html.twig', ["title" => $title, 'consulta' => $consulta, 'observacao' => $observacao]);
    }
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $observacao = new Observacao();
        $consulta = $entityManager->getRepository(Consulta::class)->find( $request->request->get('consulta_id') );

        $observacao->setDescricao($request->request->get('descricao'));
        $observacao->setConsulta($consulta);

        if($request->files->get("anexo") != NULL):
            $file = $request->files->get("anexo");
            $ext = $file->guessExtension();
            if(!in_array($ext, ["jpg", "jpeg", "png", "gif"])) return $this->json("O anexo não possui uma extensão válida", Response::HTTP_INTERNAL_SERVER_ERROR);

            $newname = md5($file->getClientOriginalName()) . uniqid() . time() . '.' . $file->guessExtension();
            $file->move($this->getParameter("upload_folder"), $newname);
            $observacao->setAnexo($newname);
        else:
            $observacao->setAnexo("");
        endif;

        $entityManager->persist($observacao);
        $entityManager->flush();

        return $this->json($observacao->getId(), Response::HTTP_CREATED);
    }

    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $observacao = $entityManager->getRepository(Observacao::class)->find($id);
        $consulta = $entityManager->getRepository(Consulta::class)->find( $request->request->get('consulta_id') );


        $observacao->setDescricao($request->request->get('descricao'));
        $oldFile = $this->getParameter("upload_folder") . "/" . $observacao->getAnexo();

        if($request->files->get("anexo") != NULL):
            if($observacao->getAnexo() != "") unlink($this->getParameter("upload_folder") . "/" . $observacao->getAnexo() );
            $file = $request->files->get("anexo");

            $ext = $file->guessExtension();
            if(!in_array($ext, ["jpg", "jpeg", "png", "gif"])) return $this->json("O anexo não possui uma extensão válida", Response::HTTP_INTERNAL_SERVER_ERROR);

            $newname = md5($file->getClientOriginalName()) . uniqid() . time() . '.' . $file->guessExtension();
            $file->move($this->getParameter("upload_folder"), $newname);
            $observacao->setAnexo($newname);
        endif;

        $entityManager->flush();

        return $this->json($observacao->getId(), Response::HTTP_OK);
    }
    public function delete(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $observacao = $entityManager->getRepository(Observacao::class)->find($id);

        if($observacao->getAnexo() != "") unlink($this->getParameter("upload_folder") . "/" . $observacao->getAnexo() );

        try{
            $entityManager->remove($observacao);
            $entityManager->flush();

            return $this->json('Deletado com sucesso!', Response::HTTP_OK);
        }catch (\Exception $e){
            return $this->json("O item possui cadastros associados", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
