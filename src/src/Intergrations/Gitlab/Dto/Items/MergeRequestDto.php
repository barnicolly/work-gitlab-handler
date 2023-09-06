<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Dto\Items;

class MergeRequestDto
{
    private string $title;

    private string $sourceBranch;

    private string $webUrl;

    private int $iid;
    /**
     * @var string[]
     */
    private array $labels;

    private AuthorDto $author;

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getIid(): int
    {
        return $this->iid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): AuthorDto
    {
        return $this->author;
    }

    public function getSourceBranch(): string
    {
        return $this->sourceBranch;
    }

    public function getWebUrl(): string
    {
        return $this->webUrl;
    }

    public function fromResponseArray(array $item): self
    {
        $this->iid = $item['iid'];
        $this->title = $item['title'];
        $this->labels = $item['labels'];
        $this->sourceBranch = $item['source_branch'];
        $this->webUrl = $item['web_url'];
        $this->author = (new AuthorDto())->fromResponseArray($item['author']);
        return $this;
    }
}
