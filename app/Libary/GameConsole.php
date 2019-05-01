<?php


namespace App\Libary;


use App\Sanitizers\CommandSantizer;

class GameConsole
{
    /**
     * @var string
     */
    private $screenName;
    /**
     * @var string
     */
    private $configFile;
    /**
     * @var string
     */
    private $galaxyName;
    /**
     * @var string
     */
    private $dataPath;
    /**
     * @var string
     */
    private $serverSh;

    /**
     * @var GameState
     */
    public $gameState;


    /**
     * GameConsole constructor.
     * @param string $screenName
     * @param string $configFile
     * @param string $galaxyName
     * @param string $dataPath
     * @param string $serverSh
     */
    public function __construct(
        $screenName = 'avorion',
        $configFile = '/home/dlindeboom/projects/avorionHosting/storage/avorion/default.conf',
        $galaxyName = 'Ygdrasil',
        $dataPath = 'galaxies',
        $serverSh = '/var/www/avorion.bearhosting.nl/avorion/server.sh'
    )
    {
        $this->screenName = $screenName;
        $this->configFile = $configFile;
        $this->galaxyName = $galaxyName;
        $this->dataPath = $dataPath;
        $this->serverSh = $serverSh;
        $this->gameState = new GameState($this->screenName);
        $this->gameState->checkIfRunning();
    }

    public function startServer(): GameState
    {
        if ($this->gameState->checkIfRunning()) {
            $this->gameState->setError('server is already running');
            return $this->gameState;
        }

        $command = 'screen -c %s -dmSL \'%s\' %s --galaxy-name=%s --datapath=%s';
        $command = sprintf($command,
            $this->configFile,
            $this->screenName,
            $this->serverSh,
            $this->galaxyName,
            $this->dataPath
        );
        // To launch the app:
        exec($command);

        if ($this->gameState->checkIfRunning()) {
            $this->gameState->setState('Server is Running');
        } else {
            $this->gameState->setError('Was unable to start server');
        }

        return $this->gameState;
    }

    public function forceKillServer()
    {
        if ($this->gameState->checkIfRunning()) {
            $this->gameState->setError('Server is not running');
            return $this->gameState;
        }

        exec('killall screen');

        if ($this->gameState->checkIfRunning()) {
            $this->gameState->setError('Server is still running');
        } else {
            $this->gameState->setState('Server has stopped');
        }

        return $this->gameState;
    }

    public function stopServer(): GameState
    {
        $this->sendCommandToServer('stop', false);
        print_r(exec(' > /home/dlindeboom/projects/avorionHosting/storage/avorion/server.log'));

        if ($this->gameState->checkIfRunning()) {
            $this->gameState->setError('Server is still running');
        } else {
            $this->gameState->setState('Server has stopped');
        }

        return $this->gameState;
    }

    /**
     * @param $command
     * @param bool $sanitize
     * @return GameState
     */
    public function sendCommandToServer($command, $sanitize = true): GameState
    {
        if ($sanitize) {
            $sanitizer = new CommandSantizer($command);

            if (!$sanitizer->valid()) {
                $this->gameState->setError('Command is not allowed');
                return $this->gameState;
            }
        }

        $template = 'screen -S %s -p 0 -X stuff "/%s^M"';
        $command = sprintf($template, $this->screenName, $command);

        exec($command);

        if ($this->gameState->checkIfRunning()) {
            $this->gameState->setState('Executed command');
        } else {
            $this->gameState->setError('Server has stopped');
        }

        return $this->gameState;
    }
}
