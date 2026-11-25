<?php
declare(strict_types=1);

namespace App\Exception;


use Throwable;


class ApplicationException extends \RuntimeException {

    public function __construct(string $message, ?Throwable $cause = null) {
        parent::__construct($message, 0, $cause);
    }

}
