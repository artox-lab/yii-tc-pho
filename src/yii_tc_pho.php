<?php

namespace yii_tc_pho;

use pho\Runner\Runner;
use yii_tc_pho\Console\Console;
use pho\Expectation\Expectation;

/**
 * Calls the runner's describe() method, creating a test suite with the provided
 * closure.
 *
 * @param string   $title   A title associated with this suite
 * @param \Closure $closure The closure associated with the suite, which may
 *                          contain nested suites and specs
 */
function describe($title, \Closure $closure)
{
    Runner::getInstance()->describe($title, $closure);
}

/**
 * Calls the runner's xdescribe() method, creating a pending test suite with
 * the provided closure.
 *
 * @param string   $title   A title associated with this suite
 * @param \Closure $closure The closure associated with the suite, which may
 *                          contain nested suites and specs
 */
function xdescribe($title, \Closure $closure)
{
    Runner::getInstance()->xdescribe($title, $closure);
}

/**
 * An alias for describe. Creates a test suite with the given closure.
 *
 * @param string   $title   A title associated with this suite
 * @param \Closure $closure The closure associated with the suite, which may
 *                          contain nested suites and specs
 */
function context($title, \Closure $closure)
{
    Runner::getInstance()->describe($title, $closure);
}

/**
 * An alias for xdescribe. Creates a pending test suite with the given closure.
 *
 * @param string   $title   A title associated with this suite
 * @param \Closure $closure The closure associated with the suite, which may
 *                          contain nested suites and specs
 */
function xcontext($title, \Closure $closure)
{
    Runner::getInstance()->xdescribe($title, $closure);
}

/**
 * Calls the runner's it() method, creating a test spec with the provided
 * closure.
 *
 * @param string   $title   A title associated with this spec
 * @param \Closure $closure The closure associated with the spec
 */
function it($title, \Closure $closure = null)
{
    Runner::getInstance()->it($title, $closure);
}

/**
 * Calls the runner's xit() method, creating a pending test spec with the
 * provided closure.
 *
 * @param string   $title   A title associated with this spec
 * @param \Closure $closure The closure associated with the spec
 */
function xit($title, \Closure $closure = null)
{
    Runner::getInstance()->xit($title, $closure);
}

/**
 * Calls the runner's before() method, defining a closure to be ran prior to
 * the parent suite's closure.
 *
 * @param \Closure $closure The closure to be ran before the suite
 */
function before(\Closure $closure)
{
    Runner::getInstance()->before($closure);
}

/**
 * Calls the runner's after() method, defining a closure to be ran after the
 * parent suite's closure.
 *
 * @param \Closure $closure The closure to be ran after the suite
 */
function after(\Closure $closure)
{
    Runner::getInstance()->after($closure);
}

/**
 * Calls the runner's beforeEach() method, defining a closure to be ran prior to
 * each of the parent suite's nested suites and specs.
 *
 * @param \Closure $closure The closure to be ran before each spec
 */
function beforeEach(\Closure $closure)
{
    Runner::getInstance()->beforeEach($closure);
}

/**
 * Calls the runner's afterEach() method, defining a closure to be ran after
 * each of the parent suite's nested suites and specs.
 *
 * @param \Closure $closure The closure to be ran after the suite
 */
function afterEach(\Closure $closure)
{
    Runner::getInstance()->afterEach($closure);
}

/**
 * Creates and returns a new Expectation for the supplied value.
 *
 * @param mixed $actual The value to test
 */
function expect($actual)
{
    return new Expectation($actual);
}

// Create a new Console and parse arguments
$console = new Console(array_slice($argv, 1), 'php://stdout');
$console->parseArguments();

// Disable color output if necessary
if ($console->options['no-color']) {
    $console->formatter->disableANSI();
}

if (!$console->options['reporter'])
{
    $console->options['reporter'] = '\\yii_tc_pho\\Reporter\\TeamCity';
}

// // Exit if necessary
// if ($console->getErrorStatus() !== null) {
//     exit($console->getErrorStatus());
// }

// Load global namespaced functions if required
if (!$console->options['namespace']) {
    $path = dirname(__FILE__) . '/globalPho.php';
    require_once($path);
}

// Start the runner
Runner::$console = $console;
Runner::getInstance()->run();
