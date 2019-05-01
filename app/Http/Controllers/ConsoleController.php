<?php

namespace App\Http\Controllers;

use Illuminate\Hcctp\JsonResponse;
use Illuminate\Http\Request;

class ConsoleController extends Controller
{
    /**
     * @param int $lines
     * @return JsonResponse
     */
    public function getConsoleOutput(int $lines = 24)
    {
        $command = sprintf('tail ../storage/avorion/server.log -n %s', $lines);
        exec($command, $output);

        return response()->json(['lines' => $output], 200);
    }
}
