<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    public const X_UNIT = 'X';
    public const O_UNIT = 'O';

    public const DRAW = 'draw';

    public const EMPTY_BOARD_STATE = [
        ['', '', ''],
        ['', '', ''],
        ['', '', '']
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json_array")
     */
    private $board_state;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $winner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $firstPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $secondPlayer;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $is_finished = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getBoardState(): array
    {
        return $this->board_state;
    }

    /**
     * @param $board_state
     *
     * @return Game
     */
    public function setBoardState($board_state): self
    {
        $this->board_state = $board_state;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWinner(): ?User
    {
        return $this->winner;
    }

    /**
     * @param User|null $winner
     *
     * @return Game
     */
    public function setWinner(?User $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * @return User
     */
    public function getFirstPlayer(): User
    {
        return $this->firstPlayer;
    }

    /**
     * @param User $player
     *
     * @return Game
     */
    public function setFirstPlayer(User $player): self
    {
        $this->firstPlayer = $player;

        return $this;
    }

    /**
     * @return User
     */
    public function getSecondPlayer(): User
    {
        return $this->secondPlayer;
    }

    /**
     * @param User $player
     *
     * @return Game
     */
    public function setSecondPlayer(User $player): self
    {
        $this->secondPlayer = $player;

        return $this;
    }

    public function getIsFinished(): ?bool
    {
        return $this->is_finished;
    }

    public function setIsFinished(bool $is_finished): self
    {
        $this->is_finished = $is_finished;

        return $this;
    }
}
