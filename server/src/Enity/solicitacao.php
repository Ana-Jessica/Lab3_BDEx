<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="candidaturas")
 */
class Candidatura
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data_candidatura;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status; // "pendente", "aceita", "rejeitada"

    /**
     * @ORM\ManyToOne(targetEntity="Desenvolvedor", inversedBy="candidaturas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $desenvolvedor;

    /**
     * @ORM\ManyToOne(targetEntity="Vaga", inversedBy="candidaturas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vaga;

    public function __construct()
    {
        $this->data_candidatura = new \DateTime();
        $this->status = 'pendente';
    }

    // Getters e Setters
    // ...
}