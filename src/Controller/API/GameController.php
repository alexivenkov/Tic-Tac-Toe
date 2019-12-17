<?php

namespace App\Controller\API;

use App\Entity\Game;
use App\Entity\MovePoint;
use App\Entity\User;
use App\Services\GameService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GameController extends AbstractFOSRestController
{
    //
    //@todo validate all requests         Ошибки: игра не найдена, клетка занята, не правильные параметры реквеста
    // Чекнуть paramConverter для MovePoint
    //@todo provide exceptions
    //@todo refactor bot logic
    //@todo tests
    //@todo async await on client api calls
    //@todo check game winner/draw

    /**
     * @Rest\Post("/init", name="game.init")
     *
     * @param Request     $request
     * @param GameService $service
     *
     * @return View
     */
    public function init(Request $request, GameService $service): View
    {
        $playerUnit = $request->get('playerUnit');
        $newGame = $service->init($playerUnit);

        return View::create([
            'id'          => $newGame->getId(),
            'board_state' => $newGame->getBoardState()
        ]);
    }

    /**
     * @Rest\Post("/game/{id}/move", name="game.move")
     * @ParamConverter("movePoint", converter="fos_rest.request_body")
     *
     * @param Game                             $game
     * @param MovePoint                        $movePoint
     * @param ConstraintViolationListInterface $validationErrors
     * @param GameService                      $service
     *
     * @return View
     */
    public function move(
        Game $game,
        MovePoint $movePoint,
        ConstraintViolationListInterface $validationErrors,
        GameService $service
    ): View {
        if (count($validationErrors) > 0) {
            return View::create($validationErrors, Response::HTTP_BAD_REQUEST);
        }

        $service->validate($game, $movePoint);
        $updatedGame = $service->updateBoardState($game, $movePoint);

        return View::create([
            'board_state'      => $updatedGame->getBoardState(),
            'terminate_status' => $service->getTerminateState($updatedGame->getBoardState()),
        ], Response::HTTP_OK);
    }
}
