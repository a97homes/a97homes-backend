<?php

namespace App\Permissions;

use App\Enums\Role\UserRoleEnum;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;
use ReflectionClass;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Throwable;

/**
 * Discovers every permission the application actually guards with, so the
 * database never falls behind the code.
 *
 * The controllers are the single source of truth. Two passes are merged:
 *  1. The middleware gathered from every registered route.
 *  2. The `middleware()` method of every controller implementing HasMiddleware,
 *     including entries whose `only`/`except` methods have no route yet.
 */
class PermissionScanner
{
    /**
     * Middleware aliases and class names that carry permission names.
     *
     * @var array<int, string>
     */
    private const PERMISSION_MIDDLEWARE = [
        'permission',
        'role_or_permission',
        PermissionMiddleware::class,
        RoleOrPermissionMiddleware::class,
    ];

    private const CONTROLLERS_PATH = 'Http/Controllers';

    /**
     * Every permission name known to the application, sorted.
     *
     * @return array<int, string>
     */
    public static function all(): array
    {
        return self::normalize(array_merge(
            self::fromRoutes(),
            self::fromControllers(),
        ));
    }

    /**
     * Permission names guarded by the middleware of the registered routes.
     *
     * @return array<int, string>
     */
    public static function fromRoutes(): array
    {
        $permissions = [];

        /** @var Route $route */
        foreach (RouteFacade::getRoutes() as $route) {
            try {
                $middleware = $route->gatherMiddleware();
            } catch (Throwable) {
                continue;
            }

            foreach ($middleware as $entry) {
                $permissions = array_merge($permissions, self::parse($entry));
            }
        }

        return self::normalize($permissions);
    }

    /**
     * Permission names declared by controllers implementing HasMiddleware.
     *
     * Unlike the route scan this ignores `only`/`except`, so a permission wired
     * to a controller method that is not routed yet is still discovered.
     *
     * @return array<int, string>
     */
    public static function fromControllers(): array
    {
        $permissions = [];

        foreach (self::controllerClasses() as $class) {
            if (! is_a($class, HasMiddleware::class, true)) {
                continue;
            }

            foreach ($class::middleware() as $entry) {
                $entries = $entry instanceof Middleware ? (array) $entry->middleware : [$entry];

                foreach ($entries as $middleware) {
                    $permissions = array_merge($permissions, self::parse($middleware));
                }
            }
        }

        return self::normalize($permissions);
    }

    /**
     * Permission names discovered in the code but missing from PermissionRegistry.
     *
     * @return array<int, string>
     */
    public static function missingFromRegistry(): array
    {
        return array_values(array_diff(self::all(), PermissionRegistry::all()));
    }

    /**
     * Pull the permission names out of a single middleware definition.
     *
     * @return array<int, string>
     */
    private static function parse(mixed $middleware): array
    {
        if (! is_string($middleware) || ! Str::contains($middleware, ':')) {
            return [];
        }

        [$name, $arguments] = explode(':', $middleware, 2);

        if (! in_array($name, self::PERMISSION_MIDDLEWARE, true)) {
            return [];
        }

        $roleNames = array_column(UserRoleEnum::cases(), 'value');

        // The guard is an optional second argument: "roles.index|admin,web".
        $candidates = explode('|', explode(',', $arguments)[0]);

        return array_values(array_filter(
            array_map('trim', $candidates),
            fn (string $candidate): bool => $candidate !== '' && ! in_array($candidate, $roleNames, true),
        ));
    }

    /**
     * Fully qualified class names of every controller in the application.
     *
     * @return array<int, class-string>
     */
    private static function controllerClasses(): array
    {
        $directory = app_path(self::CONTROLLERS_PATH);

        if (! is_dir($directory)) {
            return [];
        }

        $classes = [];

        foreach (Finder::create()->files()->in($directory)->name('*.php') as $file) {
            $class = self::classFromFile($file);

            if ($class === null || ! class_exists($class)) {
                continue;
            }

            if ((new ReflectionClass($class))->isAbstract()) {
                continue;
            }

            $classes[] = $class;
        }

        return $classes;
    }

    /**
     * @return class-string|null
     */
    private static function classFromFile(SplFileInfo $file): ?string
    {
        $relative = Str::after($file->getRealPath(), app_path().DIRECTORY_SEPARATOR);

        /** @var class-string $class */
        $class = 'App\\'.str_replace(
            [DIRECTORY_SEPARATOR, '.php'],
            ['\\', ''],
            $relative
        );

        return $class;
    }

    /**
     * @param  array<int, string>  $permissions
     * @return array<int, string>
     */
    private static function normalize(array $permissions): array
    {
        $permissions = array_values(array_unique(array_filter($permissions)));

        sort($permissions);

        return $permissions;
    }
}
