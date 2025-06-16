<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="empresas")
 */
class Empresa
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $senha; // Você deve usar hashing na senha

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cnpj;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descricao;

    /**
     * @ORM\OneToMany(targetEntity="Vaga", mappedBy="empresa")
     */
    private $vagas;

    public function __construct()
    {
        $this->vagas = new ArrayCollection();
    }

    // Getters e Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    // Adicione os outros getters e setters para todos os campos
    // ...
}