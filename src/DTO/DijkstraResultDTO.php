<?php

namespace App\DTO;

class DijkstraResultDTO
{
    /**
     * @param int $distance
     * @param array $path
     */
    public function __construct(private int $distance, private array $path) {}

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * @return array
     */
    public function getPath(): array
    {
        return $this->path;
    }
}