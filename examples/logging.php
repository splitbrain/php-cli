#!/usr/bin/php
<?php
require __DIR__ . '/../vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class logging extends CLI // you may want to extend from PSR3CLI instead
{
    // override the default log level
    protected $logdefault = 'debug';

    // register options and arguments
    protected function setup(Options $options)
    {
        $options->setHelp('A very minimal example that demos the logging');
    }

    // implement your code
    protected function main(Options $options)
    {
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

// execute it
$cli = new logging();
$cli->run();