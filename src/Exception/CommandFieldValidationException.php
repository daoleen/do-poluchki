<?php
declare(strict_types=1);

namespace App\Exception;


class CommandFieldValidationException extends \RuntimeException {
    private array $errors;


    // TODO: тут надо разобраться с BindingResult bindingResult


    public function getErrors(): array {
        return $this->errors;
    }

}