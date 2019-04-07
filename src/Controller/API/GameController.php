<?php

namespace App\Controller\API;

use App\Entity\MoveData;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GameController extends AbstractFOSRestController
{
    /**
     * @Rest\Post("/make-move", name="make-move")
     * @param Request            $request
     * @param ValidatorInterface $validator
     *
     * @return View
     */
    public function makeMove(Request $request, ValidatorInterface $validator): View
    {
        $moveData = new MoveData();
        $moveData->boardState = $request->get('board');
        $moveData->playerUnit = $request->get('playerUnit');
        $errors = $validator->validate($moveData);

        return View::create([
            'errors' => (string) $errors
        ], Response::HTTP_OK);
    }
}
