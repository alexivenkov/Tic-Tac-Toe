<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class MovePoint
{
    /**
     * @Assert\NotBlank(message="empty x coordinate")
     *
     * @var integer
     */
    private $x;

    /**
     * @Assert\NotBlank(message="empty y coordinate")
     *
     * @var integer
     */
    private $y;

    /**
     * @Assert\NotBlank(message="empty unit")
     *
     * @var string
     */
    private $unit;

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX(int $x): void
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY(int $y): void
    {
        $this->y = $y;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }
}
