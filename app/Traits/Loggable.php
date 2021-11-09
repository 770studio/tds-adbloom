<?php

namespace App\Traits;

use Illuminate\Log\Logger;

/**
 * allows redirecting log to another channel
 */
trait Loggable
{
    public Logger $logger;

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }
}
