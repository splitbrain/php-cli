<?php

namespace splitbrain\phpcli;

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
    const C_RED = 'red';
    const C_GREEN = 'green';
    const C_YELLOW = 'yellow';
    const C_BLUE = 'blue';
    const C_MAGENTA = 'magenta';
    const C_CYAN = 'cyan';
    const C_WHITE = 'white';

    const C_LIGHT_BLACK = 'lightblack';
    const C_LIGHT_RED = 'lightred';
    const C_LIGHT_GREEN = 'lightgreen';
    const C_LIGHT_YELLOW = 'lightyellow';
    const C_LIGHT_BLUE = 'lightblue';
    const C_LIGHT_MAGENTA = 'lightmagenta';
    const C_LIGHT_CYAN = 'lightcyan';
    const C_LIGHT_WHITE = 'lightwhite';

    const C_BLACK_BACKGROUND = 'blackbackground';
    const C_RED_BACKGROUND = 'redbackground';
    const C_GREEN_BACKGROUND = 'greenbackground';
    const C_YELLOW_BACKGROUND = 'yellowbackground';
    const C_BLUE_BACKGROUND = 'bluebackground';
    const C_MAGENTA_BACKGROUND = 'magentabackground';
    const C_CYAN_BACKGROUND = 'cyanbackground';
    const C_WHITE_BACKGROUND = 'whitebackground';

    const C_LIGHT_BLACK_BACKGROUND = 'lightblackbackground';
    const C_LIGHT_RED_BACKGROUND = 'lightredbackground';
    const C_LIGHT_GREEN_BACKGROUND = 'lightgreenbackground';
    const C_LIGHT_YELLOW_BACKGROUND = 'lightyellowbackground';
    const C_LIGHT_BLUE_BACKGROUND = 'lightbluebackground';
    const C_LIGHT_MAGENTA_BACKGROUND = 'lightmagentabackground';
    const C_LIGHT_CYAN_BACKGROUND = 'lightcyanbackground';
    const C_LIGHT_WHITE_BACKGROUND = 'lightwhitebackground';

    const C_BOLD = 'bold';
    const C_UNDERLINE = 'underline';
    const C_REVERSED = 'reversed';

    /** @var array known color names */
    protected $colors = array(
        self::C_RESET => "\33[0m",

        self::C_BLACK => "\33[30m",
        self::C_RED => "\33[31m",
        self::C_GREEN => "\33[32m",
        self::C_YELLOW => "\33[33m",
        self::C_BLUE => "\33[34m",
        self::C_MAGENTA => "\33[35m",
        self::C_CYAN => "\33[36m",
        self::C_WHITE => "\33[37m",

        self::C_LIGHT_BLACK => "\33[1;30m",
        self::C_LIGHT_RED => "\33[1;31m",
        self::C_LIGHT_GREEN => "\33[1;32m",
        self::C_LIGHT_YELLOW => "\33[1;33m",
        self::C_LIGHT_BLUE => "\33[1;34m",
        self::C_LIGHT_MAGENTA => "\33[1;35m",
        self::C_LIGHT_CYAN => "\33[1;36m",
        self::C_LIGHT_WHITE => "\33[1;37m",

        self::C_BLACK_BACKGROUND => "\33[40m",
        self::C_RED_BACKGROUND => "\33[41m",
        self::C_GREEN_BACKGROUND => "\33[42m",
        self::C_YELLOW_BACKGROUND => "\33[43m",
        self::C_BLUE_BACKGROUND => "\33[44m",
        self::C_MAGENTA_BACKGROUND => "\33[45m",
        self::C_CYAN_BACKGROUND => "\33[46m",
        self::C_WHITE_BACKGROUND => "\33[47m",

        self::C_LIGHT_BLACK_BACKGROUND => "\33[1;40m",
        self::C_LIGHT_RED_BACKGROUND => "\33[1;41m",
        self::C_LIGHT_GREEN_BACKGROUND => "\33[1;42m",
        self::C_LIGHT_YELLOW_BACKGROUND => "\33[1;43m",
        self::C_LIGHT_BLUE_BACKGROUND => "\33[1;44m",
        self::C_LIGHT_MAGENTA_BACKGROUND => "\33[1;45m",
        self::C_LIGHT_CYAN_BACKGROUND => "\33[1;46m",
        self::C_LIGHT_WHITE_BACKGROUND => "\33[1;47m",

        self::C_BOLD => "\33[1m",
        self::C_UNDERLINE => "\33[4m",
        self::C_REVERSED => "\33[7m",
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
        $this->set($color);
        fwrite($channel, $line . "\n");
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
     *
     * @throws Exception
     */
    public function reset($channel = STDOUT)
    {
        $this->set('reset', $channel);
    }

    /**
     * Prints text using color codes
     *
     * @param string $text string to print
     * @param resource $channel file descriptor to write to
     *
     * @throws Exception
     */
    public function print($text, $channel = STDOUT)
    {
        $active_colors = array();

        $text = preg_replace_callback('/\<(.[^\>]*?)\>/', function ($matches) use (&$active_colors) {
            $new_color = $matches[1];
            $colors = array();

            if (!isset($this->colors[$new_color]) && !isset($this->colors[substr($new_color, 1)])) {
                return "<$new_color>";
            }

            if (substr($new_color, 0, 1) === '/') {
                array_pop($active_colors);
                $colors[] = 'reset';
            } else {
                $active_colors[] = $new_color;
            }

            $colors = array_merge($colors, $active_colors);

            return implode('', array_map(function ($color) {
                return $this->getColorCode($color);
            }, $colors));
        }, $text);

        fwrite($channel, $text . "\n");

        if (end($active_colors) !== 'reset') {
            $this->reset($channel);
        }
    }
}
