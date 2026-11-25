<?php

namespace App\Entity;


use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`USER`')]
class User {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ID', type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(name: 'KEYCLOAK_ID', length: 36, unique: true, nullable: false)]
    private string $keycloakId; // sub из Keycloak


    public function __construct(string $keycloakId) {
        $this->keycloakId = $keycloakId;
    }


    public function getId(): ?int {
        return $this->id;
    }


    public function setId(int $id): static {
        $this->id = $id;

        return $this;
    }


    public function getKeycloakId(): ?string {
        return $this->keycloakId;
    }


    public function setKeycloakId(string $keycloakId): static {
        $this->keycloakId = $keycloakId;

        return $this;
    }
}
