<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\ProjectRole;
use App\Repository\GitlabUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GitlabUserRepository::class)]
class GitlabUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $external_user_id; // идентификатор пользователя gitlab

    #[ORM\Column(type: 'string', enumType: ProjectRole::class)]
    private ProjectRole $role; // роль пользователя в проекте

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalUserId(): int
    {
        return $this->external_user_id;
    }

    public function setExternalUserId(int $external_user_id): static
    {
        $this->external_user_id = $external_user_id;

        return $this;
    }

    public function getRole(): ProjectRole
    {
        return $this->role;
    }

    public function setRole(ProjectRole $role): static
    {
        $this->role = $role;

        return $this;
    }
}
