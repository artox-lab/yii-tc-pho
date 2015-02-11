<?php

namespace yii_tc_pho\Reporter;

use pho\Runnable\Spec;
use pho\Suite\Suite;

abstract class AbstractReporter extends \pho\Reporter\AbstractReporter
{
    protected $suite;

    public function beforeSuite(Suite $suite)
    {
        if (empty($suite->getParent()))
        {
            $this->console->writeLn("\n##teamcity[testStarted name='" . $suite->getTitle() . "']");

            $this->suite = $suite;
        }

        return parent::beforeSuite($suite);
    }

    public function afterSuite(Suite $suite)
    {
        if (empty($suite->getParent()))
        {
            $this->console->writeLn("\n##teamcity[testFinished name='" . $suite->getTitle() . "']");

            $this->suite = null;
        }

        return parent::beforeSuite($suite);
    }

    /**
     * Invoked after the test suite has ran, allowing for the display of test
     * results and related statistics.
     */
    public function afterRun()
    {
        if (count($this->failedSpecs)) {
            $this->console->writeLn("\nFailures:");
        }

        foreach ($this->failedSpecs as $spec) {
            $failedText = $this->formatter->red("\n\"$spec\" FAILED");

            if (!empty($this->suite))
            {
                $this->console->writeLn("
                ##teamcity[testFailed name='" . $spec->getTitle() . "' message='" . $failedText . "' details='" . $spec->exception . "']
                ");
            }
            else
            {
                $this->console->writeLn($failedText);
                $this->console->writeLn($spec->exception);
            }
        }

        if ($this->startTime) {
            $endTime = microtime(true);
            $runningTime = round($endTime - $this->startTime, 5);
            $this->console->writeLn("\nFinished in $runningTime seconds");
        }

        $failedCount = count($this->failedSpecs);
        $incompleteCount = count($this->incompleteSpecs);
        $pendingCount = count($this->pendingSpecs);
        $specs = ($this->specCount == 1) ? 'spec' : 'specs';
        $failures = ($failedCount == 1) ? 'failure' : 'failures';
        $incomplete = ($incompleteCount) ? ", $incompleteCount incomplete" : '';
        $pending = ($pendingCount) ? ", $pendingCount pending" : '';

        // Print ASCII art if enabled
        if ($this->console->options['ascii']) {
            $this->console->writeLn('');
            $this->drawAscii();
        }

        $summaryText = "\n{$this->specCount} $specs, $failedCount $failures" .
                       $incomplete . $pending;

        // Generate the summary based on whether or not it passed
        if ($failedCount) {
            $summary = $this->formatter->red($summaryText);
        } else {
            $summary = $this->formatter->green($summaryText);
        }

        $summary = $this->formatter->bold($summary);
        $this->console->writeLn($summary);
    }
}
