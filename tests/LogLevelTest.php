<?php

namespace splitbrain\phpcli\tests;


use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class LogLevel extends CLI
{
    protected function setup(Options $options)
    {
    }

    protected function main(Options $options)
    {
    }
}

class LogLevelTest extends \PHPUnit\Framework\TestCase
{


    public function provideLevels()
    {
        return array(
            array(
                'debug',
                array('debug', 'info', 'notice', 'success', 'warning', 'error', 'critical', 'alert', 'emergency'),
                array(),
            ),
            array(
                'info',
                array('info', 'notice', 'success', 'warning', 'error', 'critical', 'alert', 'emergency'),
                array('debug'),
            ),
            array(
                'notice',
                array('notice', 'success', 'warning', 'error', 'critical', 'alert', 'emergency'),
                array('debug', 'info'),
            ),
            array(
                'success',
                array('success', 'warning', 'error', 'critical', 'alert', 'emergency'),
                array('debug', 'info', 'notice'),
            ),
            array(
                'warning',
                array('warning', 'error', 'critical', 'alert', 'emergency'),
                array('debug', 'info', 'notice', 'success'),
            ),
            array(
                'error',
                array('error', 'critical', 'alert', 'emergency'),
                array('debug', 'info', 'notice', 'success', 'warning'),
            ),
            array(
                'critical',
                array('critical', 'alert', 'emergency'),
                array('debug', 'info', 'notice', 'success', 'warning', 'error'),
            ),
            array(
                'alert',
                array('alert', 'emergency'),
                array('debug', 'info', 'notice', 'success', 'warning', 'error', 'critical'),
            ),
            array(
                'emergency',
                array('emergency'),
                array('debug', 'info', 'notice', 'success', 'warning', 'error', 'critical', 'alert'),
            ),
        );
    }

    /**
     * @dataProvider provideLevels
     */
    public function testLevels($level, $enabled, $disabled)
    {
        $cli = new LogLevel();
        $cli->setLogLevel($level);
        foreach ($enabled as $e) {
            $this->assertTrue($cli->isLogLevelEnabled($e), "$e is not enabled but should be");
        }
        foreach ($disabled as $d) {
            $this->assertFalse($cli->isLogLevelEnabled($d), "$d is enabled but should not be");
        }
    }


}
