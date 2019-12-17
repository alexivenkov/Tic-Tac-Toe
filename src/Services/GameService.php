<?php

namespace App\Services;

use App\Entity\Game;
use App\Entity\MovePoint;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GameService
{
    use GameUtils;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var AIService
     */
    private $botService;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $em,
        GameRepository $gameRepository,
        UserRepository $userRepository,
        AIService $botService
    ) {
        $this->em = $em;
        $this->gameRepository = $gameRepository;
        $this->botService = $botService;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $playerUnit
     *
     * @return Game
     */
    public function init(string $playerUnit): Game
    {
        $boardState = Game::EMPTY_BOARD_STATE;

        $botUnit = $playerUnit === Game::X_UNIT ? Game::O_UNIT : Game::X_UNIT;
        /** @var User $player */
        $player = $this->userRepository->findOneBy(['unit' => $playerUnit]);
        /** @var User $bot */
        $bot = $this->userRepository->findOneBy(['unit' => $botUnit]);

        $game = new Game();
        $game->setFirstPlayer($player);
        $game->setSecondPlayer($bot);

        if ($playerUnit === Game::O_UNIT) {
            $this->botService->setGame($game);
            $botMoveData = $this->botService->makeMove(Game::EMPTY_BOARD_STATE, $botUnit);
            $boardState[$botMoveData[1]][$botMoveData[0]] = $botMoveData[2];
        }

        $game->setBoardState($boardState);
        $this->em->persist($game);
        $this->em->flush();

        return $game;
    }

    /**
     * @param Game      $game
     * @param MovePoint $movePoint
     *
     * @return Game
     */
    public function updateBoardState(Game $game, MovePoint $movePoint): Game
    {
        $game = $this->playerMove($game, $movePoint);

        if (!$game->getIsFinished()) {
            $this->botService->setGame($game);
            $game = $this->botMove($game);
        }

        $this->em->persist($game);
        $this->em->flush();

        return $game;
    }

    /**
     * @param Game      $game
     * @param MovePoint $movePoint
     *
     * @return Game
     */
    private function playerMove(Game $game, MovePoint $movePoint): Game
    {
        $currentBoardState = $game->getBoardState();
        $currentBoardState[$movePoint->getY()][$movePoint->getX()] = $movePoint->getUnit();
        $game->setBoardState($currentBoardState);
        $game = $this->updateTerminateState($game, $game->getFirstPlayer());

        return $game;
    }

    /**
     * @param Game $game
     *
     * @return Game
     */
    private function botMove(Game $game): Game
    {
        $currentBoardState = $game->getBoardState();
        $botMove = $this->botService->makeMove($currentBoardState, $game->getSecondPlayer()->getUnit());
        $currentBoardState[$botMove[1]][$botMove[0]] = $botMove[2];
        $game->setBoardState($currentBoardState);
        $game = $this->updateTerminateState($game, $game->getSecondPlayer());

        return $game;
    }

    /**
     * @param Game $game
     * @param User $user
     *
     * @return Game
     */
    private function updateTerminateState(Game $game, User $user): Game
    {
        $terminateState = $this->getTerminateState($game->getBoardState());

        if ($this->checkWinner($game->getBoardState(), $user->getUnit())) {
            $game->setWinner($user);
            $game->setIsFinished(true);
        }

        if ($terminateState === Game::DRAW) {
            $game->setIsFinished(true);
        }

        return $game;
    }

    /**
     * @param Game      $game
     * @param MovePoint $movePoint
     *
     * @return void
     */
    public function validate(Game $game, MovePoint $movePoint): void
    {
        if ($game->getIsFinished()) {
            throw new BadRequestHttpException('Game:' . $game->getId() . ' is already finished');
        }

        $boardState = $game->getBoardState();

        if ($boardState[$movePoint->getY()][$movePoint->getX()] !== '') {
            throw new BadRequestHttpException('This cell is already occupied');
        }
    }
}
