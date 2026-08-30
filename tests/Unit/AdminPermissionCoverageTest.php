<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Permissions\PermissionScanner;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use ReflectionClass;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Guards the permission wiring itself: a controller action that ships without a
 * permission, or with one the seeder never creates, fails here.
 */
class AdminPermissionCoverageTest extends TestCase
{
    public function test_every_public_action_of_every_admin_controller_is_guarded(): void
    {
        foreach ($this->guardedControllers() as $controller) {
            $guarded = [];

            foreach ($controller::middleware() as $middleware) {
                $guarded = array_merge($guarded, (array) $middleware->only);
            }

            foreach ($this->publicActions($controller) as $action) {
                $this->assertContains(
                    $action,
                    $guarded,
                    "{$controller}::{$action}() is not guarded by a permission - add it to middleware()."
                );
            }
        }
    }

    public function test_every_guarded_permission_is_discovered_by_the_scanner(): void
    {
        $discovered = PermissionScanner::all();

        foreach ($this->guardedControllers() as $controller) {
            foreach ($controller::middleware() as $middleware) {
                foreach ($this->permissionsIn($middleware) as $permission) {
                    $this->assertContains(
                        $permission,
                        $discovered,
                        "{$controller} guards with '{$permission}', which the seeder would never create."
                    );
                }
            }
        }
    }

    /**
     * @return array<int, class-string<HasMiddleware>>
     */
    private function guardedControllers(): array
    {
        return array_values(array_filter(
            PermissionScanner::controllerClasses(),
            fn (string $class): bool => is_a($class, HasMiddleware::class, true),
        ));
    }

    /**
     * @return array<int, string>
     */
    private function permissionsIn(Middleware $middleware): array
    {
        $permissions = [];

        foreach ((array) $middleware->middleware as $entry) {
            if (! is_string($entry) || ! str_contains($entry, ':')) {
                continue;
            }

            [, $arguments] = explode(':', $entry, 2);

            foreach (explode('|', explode(',', $arguments)[0]) as $candidate) {
                if (str_contains($candidate, '.')) {
                    $permissions[] = $candidate;
                }
            }
        }

        return $permissions;
    }

    /**
     * @param  class-string  $controller
     * @return array<int, string>
     */
    private function publicActions(string $controller): array
    {
        $actions = [];

        foreach ((new ReflectionClass($controller))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();

            if ($method->getDeclaringClass()->getName() !== $controller || $method->isStatic() || str_starts_with($name, '__')) {
                continue;
            }

            $actions[] = $name;
        }

        return $actions;
    }
}
