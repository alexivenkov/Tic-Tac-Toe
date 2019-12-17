<?php

namespace App\Services;


interface MoveInterface
{
    /**
     * @param array  $boardState
     * @param string $playerUnit
     *
     * @return array
     */
    public function makeMove(array $boardState, string $playerUnit = 'X'): array;
}
