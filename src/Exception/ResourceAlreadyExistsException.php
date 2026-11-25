<?php
declare(strict_types=1);

namespace App\Exception;


class ResourceAlreadyExistsException extends \RuntimeException {

    public function __construct(string $resource, string $field) {
        parent::__construct(sprintf("%s with that %s is registered already", $resource, $field));
    }

}
