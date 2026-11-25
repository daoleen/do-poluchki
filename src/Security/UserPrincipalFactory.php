<?php
declare(strict_types=1);

namespace App\Security;


use Symfony\Component\Security\Core\Exception\AccessDeniedException;


final class UserPrincipalFactory {

    public static function fromClaims(?int $userId, array $claims): UserPrincipal {
        $kcRoles = $claims['realm_access']['roles'] ?? [];
        $roles = array_filter($kcRoles, fn (string $role) => str_starts_with($role, 'ROLE_'));

        $sub = $claims['sub'] ?? null;
        if (!is_string($sub) || $sub === '') {
            throw new AccessDeniedException('Malformed token: unknown sub');
        }

        return new UserPrincipal($userId, $sub, $claims['given_name'] ?? null, $claims['email'] ?? null,
            $claims['preferred_username'], $claims['avatar_id'] ?? null, $roles);
    }

}