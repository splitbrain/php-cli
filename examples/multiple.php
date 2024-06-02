#!/usr/bin/php
<?php

namespace splitbrain\phpcli\examples;

require __DIR__ . '/../vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Multiple extends CLI
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
        $options->registerOption('multiple', 'This option can be specified multiple times.', 'm', true, '', true);
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
        if ($options->getOpt('multiple')) {
            $this->info("multiple was given as " . implode(", ", (array) $options->getOpt('multiple')));
        }

        $this->info("Number of arguments: " . count($options->getArgs()));

        $this->success("main finished");
    }
}

$cli = new Multiple();
$cli->run();
