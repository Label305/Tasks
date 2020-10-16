<?php


namespace Label305\Tasks\Persistence\Log;


class Log
{

    /**
     * @var int|null id
     */
    private $id;

    /**
     * @var int taskId
     */
    private $taskId;

    /**
     * @var string blob
     */
    private $blob;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTaskId(): int
    {
        return $this->taskId;
    }

    /**
     * @param int $taskId
     */
    public function setTaskId(int $taskId): void
    {
        $this->taskId = $taskId;
    }

    /**
     * @return string
     */
    public function getBlob(): string
    {
        return $this->blob;
    }

    /**
     * @param string $blob
     */
    public function setBlob(string $blob): void
    {
        $this->blob = $blob;
    }

}