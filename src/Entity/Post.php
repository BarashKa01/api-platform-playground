<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PostRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\PostPublishController;
use App\Controller\PostCountController;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups" = {"read:collection"}},
 *     denormalizationContext={"groups" = {"write:Post"}},
 *     paginationItemsPerPage=2,
 *     paginationMaximumItemsPerPage=2,
 *     paginationClientItemsPerPage=true,
 *     collectionOperations={
 *      "get",
 *      "post"={
 *          "validation_groups"={Post::class, "ValidationGroups"}
 *      },
 *      "count"={
 *          "method"="GET",
 *          "path"="/posts/count",
 *          "controller"=PostCountController::class,
 *          "read"=false,
 *          "pagination_enabled"=false,
 *          "filters"={},
 *          "openapi_context"={
                "summary"="Retrieve total posts count",
 *              "parameters"={
                    {
 *                      "in"="query",
 *                      "name"="online",
 *                      "schema"={
                            "type"="integer",
 *                          "maximum"=1,
 *                          "minimum"=0
 *                      },
 *                      "description"="Filter posts which are published"
 *                  }
 *              },
 *           "responses"={
                "200"={
 *                  "description"="OK",
 *                  "content"={
                        "application/json"={
 *                          "schema"={
                                "type"="integer",
 *                              "example"=4
 *                          }
 *                      }
 *                  }
 *              }
 *          }
 *          }
 *          }
 *     },
 *     itemOperations={
 *          "put"={
 *              "denormalization_context"={
 *                  "groups"={"put:Post"}
 *              }
 *          },
 *          "delete",
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"read:collection", "read:item", "read:Post"}
 *              }
 *          },
 *          "publish"={
                "method"="POST",
 *              "path"="/post/{id}/publish",
 *              "controller"=PostPublishController::class,
 *              "openapi_context"={
                    "summary"="Publish or unpublish a post",
 *                  "request_body"={
                        "content"={
 *                          "application/json"={
                                "schema"= {}
 *                          }
 *                      }
 *                  },
 *              },
 *          },
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id":"exact", "title":"partial"})
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:collection", "write:Post"})
     * @Assert\Length(min=5, groups={"create:Post"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:collection", "put:Post", "write:Post"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:item", "write:Post"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:item", "write:Post"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:item", "write:Post"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="posts", cascade={"persist"})
     * @Groups({"read:item", "write:Post"})
     * @Assert\Valid()
     */
    private $category;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     * @Groups({"read:collection"})
     */
    private $online = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public static function validationGroups(self $post) {
        return ["create:Post"];
    }

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }
}
