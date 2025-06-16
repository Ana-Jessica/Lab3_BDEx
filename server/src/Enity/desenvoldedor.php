<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="desenvolvedores")
 */
class Desenvolvedor
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $biografia;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nivel_experiencia; // Júnior, Pleno, Sênior

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $habilidades = []; // Array de habilidades (PHP, JavaScript, etc.)

    /**
     * @ORM\OneToMany(targetEntity="Candidatura", mappedBy="desenvolvedor")
     */
    private $candidaturas;

    public function __construct()
    {
        $this->candidaturas = new ArrayCollection();
    }

    // Getters e Setters
    // ...
}