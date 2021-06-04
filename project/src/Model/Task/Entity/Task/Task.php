<?php

declare(strict_types=1);

namespace App\Model\Task\Entity\Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "task_tasks")]
#[ORM\Index(fields: ["createdAt"])]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: "task_task_id")]
    private Id $id;

    #[ORM\Column(type: "task_task_status")]
    private Status $status;

    #[ORM\Column(type: "task_task_priority")]
    private Priority $priority;

    #[ORM\Column]
    private string $title;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "task_task_author_id")]
    private AuthorId $authorId;

    #[ORM\ManyToOne(inversedBy: "children")]
    #[ORM\JoinColumn(name: "parent_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?Task $parent = null;
    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(mappedBy: "parent", targetEntity: Task::class)]
    private Collection $children;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finishedAt = null;

    public function __construct(
        Id $id,
        Priority $priority,
        string $title,
        string $desc,
        AuthorId $authorId,
        \DateTimeImmutable $createdAt,
        ?Task $parent
    ) {
        $this->id = $id;
        $this->status = Status::todo();
        $this->priority = $priority;
        $this->title = $title;
        $this->description = $desc;
        $this->authorId = $authorId;
        $this->children = new ArrayCollection();
        $this->createdAt = $createdAt;
        if ($parent) {
            $this->assignToParent($parent);
        }
    }

    public function edit(
        Priority $priority,
        string $title,
        string $desc,
        ?Task $parent
    ): void {
        $this->priority = $priority;
        $this->title = $title;
        $this->description = $desc;
        if ($parent) {
            $this->assignToParent($parent);
        }
    }

    public function finish(\DateTimeImmutable $finishedAt): void
    {
        if ($this->isDone()) {
            throw new \DomainException('Task was already marked as done');
        }

        foreach ($this->children as $child) {
            if (!$child->isDone()) {
                throw new \DomainException('Complete child tasks before closing this one');
            }
        }

        $this->status = Status::done();
        $this->finishedAt = $finishedAt;
    }

    public function assignToParent(Task $parent): void
    {
        if ($this->parent === $parent) {
            return;
        }

        $parentTask = $parent;
        while ($parentTask) {
            if ($parentTask->getId() === $this->id) {
                throw new \DomainException("Task can't be its own parent");
            }
            $parentTask = $parentTask->getParent();
        }

        $this->parent = $parent;
    }

    public function isDone(): bool
    {
        return $this->status->isDone();
    }

    public function getParent(): ?Task
    {
        return $this->parent;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function canBeDeleted(): bool
    {
        return !$this->isDone();
    }

    public function getAuthorId(): AuthorId
    {
        return $this->authorId;
    }

    public function getPriority(): Priority
    {
        return $this->priority;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }
}
