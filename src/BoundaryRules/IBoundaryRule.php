<?php

namespace Wubbleyou\Boundaries\BoundaryRules;

use Illuminate\Routing\Route;
use Tests\TestCase;

interface IBoundaryRule {
    public function handle(Route $route, TestCase $test, string $routeName): array;
    public function setName(string $name);
    public function getName();
}