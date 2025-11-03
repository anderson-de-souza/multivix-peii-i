<?php

class Poster {
    
    private int $id;
    private string $title;
    private string $headline;
    private string $description;
    private ?string $coverImgName;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

    public function __construct(
        string $title,
        string $headline,
        string $description,
        ?string $coverImgName,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null
    ) {
        $this->title = $title;
        $this->headline = $headline;
        $this->description = $description;
        $this->coverImgName = $coverImgName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getHeadline(): string {
        return $this->headline;
    }

    public function setHeadline(string $headline): void {
        $this->headline = $headline;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getCoverImgName(): ?string {
        return $this->coverImgName;
    }

    public function setCoverImgName(?string $coverImgName): void {
        $this->coverImgName = $coverImgName;
    }

    public function getCreatedAt(): ?DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime {
        return $this->updatedAt;
    }
    
}