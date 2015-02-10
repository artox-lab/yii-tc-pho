<?php

namespace yii_tc_pho\Exception;

/**
 * Class ReporterNotFoundException
 * @package yii_tc_pho\Exception
 */
class ReporterNotFoundException extends Exception
{

    /**
     * Creates a ReporterNotFoundException, given a previously raised exception.
     * This exception is thrown when a reporter is not available.
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        parent::__construct(
            $exception->getMessage(),
            $exception->getCode(),
            $exception
        );

        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        $this->type = get_class($exception);
    }

}
