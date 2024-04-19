<?php

namespace Wubbleyou\Boundaries\BoundaryRules;

use Illuminate\Routing\Route;

class BoundaryRule implements IBoundaryRule {
    public function __construct(public Route $route) {}

    public function handle(): bool {
        return false;
    }
}