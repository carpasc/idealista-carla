<?php

declare(strict_types=1);

namespace App\Domain;

final class Picture
{
    public function __construct(
        private int $id,
        private String $url,
        private String $quality,
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param String $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return String
     */
    public function getQuality(): string
    {
        return $this->quality;
    }

    /**
     * @param String $quality
     */
    public function setQuality(string $quality): void
    {
        $this->quality = $quality;
    }
}
