<?php

namespace Wubbleyou\Boundaries\BoundaryRules;

use Illuminate\Routing\Route;
use Tests\TestCase;

class BoundaryRule implements IBoundaryRule {
    public string $name = 'BoundaryRule';

    public function handle(Route $route, TestCase $test, string $routeName): array
    {
        return [];
    }

    public function setName(string $name): BoundaryRule
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}