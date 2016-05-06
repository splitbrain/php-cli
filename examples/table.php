#!/usr/bin/php
<?php

namespace splitbrain\phpcli\examples;

require __DIR__ . '/../vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Colors;
use splitbrain\phpcli\Options;
use splitbrain\phpcli\TableFormatter;

class Table extends CLI
{

    /**
     * Register options and arguments on the given $options object
     *
     * @param Options $options
     * @return void
     */
    protected function setup(Options $options)
    {
        $options->setHelp('This shows how the table formatter works by printing the current php.ini values');
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
        $tf = new TableFormatter($this->colors);
        $tf->setBorder(' | '); // nice border between colmns

        // show a header
        echo $tf->format(
            array('*', '30%', '30%'),
            array('ini setting', 'global', 'local')
        );

        // a line across the whole width
        echo str_pad('', $tf->getMaxWidth(), '-') . "\n";

        // colored columns
        $ini = ini_get_all();
        foreach ($ini as $val => $opts) {
            echo $tf->format(
                array('*', '30%', '30%'),
                array($val, $opts['global_value'], $opts['local_value']),
                array(Colors::C_CYAN, Colors::C_RED, Colors::C_GREEN)
            );
        }
    }
}

$cli = new Table();
$cli->run();