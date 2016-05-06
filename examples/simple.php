#!/usr/bin/php
<?php

namespace splitbrain\phpcli\examples;

require __DIR__ . '/../vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Simple extends CLI
{

    /**
     * Register options and arguments on the given $options object
     *
     * @param Options $options
     * @return void
     */
    protected function setup(Options $options)
    {
        $options->setHelp('This is a simple example, not using any subcommands');
        $options->registerOption('longflag', 'A flag that can also be set with a short option', 'l');
        $options->registerOption('file', 'This option expects an argument.', 'f', 'filename');
        $options->registerArgument('argument', 'Arguments can be required or optional. This one is optional', false);
    }

    /**
     * Your main program
     *
     * Arguments and options have been parsed when this is run
     *
     * @param Options $options
     * @return void
     */
    protected function main(Options $options)
    {
        if ($options->getOpt('longflag')) {
            $this->info("longflag was set");
        } else {
            $this->info("longflag was not set");
        }

        if ($options->getOpt('file')) {
            $this->info("file was given as " . $options->getOpt('file'));
        }

        $this->info("Number of arguments: " . count($options->getArgs()));

        $this->success("main finished");
    }
}

$cli = new Simple();
$cli->run();