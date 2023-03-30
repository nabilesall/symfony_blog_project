<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'categories')]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Post $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addCategory($this);
        }

        return $this;
    }

    public function removeCategory(Post $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeCategory($this);
        }

        return $this;
    }
}
