<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\TabRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TabRepository::class)]
class Tab
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    /**
     * @var ?array<mixed>
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $orderData = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return ?array<mixed>
     */
    public function getOrderData(): ?array
    {
        return $this->orderData;
    }

    /**
     * @param ?array<mixed> $orderData
     */
    public function setOrderData(?array $orderData): static
    {
        $this->orderData = $orderData;

        return $this;
    }
}
