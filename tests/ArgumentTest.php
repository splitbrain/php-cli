<?php

namespace splitbrain\phpcli\tests;

class argumentOptions extends \splitbrain\phpcli\Options
{
    public $args;
}

class ArgumentTest extends \PHPUnit\Framework\TestCase
{
    private function getOptions() : commandOptions
    {
        $options = new commandOptions();

        $options->registerCommand('status', 'display status info');
        $options->registerCommand('execute', 'execute an action');

        // test an option only for status command
        $options->registerOption('server', 'server property', 's', 'server', 'status');

        // register an argument for both commands
        $options->registerArgument('flag', 'display a flag', true, ['status','execute']);

        // register an argument for status command
        $options->registerArgument('flavor', 'latte flavor', true, 'status');

        // register an argument for execute command
        $options->registerArgument('size', 'size of latte', false, 'execute');

        return $options;
    }


    public function test_args()
    {
        $options = $this->getOptions();

        // command, flag, flavor, size
        $options->args = array('status', '--server=123', '1', 'pumpkin-spice', 'grande' );
        $options->parseOptions();

        $this->assertEquals('status', $options->getCmd());
        $this->assertEquals('123', $options->getOpt('server' ) );
        $this->assertEquals('1', $options->getArgs()[0]);
        $this->assertEquals('pumpkin-spice', $options->getArgs()[1]);
        $this->assertEquals('grande', $options->getArgs()[2]);
    }


    public function test_args_exception()
    {
        $this->expectException( 'splitbrain\phpcli\Exception' );
        $options = $this->getOptions();

        // command, flag, flavor, size
        $options->args = array('status', '--notset=123', '1', 'pumpkin-spice', 'grande' );
        $options->parseOptions();
    }


    public function test_args_command_exception()
    {
        // in my opinion, this test should fail because we pass arguments that aren't defined
        $options = $this->getOptions();

        // command, flag, flavor, size
        $options->args = array('execute', '1', 'pumpkin-spice', 'grande', 'adsf' );
        $options->parseOptions();
        $this->assertEquals('1', $options->getArgs()[0]);
    }
}