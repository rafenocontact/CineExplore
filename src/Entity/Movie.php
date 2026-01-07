<?php

namespace App\Entity;

class Movie
{
    private ?int $id;

    private ?string $title;

    private ?\DateTimeInterface $releaseDate;

    private ?string $productionName;

    private ?string $description;

    private mixed $voteCount;

    private ?float $voteAverage;

    private mixed $urlVideo;

    private mixed $thumbnail;

    private mixed $originalLanguage;

    /**
     * @return mixed
     */
    public function getOriginalLanguage()
    {
        return $this->originalLanguage;
    }

    /**
     * @param mixed $originalLanguage
     */
    public function setOriginalLanguage($originalLanguage): void
    {
        $this->originalLanguage = $originalLanguage;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getProductionName(): ?string
    {
        return $this->productionName;
    }

    public function setProductionName(?string $productionName): self
    {
        $this->productionName = $productionName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getVoteAverage(): ?float
    {
        return $this->voteAverage;
    }

    public function setVoteAverage(?float $voteAverage): self
    {
        $this->voteAverage = $voteAverage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrlVideo()
    {
        return $this->urlVideo;
    }

    /**
     * @param mixed $urlVideo
     */
    public function setUrlVideo($urlVideo): void
    {
        $this->urlVideo = $urlVideo;
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param mixed $thumbnail
     */
    public function setThumbnail($thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }
    /**
     * @return mixed
     */
    public function getVoteCount()
    {
        return $this->voteCount;
    }

    /**
     * @param mixed $voteCount
     */
    public function setVoteCount($voteCount): void
    {
        $this->voteCount = $voteCount;
    }
}
