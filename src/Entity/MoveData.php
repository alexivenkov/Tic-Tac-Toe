<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class MoveData
{
    /**
     * @Assert\NotBlank(message="empty board")
     */
    public $boardState;

    /**
     * @Assert\NotBlank(message="empty player unit")
     */
    public $playerUnit;
}