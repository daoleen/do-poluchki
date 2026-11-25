<?php
declare(strict_types=1);

namespace App\Exception;


class ResourceNotFoundException extends \RuntimeException {

    public function __construct(public string $resourceName, public string $resourceId) {
        parent::__construct($this->__toString());
    }


    public function __toString(): string {
        return sprintf("Resource %s with %s is not found", $this->resourceName,  $this->resourceId);
    }

}