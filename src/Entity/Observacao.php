<?php

namespace App\Entity;

use App\Repository\ObservacaoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObservacaoRepository::class)]
#[ORM\Table(name: "observacoes")]
class Observacao
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descricao = null;

    #[ORM\ManyToOne]
    private ?Consulta $consulta = null;

    #[ORM\Column(length: 255)]
    private ?string $anexo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): static
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getConsulta(): ?Consulta
    {
        return $this->consulta;
    }

    public function setConsulta(?Consulta $consulta): static
    {
        $this->consulta = $consulta;

        return $this;
    }

    public function getAnexo(): ?string
    {
        return $this->anexo;
    }

    public function setAnexo(string $anexo): static
    {
        $this->anexo = $anexo;

        return $this;
    }
}
