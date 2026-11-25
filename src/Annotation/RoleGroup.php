<?php
declare(strict_types=1);

namespace App\Annotation;


#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class RoleGroup {

    public function __construct(public readonly array $roles) {
    }

}
