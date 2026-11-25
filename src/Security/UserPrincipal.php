<?php
declare(strict_types=1);

namespace App\Security;


use Symfony\Component\Security\Core\User\UserInterface;


final class UserPrincipal implements UserInterface {
    private readonly int $userId;   // db User#ID
    private readonly string $sub;
    private readonly string $name;
    private readonly string $email;
    private readonly string $username;
    private readonly string $avatarId;
    private readonly array $roles;


    /**
     * @param int $userId database User ID
     * @param string $sub keycloakId
     * @param string $name имя человека
     * @param string $email адрес электронной почты
     * @param string $username имя пользователя
     * @param string $avatarId id аватарки
     * @param array $roles роли пользователя
     */
    public function __construct(int $userId, string $sub, string $name, string $email, string $username,
        string $avatarId, array $roles)
    {
        $this->userId = $userId;
        $this->sub = $sub;
        $this->name = $name;
        $this->email = $email;
        $this->username = $username;
        $this->avatarId = $avatarId;
        $this->roles = $roles;
    }


    public function getUserId(): int {
        return $this->userId;
    }


    public function getSub(): string {
        return $this->sub;
    }


    public function getName(): string {
        return $this->name;
    }


    public function getEmail(): string {
        return $this->email;
    }


    public function getUsername(): string {
        return $this->username;
    }


    public function getAvatarId(): string {
        return $this->avatarId;
    }


    public function getRoles(): array {
        return $this->roles;
    }


    public function getUserIdentifier(): string {
        return $this->sub;
    }
}