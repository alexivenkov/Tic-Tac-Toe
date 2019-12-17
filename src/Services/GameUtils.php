<?php

namespace App\Services;

use App\Entity\Game;

trait GameUtils
{
    /**
     * @param array $boardState
     * @param string $unit
     *
     * @return bool
     */
    public function checkWinner(array $boardState, string $unit): bool
    {
        if (
            //check rows
            ($boardState[0][0] === $unit && $boardState[0][1] === $unit && $boardState[0][2] === $unit) ||
            ($boardState[1][0] === $unit && $boardState[1][1] === $unit && $boardState[1][2] === $unit) ||
            ($boardState[2][0] === $unit && $boardState[2][1] === $unit && $boardState[2][2] === $unit) ||
            //check columns
            ($boardState[0][0] === $unit && $boardState[1][0] === $unit && $boardState[2][0] === $unit) ||
            ($boardState[0][1] === $unit && $boardState[1][1] === $unit && $boardState[2][1] === $unit) ||
            ($boardState[0][2] === $unit && $boardState[1][2] === $unit && $boardState[2][2] === $unit) ||
            //check diagonals
            ($boardState[0][0] === $unit && $boardState[1][1] === $unit && $boardState[2][2] === $unit) ||
            ($boardState[0][2] === $unit && $boardState[1][1] === $unit && $boardState[2][0] === $unit)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param array $boardState
     *
     * @return string
     */
    public function getTerminateState(array $boardState): ?string
    {
        if ($this->checkWinner($boardState, Game::X_UNIT)) {
            return Game::X_UNIT;
        }

        if ($this->checkWinner($boardState, Game::O_UNIT)) {
            return Game::O_UNIT;
        };

        if (empty($this->getEmptyCells($boardState))) {
            return Game::DRAW;
        }

        return null;
    }

    /**
     * @param array $boardState
     *
     * @return array
     */
    public function getEmptyCells(array $boardState): array
    {
        $coords = [];

        foreach ($boardState as $rowNum => $row) {
            foreach ($row as $cellNum => $cell) {
                if (empty($cell)) {
                    array_push($coords, [$cellNum, $rowNum]);
                }
            }
        }

        return $coords;
    }
}
