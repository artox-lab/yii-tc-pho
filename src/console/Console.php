<?php

namespace yii_tc_pho\Console;

use yii_tc_pho\Reporter;
use ReflectionClass;
use ReflectionException;
use pho\Exception\ReporterNotFoundException;

class Console extends pho\Console\Console
{
    /**
     * Returns the namespaced name of the reporter class requested via the
     * command line arguments, defaulting to DotReporter if not specified.
     *
     * @return string The namespaced class name of the reporter
     * @throws \pho\Exception\ReporterNotFoundException
     */
    public function getReporterClass()
    {
        $reporter = $this->options['reporter'];

        if ($reporter === false) {
            return self::DEFAULT_REPORTER;
        }

        $reporterClass = ucfirst($reporter) . 'Reporter';
        $reporterClass = "yii_tc_pho\\Reporter\\$reporterClass";

        try {
            $reflection = new ReflectionClass($reporterClass);
        } catch (ReflectionException $exception) {
            throw new ReporterNotFoundException($exception);
        }

        return $reflection->getName();
    }
}
