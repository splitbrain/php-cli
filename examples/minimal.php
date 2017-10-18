#!/usr/bin/php
<?php
require __DIR__ . '/../vendor/autoload.php';
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Minimal extends CLI
{
    // register options and arguments
    protected function setup(Options $options)
    {
        $options->setHelp('A very minimal example that prints a version or demos the logging');
        $options->registerOption('version', 'print version', 'v');
    }

    // implement your code
    protected function main(Options $options)
    {
        if ($options->getOpt('version')) {
            $this->info('1.0.0');
        } else {
            $this->debug('This is a debug message');
            $this->info('This is a info message');
            $this->notice('This is a notice message');
            $this->success('This is a success message');
            $this->warning('This is a warning message');
            $this->error('This is a error message');
            $this->critical('This is a critical message');
            $this->alert('This is a alert message');
            $this->emergency('This is a emergency message');
            throw new \Exception('Exception will be caught, too');
        }
    }
}
// execute it
$cli = new Minimal();
$cli->run();