<?php

namespace splitbrain\phpcli;

/**
 * Class Colors
 *
 * Handles color output on (Linux) terminals
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
class Colors
{
    /** @var array known color names */
    protected $colors = array(
        'reset' => "\33[0m",
        'black' => "\33[0;30m",
        'darkgray' => "\33[1;30m",
        'blue' => "\33[0;34m",
        'lightblue' => "\33[1;34m",
        'green' => "\33[0;32m",
        'lightgreen' => "\33[1;32m",
        'cyan' => "\33[0;36m",
        'lightcyan' => "\33[1;36m",
        'red' => "\33[0;31m",
        'lightred' => "\33[1;31m",
        'purple' => "\33[0;35m",
        'lightpurple' => "\33[1;35m",
        'brown' => "\33[0;33m",
        'yellow' => "\33[1;33m",
        'lightgray' => "\33[0;37m",
        'white' => "\33[1;37m",
    );

    /** @var bool should colors be used? */
    protected $enabled = true;

    /**
     * Constructor
     *
     * Tries to disable colors for non-terminals
     */
    public function __construct()
    {
        if (function_exists('posix_isatty') && !posix_isatty(STDOUT)) {
            $this->enabled = false;
            return;
        }
        if (!getenv('TERM')) {
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
     * Convenience function to print a line in a given color
     *
     * @param string $line the line to print, a new line is added automatically
     * @param string $color one of the available color names
     * @param resource $channel file descriptor to write to
     */
    public function ptln($line, $color, $channel = STDOUT)
    {
        $this->set($color);
        fwrite($channel, rtrim($line) . "\n");
        $this->reset();
    }

    /**
     * Returns the given text wrapped in the appropriate color and reset code
     *
     * @param string $text string to wrap
     * @param string $color one of the available color names
     * @return string the wrapped string
     * @throws Exception
     */
    public function wrap($text, $color)
    {
        return $this->getColorCode($color) . $text . $this->getColorCode('reset');
    }

    /**
     * Gets the appropriate terminal code for the given color
     *
     * @param string $color one of the available color names
     * @return string color code
     * @throws Exception
     */
    public function getColorCode($color)
    {
        if (!$this->enabled) {
            return '';
        }
        if (!isset($this->colors[$color])) {
            throw new Exception("No such color $color");
        }

        return $this->colors[$color];
    }

    /**
     * Set the given color for consecutive output
     *
     * @param string $color one of the supported color names
     * @param resource $channel file descriptor to write to
     * @throws Exception
     */
    public function set($color, $channel = STDOUT)
    {
        fwrite($channel, $this->getColorCode($color));
    }

    /**
     * reset the terminal color
     *
     * @param resource $channel file descriptor to write to
     */
    public function reset($channel = STDOUT)
    {
        $this->set('reset', $channel);
    }
}
