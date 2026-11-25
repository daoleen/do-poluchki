<?php
declare(strict_types=1);

namespace App\DTO;


class FieldErrorDTO extends ErrorDTO {
    private string $field;


    public function __construct(string $field, string $code, string $description) {
        parent::__construct($code, $description);
        $this->field = $field;
    }


    public function compareTo(self $other): int {
        return $this->field <=> $other->field;
    }


    public function getField(): string {
        return $this->field;
    }

}
