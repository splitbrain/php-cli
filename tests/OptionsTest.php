<?php

namespace splitbrain\phpcli\tests;

class Options extends \splitbrain\phpcli\Options
{

    public $args;
}

class OptionsTest extends \PHPUnit\Framework\TestCase
{

    function test_simpleshort()
    {
        $options = new Options();
        $options->registerOption('exclude', 'exclude files', 'x', 'file');

        $options->args = array('-x', 'foo', 'bang');
        $options->parseOptions();

        $this->assertEquals('foo', $options->getOpt('exclude'));
        $this->assertEquals(array('bang'), $options->args);
        $this->assertFalse($options->getOpt('nothing'));
    }

    function test_simplelong1()
    {
        $options = new Options();
        $options->registerOption('exclude', 'exclude files', 'x', 'file');

        $options->args = array('--exclude', 'foo', 'bang');
        $options->parseOptions();

        $this->assertEquals('foo', $options->getOpt('exclude'));
        $this->assertEquals(array('bang'), $options->args);
        $this->assertFalse($options->getOpt('nothing'));
    }

    function test_simplelong2()
    {
        $options = new Options();
        $options->registerOption('exclude', 'exclude files', 'x', 'file');

        $options->args = array('--exclude=foo', 'bang');
        $options->parseOptions();

        $this->assertEquals('foo', $options->getOpt('exclude'));
        $this->assertEquals(array('bang'), $options->args);
        $this->assertFalse($options->getOpt('nothing'));
    }

    function test_complex()
    {
        $options = new Options();

        $options->registerOption('plugins', 'run on plugins only', 'p');
        $options->registerCommand('status', 'display status info');
        $options->registerOption('long', 'display long lines', 'l', false, 'status');

        $options->args = array('-p', 'status', '--long', 'foo');
        $options->parseOptions();

        $this->assertEquals('status', $options->getCmd());
        $this->assertTrue($options->getOpt('plugins'));
        $this->assertTrue($options->getOpt('long'));
        $this->assertEquals(array('foo'), $options->args);
    }
}
