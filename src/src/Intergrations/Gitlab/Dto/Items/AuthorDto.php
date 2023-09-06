<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Dto\Items;

class AuthorDto
{
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function fromResponseArray(array $item): self
    {
        $this->id = $item['id'];
        return $this;
    }
}
