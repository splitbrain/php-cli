<?php

namespace splitbrain\phpcli;

use JakubOnderka\PhpConsoleColor\ConsoleColor;
use Hexmode\IOMode\IOMode;

/**
 * Class Colors
 *
 * Handles color output on (Linux) terminals
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @license MIT
 */
class Colors
{
    // these constants make IDE autocompletion easier, but color names can also be passed as strings
    const C_RESET = 'reset';
    const C_BLACK = 'black';
    const C_DARKGRAY = 'dark_gray';
    const C_BLUE = 'blue';
    const C_LIGHTBLUE = 'light_blue';
    const C_GREEN = 'green';
    const C_LIGHTGREEN = 'light_green';
    const C_CYAN = 'cyan';
    const C_LIGHTCYAN = 'light_cyan';
    const C_RED = 'red';
    const C_LIGHTRED = 'light_red';
    const C_MAGENTA = 'magenta';
    const C_LIGHTMAGENTA = 'light_magenta';
    const C_PURPLE = 'magenta';
    const C_LIGHTPURPLE = 'light_magenta';
    const C_YELLOW = 'yellow';
    const C_LIGHTYELLOW = 'light_yellow';
    const C_LIGHTGRAY = 'light_gray';
    const C_WHITE = 'white';

    /** @var bool should colors be used? */
    protected $enabled = true;

    /** @var ConsoleColor $console */
    protected $console;

    /**
     * Constructor
     *
     * Tries to disable colors for non-terminals
     */
    public function __construct()
    {
        $this->console = new ConsoleColor();
        $ioMode = new IOMode( STDOUT );
        if ( $ioMode->tty() === false || $ioMode->fifo() ) {
            $this->enabled = false;
            return;
        }
        if ( $this->console->isSupported() ) {
            $this->enabled = false;
            return;
        }
    }

    /**
     * enable color output
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * disable color output
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * @return bool is color support enabled?
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Convenience function to print a line in a given color
     *
     * @param string   $line    the line to print, a new line is added automatically
     * @param string   $color   one of the available color names
     * @param resource $channel file descriptor to write to
     *
     * @throws Exception
     */
    public function ptln($line, $color, $channel = STDOUT)
    {
        fwrite(
            $channel, $this->console->apply( $color, rtrim( $line ) . "\n" )
        );
    }

    /**
     * Returns the given text wrapped in the appropriate color and
     * reset code
     *
     * @param string $text string to wrap
     * @param string $color one of the available color names
     * @return string the wrapped string
     * @throws Exception
     */
    public function wrap( $text, $color ) {
        return $this->console->apply( $color, $text );
    }
}
