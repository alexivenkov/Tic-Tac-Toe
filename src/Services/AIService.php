<?php

namespace App\Services;


use App\Entity\Game;

class AIService implements MoveInterface
{
    use GameUtils;

    /** @var Game $game */
    private $game;

    /**
     * @param Game $game
     */
    public function setGame(Game $game) {
        $this->game = $game;
    }

    /**
     * @param array  $boardState
     * @param string $unit
     *
     * @return array
     */
    public function makeMove(array $boardState, string $unit = 'X'): array
    {
        $move = $this->getBestMove($boardState, $unit);

        return [$move['index'][0], $move['index'][1], $unit];
    }

    /**
     * @param array  $boardState
     * @param string $unit
     *
     * @return array
     */
    public function getBestMove(array $boardState, string $unit): array
    {
        $playerUnit = $this->game->getFirstPlayer()->getUnit();
        $aiUnit = $this->game->getSecondPlayer()->getUnit();

        $emptyCells = $this->getEmptyCells($boardState);

        if ($this->checkWinner($boardState, $playerUnit)) {
            return ['score' => -10];
        } elseif ($this->checkWinner($boardState, $aiUnit)) {
            return ['score' => 10];
        } elseif (count($emptyCells) === 0) {
            return ['score' => 0];
        }

        $moves = [];

        foreach ($emptyCells as $emptyCell) {
            $move['index'] = $emptyCell;
            $boardState[$emptyCell[1]][$emptyCell[0]] = $unit;

            $result = $this->getBestMove($boardState, $unit === $playerUnit ? $aiUnit : $playerUnit);
            $move['score'] = $result['score'];

            $boardState[$emptyCell[1]][$emptyCell[0]] = '';
            $moves[] = $move;
        }

        $bestMove = null;

        if ($unit === $aiUnit) {
            $bestScore = -10000;

            for ($i = 0; $i < count($moves); $i++) {
                if ($moves[$i]['score'] > $bestScore) {
                    $bestScore = $moves[$i]['score'];
                    $bestMove = $i;
                }
            }
        } else {
            $bestScore = 10000;

            for ($i = 0; $i < count($moves); $i++) {
                if ($moves[$i]['score'] < $bestScore) {
                    $bestScore = $moves[$i]['score'];
                    $bestMove = $i;
                }
            }
        }

        return $moves[$bestMove];
    }
}
