<?php

namespace App\Entity;

class GenreMovie
{
    /**
     * @param int $id
     * @param string $name
     */
    public function __construct(private readonly int $id, private string $name)
    {}


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
