# PHP-CLI

PHP-CLI is a simple library that helps with creating nice looking command line scripts.

It takes care of

- **option parsing**
- **help page generation**
- **automatic width adjustment**
- **colored output**

It is lightweight and has **no 3rd party dependencies**.

[![Build Status](https://travis-ci.org/splitbrain/php-cli.svg)](https://travis-ci.org/splitbrain/php-cli)

## Installation

Use composer:

```php composer.phar require splitbrain/php-cli```

## Usage and Examples

The basic usage is simple:

- create a class and ``extend splitbrain\phpcli\CLI``
- implement the ```setup($options)``` method and register options, arguments, commands and set help texts
    - ``$options->setHelp()`` adds a general description
    - ``$options->registerOption()`` adds an option
    - ``$options->registerArgument()`` adds an argument
    - ``$options->registerComman()`` adds a sub command
- implement the ```main($options)``` method and do your business logic there
    - ``$options->getOpts`` lets you access set options
    - ``$options->getArgs()`` returns the remaining arguments after removing the options
    - ``$options->getCmd()`` returns the sub command the user used
- instantiate your class and call ```run()``` on it

Examples can be found in the examples directory. Please refer to the [API docs](https://splitbrain.github.io/php-cli/)
for further info.

## Exceptions

By default the CLI class registers an exception handler and will print the exception's message to the end user and
exit the programm with a non-zero exit code. You can disable this behaviour and catch all exceptions yourself by
passing false to the constructor.

You can use the provided ``splitbrain\phpcli\Exception`` to signal any problems within your main code yourself. The
exceptions's code will be used as the exit code then.

## Colored output

Colored output is handled through the ``Colors`` class. It tries to detect if a color terminal is available and only
then uses terminal colors. You can always suppress colored output by passing ``--no-colors`` to your scripts.

Simple colored log messages can be printed by you using the convinence methods ``success()`` (green), ``info()`` (cyan),
``error()`` (red) or ``fatal()`` (red). The latter will also exit the programm with a non-zero exit code.

For more complex coloring you can access the color class through ``$this->colors`` in your script. The ``wrap()`` method
is probably what you want to use.


The table formatter allows coloring full columns. To use that mechanism pass an array of colors as third parameter to
its ``format()`` method. Please note that you can not pass colored texts in the second parameters (text length calculation
and wrapping will fail, breaking your texts).

## Table Formatter

The ``TableFormatter`` class allows you to align texts in multiple columns. It tries to figure out the available
terminal width on its own. It can be overwritten by setting a ``COLUMNS`` environment variable.

The formatter is used through the ``format()`` method which expects at least two arrays: The first defines the column
widths, the second contains the texts to fill into the columns. Between each column a border is printed (a single space
by default).

See the ``example/table.php`` for sample usage.

Columns width can be given in three forms:

- fixed width in characters by providing an integer (eg. ``15``)
- precentages by provifing an integer and a percent sign (eg. ``25%``)
- a single fluid "rest" column marked with an asterisk (eg. ``*``)

When mixing fixed and percentage widths, percentages refer to the remaining space after all fixed columns have been
assigned.

Space for borders is automatically calculated. It is recommended to always have some relative (percentage) or a fluid
column to adjust for different terminal widths.

The table formatter is used for the automatic help screen accessible when calling your script with ``-h`` or ``--help``.
