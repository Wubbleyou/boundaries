<?php

namespace Wubbleyou\Boundaries\BoundaryRules;

use Illuminate\Routing\Route;
use Tests\TestCase;

class BoundaryRule implements IBoundaryRule {
    public function handle(Route $route, TestCase $test, string $routeName): array
    {
        return [];
    }
}