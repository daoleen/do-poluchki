<?php
declare(strict_types=1);

namespace App\Security;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\AttributesBasedUserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;


readonly class KeycloakUserProvider implements AttributesBasedUserProviderInterface {

    public function __construct(private UserRepository $userRepository) {
    }


    public function loadUserByIdentifier(string $identifier, array $attributes = []): UserInterface {
        // identifier — это sub из токена,
        // а $attributes — ВЕСЬ payload проверенного JWT: username, email, realm_access и т.д.
        $user = $this->userRepository->findOneBy(['keycloakId' => $identifier]);

        if (!$user) {
            // создаем пользователя при первом его входе
            $user = new User($identifier);
            $this->userRepository->save($user);
        }

        $principal = UserPrincipalFactory::fromClaims($user->getId(), $attributes);
        return $principal;
    }


    public function refreshUser(UserInterface $user): UserInterface {
        return $user; // stateless API — refresh не нужен
    }


    public function supportsClass(string $class): bool {
        return $class === UserPrincipal::class;
    }
}
