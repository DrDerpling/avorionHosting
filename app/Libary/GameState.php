<?php


namespace App\Libary;


class GameState
{
    /**
     * @var boolean
     */
    public $running;

    /**
     * @var string
     */
    public $error;

    /**
     * @var string
     */
    public $state;

    /**
     * @var boolean
     */
    public $hasError;

    /**
     * @var string
     */
    private $screenName;

    public function __construct(string $screenName)
    {
        $this->screenName = $screenName;
        $this->state = $this->checkIfRunning() ? 'Server is running': 'Server is not running';
    }

    /**
     * Checks if the server is running
     * @return bool
     */
    public function checkIfRunning(): bool
    {
        exec('screen -ls', $screenLS);
        $screenLs = implode('', $screenLS);
        $this->running = (stripos($screenLs, $this->screenName) !== false);

        return $this->running;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->hasError = strlen($error) > 0;
        $this->error = $error;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }
}
