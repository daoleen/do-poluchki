<?php
declare(strict_types=1);

namespace App\Security\EventListener;


use App\Annotation\RoleGroup;
use App\Enum\Role;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class RoleAttributeListener {

    public function __construct(private readonly Security $security) {
    }


    #[AsEventListener(KernelEvents::CONTROLLER)]
    public function onKernelController(ControllerEvent $event): void {
        $controller = $event->getController();
        if (!is_array($controller)) {
            return; // замыкания и инвокейблы не проверяем
        }

        $reflection = new \ReflectionMethod($controller[0], $controller[1]);

        // сначала метод, потом класс — как приоритет у @PreAuthorize
        $attribute = $reflection->getAttributes(RoleGroup::class, \ReflectionAttribute::IS_INSTANCEOF)[0]
            ?? $reflection->getDeclaringClass()->getAttributes(RoleGroup::class, \ReflectionAttribute::IS_INSTANCEOF)[0]
            ?? null;

        if ($attribute === null) {
            return;
        }

        $roleGroup = (array) $attribute->newInstance();
        $allowedRoles = array_map(fn(Role $r) => (string) $r->value, $roleGroup['roles']);
        $accessGranted = false;

        foreach ($allowedRoles as $role) {
            if ($this->security->isGranted($role)) {
                $accessGranted = true;
                break;
            }
        }

        // isGranted с массивом = hasAnyRole: хоть одна — пускаем
        if (!$accessGranted) {
            throw new AccessDeniedException('Forbidden');
        }
    }

}