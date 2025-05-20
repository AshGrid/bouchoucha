<?php

namespace App\Entity;

use App\Repository\YearlySequenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YearlySequenceRepository::class)]
#[ORM\Table(name: 'yearly_sequence')]
class YearlySequence
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 2)]
    private string $year;  // Stores 2-digit year (e.g., "24" for 2024)

    #[ORM\Column(type: 'integer')]
    private int $value = 0;  // Stores the last used ID

    // Getters and Setters
    public function getYear(): string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;
        return $this;
    }
}
