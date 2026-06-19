<?php

namespace Gingerminds\LaravelMultisite\Resolver;

class ResourceResolver
{
    public static function model(string $resource): string
    {
        return config("gingerminds-multisite.resources.{$resource}.model");
    }

    public static function repository(string $resource): string
    {
        return config("gingerminds-multisite.resources.{$resource}.repository");
    }

    public static function controller(string $resource): string
    {
        return config("gingerminds-multisite.resources.{$resource}.controller");
    }

    public static function provider(string $resource): string
    {
        return config("gingerminds-multisite.resources.{$resource}.provider");
    }

    public static function request(string $resource): string
    {
        return config("gingerminds-multisite.resources.{$resource}.request");
    }

    public static function stateProcessor(string $resource): string
    {
        return config("gingerminds-multisite.resources.{$resource}.state_processor");
    }
}
