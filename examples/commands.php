#!/usr/bin/php
<?php
namespace myapp;

require __DIR__ . '/../vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

// an example of creating a registry of commands
// place in a separate file in the same namespace
class cliCmds
{
    const create = 'create';
    const delete = 'delete';
}

// an example of creating a registry of options
// place in a separate file in the same namespace
class cliOpts
{
    const name = 'name';
    const nameShort = 'n';
    const nameArg = 'name';
}

class app extends CLI
{
    // register options and arguments
    protected function setup( Options $options )
    {
        $options->registerCommand( cliCmds::create, 'Create a new entry' );
        $options->registerCommand( cliCmds::delete, 'Delete an entry' );

        // showing registering an option for two commands
        $options->registerOption(
            cliOpts::name,
            'The entry name',
            cliOpts::nameShort,
            cliOpts::nameArg,
            [cliCmds::create, cliCmds::delete]
        );

        $options->setHelp( 'An example showing commands' );
    }


    protected function main( Options $options )
    {
        // show the name of the program and its version
        $this->info( $this->options->getBin() . ' v1.0.0' );

        switch ( $options->getCmd() ) {
            case cliCmds::create:
                $this->create( $name );
                break;

            case cliCmds::delete:
                $this->delete( $name );
                break;

            default:
                echo $options->help();
        }
    }


    protected function delete( $name )
    {
        if( ! $name = $this->options->getOpt( cliOpts::name ) )
        {
            echo $this->options->help();
            $this->fatal( 'Missing  --' . cliOpts::name );
        }
        $this->success( 'Deleted an entry for ' . $name );
    }


    protected function create( $name )
    {
        if( ! $name = $this->options->getOpt( cliOpts::name ) )
        {
            echo $this->options->help();
            $this->fatal( 'Missing  --' . cliOpts::name );
        }
        $this->success( 'Created an entry for ' . $name );
    }
}

// execute it
$cli = new app();
$cli->run();