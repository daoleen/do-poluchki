<?php
declare(strict_types=1);

namespace App\Annotation;


use App\Enum\Role;


#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
final class ROLE_ADMIN extends RoleGroup {

    public function __construct() {
        parent::__construct([Role::ADMIN]);
    }

}
