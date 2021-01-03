<?php

declare(strict_types=1);

namespace Supermetrics\Domain;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post
 * @package Supermetrics\Domain
 */
class Post
{
    /**
     * PostDto constructor.
     * @param string $id
     * @param string $fromName
     * @param string $fromId
     * @param string $message
     * @param string $type
     * @param DateTime $createdTime
     */
    public function __construct(
        #[Assert\NotBlank]
        private string $id,
        #[Assert\NotBlank]
        private string $fromName,
        #[Assert\NotBlank]
        private string $fromId,
        #[Assert\NotBlank]
        private string $message,
        #[Assert\NotBlank]
        private string $type,
        #[
        Assert\NotBlank,
        Assert\DateTime
        ]
        private DateTime $createdTime
    ) {
    }

    /**
     * @return DateTime
     */
    public function getCreatedTime(): DateTime
    {
        return $this->createdTime;
    }

    /**
     * @return string
     */
    public function getFromId(): string
    {
        return $this->fromId;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
