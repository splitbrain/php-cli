<?php

namespace splitbrain\phpcli;

/**
 * Class CLI
 *
 * Your commandline script should inherit from this class and implement the abstract methods.
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
abstract class CLI
{
    /** @var string the executed script itself */
    protected $bin;
    /** @var  Options the option parser */
    protected $options;
    /** @var  Colors */
    public $colors;

    /**
     * constructor
     *
     * Initialize the arguments, set up helper classes and set up the CLI environment
     *
     * @param bool $autocatch should exceptions be catched and handled automatically?
     */
    public function __construct($autocatch=true)
    {
        if($autocatch) {
            set_exception_handler(array($this, 'fatal'));
        }

        $this->colors = new Colors();
        $this->options = new Options($this->colors);
    }

    /**
     * Register options and arguments on the given $options object
     *
     * @param Options $options
     * @return void
     */
    abstract protected function setup(Options $options);

    /**
     * Your main program
     *
     * Arguments and options have been parsed when this is run
     *
     * @param Options $options
     * @return void
     */
    abstract protected function main(Options $options);

    /**
     * Execute the CLI program
     *
     * Executes the setup() routine, adds default options, initiate the options parsing and argument checking
     * and finally executes main()
     */
    public function run()
    {
        if ('cli' != php_sapi_name()) {
            throw new Exception('This has to be run from the command line');
        }

        // setup
        $this->setup($this->options);
        $this->options->registerOption(
            'no-colors',
            'Do not use any colors in output. Useful when piping output to other tools or files.'
        );
        $this->options->registerOption(
            'help',
            'Display this help screen and exit immeadiately.',
            'h'
        );

        // parse
        $this->options->parseOptions();

        // handle defaults
        if ($this->options->getOpt('no-colors')) {
            $this->colors->disable();
        }
        if ($this->options->getOpt('help')) {
            echo $this->options->help();
            exit(0);
        }

        // check arguments
        $this->options->checkArguments();

        // execute
        $this->main($this->options);

        exit(0);
    }

    /**
     * Exits the program on a fatal error
     *
     * @param \Exception|string $error either an exception or an error message
     */
    public function fatal($error)
    {
        $code = 0;
        if (is_object($error) && is_a($error, 'Exception')) {
            /** @var Exception $error */
            $code = $error->getCode();
            $error = $error->getMessage();
        }
        if (!$code) {
            $code = Exception::E_ANY;
        }

        $this->error($error);
        exit($code);
    }

    /**
     * Print an error message
     *
     * @param string $string
     */
    public function error($string)
    {
        $this->colors->ptln("E: $string", 'red', STDERR);
    }

    /**
     * Print a success message
     *
     * @param string $string
     */
    public function success($string)
    {
        $this->colors->ptln("S: $string", 'green', STDERR);
    }

    /**
     * Print an info message
     *
     * @param string $string
     */
    public function info($string)
    {
        $this->colors->ptln("I: $string", 'cyan', STDERR);
    }

}
