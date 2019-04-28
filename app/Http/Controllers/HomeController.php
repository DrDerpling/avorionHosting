<?php

namespace App\Http\Controllers;

use function Couchbase\defaultDecoder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function startServer()
    {
        //Define a screen name, may be whatever, but must be unique
        $myScreenName = 'avorion';
        $configFile = '/home/dlindeboom/projects/avorionHosting/storage/avorion/default.conf';
        $galaxyName = 'Ygdrasil';
        $dataPath = 'galaxies';
        $serverSh = '/var/www/avorion.bearhosting.nl/avorion/server.sh';


        if (!$this->testIfActive($myScreenName)) {
            $command = 'screen -c %s -dmSL \'%s\' %s --galaxy-name=%s --datapath=%s';
            $command = sprintf($command, $configFile, $myScreenName, $serverSh, $galaxyName, $dataPath);
            // To launch the app:
            echo exec($command);
            dd($command);
        }

//        $file = file_get_contents();  
    }

    public function kilServer()
    {
        $myScreenName = 'avorion';

        if ($this->testIfActive($myScreenName)) {
            echo exec('killall screen & echo "Killed al servers"');
        }
    }

    private function testIfActive($myScreenName)
    {
        exec('screen -ls', $screenLS);
        $screenLs = implode('', $screenLS);
        return (stripos($screenLs, $myScreenName) !== false);
    }
}
