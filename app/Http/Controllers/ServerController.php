<?php

namespace App\Http\Controllers;

use App\Libary\GameConsole;
use App\Sanitizers\CommandSantizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServerController extends Controller
{
    /**
     * @var GameConsole
     */
    private $gameConsole;

    /**
     * ServerController constructor.
     * @param GameConsole $gameConsole
     */
    public function __construct(GameConsole $gameConsole)
    {
        $this->gameConsole = $gameConsole;
    }

    /**
     * @return JsonResponse
     */
    public function startServer()
    {
        $gameState = $this->gameConsole->startServer();

        if ($gameState->hasError) {
            return response()->json(['error' => $gameState->error], 400);
        }

        return response()->json(['message' => $gameState->state], 200);
    }

    /**
     * @return JsonResponse
     */
    public function forceKilServer()
    {
        $gameState = $this->gameConsole->forceKillServer();

        if ($gameState->hasError) {
            return response()->json(['error' => $gameState->error], 400);
        }

        return response()->json(['message' => $gameState->state], 200);
    }

    public function stopServer()
    {
        $gameState = $this->gameConsole->stopServer();

        if ($gameState->hasError) {
            return response()->json(['error' => $gameState->error], 400);
        }

        return response()->json(['message' => $gameState->state], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function sendCommandToServer(Request $request)
    {
        $this->validate($request, [
            'command' => 'required'
        ]);

        $command = $request->input('command');

        $gameState = $this->gameConsole->sendCommandToServer($command);

        if ($gameState->hasError) {
            return response()->json(['error' => $gameState->error], 400);
        }

        return response()->json(['message' => $gameState->state], 200);
    }

    public function serverStatus()
    {
        $running = $this->gameConsole->gameState->running;

        return response()->json(['status' => $running]);
    }
}
