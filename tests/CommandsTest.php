<?php

namespace splitbrain\phpcli\tests;

class commandOptions extends \splitbrain\phpcli\Options
{
    public $args;
}

class CommandsTest extends \PHPUnit\Framework\TestCase
{
    private function getOptions() : commandOptions
    {
        $options = new commandOptions();

        $options->registerCommand('status', 'display status info');
        $options->registerCommand('execute', 'execute an action');
        $options->registerOption('long', 'display long lines', 'l', false, ['status','execute']);

        // test an option only for status command
        $options->registerOption('server', 'server property', 's', 'server', 'status');

        // test an option only for execute command
        $options->registerOption('username', 'username property', 'u', 'username', 'execute');

        return $options;
    }


    public function test_command()
    {
        $options = $this->getOptions();

        $options->args = array('status', '--long', 'foo');
        $options->parseOptions();

        $this->assertEquals('status', $options->getCmd());
        $this->assertTrue($options->getOpt('long'));
        $this->assertEquals(array('foo'), $options->args);
    }


    public function test_command_again()
    {
        $options = $this->getOptions();

        $options->args = array('execute', '--long', 'foo');
        $options->parseOptions();

        $this->assertEquals('execute', $options->getCmd());
        $this->assertTrue($options->getOpt('long'));
        $this->assertEquals(array('foo'), $options->args);
    }


    public function test_command_exception()
    {
        $this->expectException( 'splitbrain\phpcli\Exception' );
        $options = $this->getOptions();

        // should throw exception for an unregistered command
        $options->args = array('foo', '--long', 'foo', '--username=123');
        $options->parseOptions();
    }


    public function test_argument_exception()
    {
        $this->expectException( 'splitbrain\phpcli\Exception' );
        $options = $this->getOptions();

        // should throw exception
        $options->args = array('status', '-u','123' );
        $options->parseOptions();
    }


}