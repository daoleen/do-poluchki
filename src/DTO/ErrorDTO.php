<?php
declare(strict_types=1);

namespace App\DTO;


class ErrorDTO {
    public function __construct(public string $code, public string $description) {
    }
}
