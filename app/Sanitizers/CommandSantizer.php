<?php


namespace App\Sanitizers;


class CommandSantizer
{
    private const ILLEGALE_COMMANDS = [
        'exit',
        '&',
        'leave',
        'quit',
        '^'
    ];

    /**
     * @var string
     */
    private $command;

    /**
     * @var boolean
     */
    private $valid;

    public function __construct(string $command)
    {
        $this->command = $command;
        $this->checkIfAllowed();
    }

    /**
     * Checks if the command is allowed
     */
    private function checkIfAllowed(): void
    {
        foreach (self::ILLEGALE_COMMANDS as $COMMAND) {
            if (strpos($this->command, $COMMAND) !== false) {
                $this->valid = false;
                return;
            }
        }

        $this->valid = true;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->valid;
    }
}
