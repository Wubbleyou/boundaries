<?php

namespace Wubbleyou\Boundaries\Tests;

use Tests\TestCase;

use Wubbleyou\Boundaries\Helpers\RouteListGeneration;
use Wubbleyou\Boundaries\BoundaryRules\BoundaryRule;
use Illuminate\Support\Facades\Route;

class BaseBoundaryMiddlewareTest extends TestCase
{
    /**
     * Tests that our route checker will boundary check all available routes
     *
     * @return void
     */
    public function testBoundaryRoutes(): void
    {
        $missingRoutes = RouteListGeneration::findMissingRoutes($this->getRoutes(), $this->getWhitelist());
        $this->assertEquals([], $missingRoutes, 'The following routes are not being tested and must be added to your boundaries trait file (app/Traits/BoundaryRouteTrait.php): ' . json_encode($missingRoutes));
    }

    /**
     * Checks that all routes have the correct middleware
     * @return void
     */
    public function testBoundaryMiddleware(): void
    {
        $boundaries = $this->getRoutes();
        $errors = [];

        foreach ($boundaries as $routeName => $options) {
            $route = Route::getRoutes()->getByName($routeName);

            if ($route === null) {
                $errors[] = "$routeName does not exist, it is probably not registered by the Laravel app - was is removed?";
            } else {
                // Test middleware
                if(isset($options['middleware']) && is_array($options['middleware'])) {
                    $errors = array_merge($errors, $this->testMiddleware($options, $route));
                }

                // Test rules
                if(isset($options['rules']) && is_array($options['rules'])) {
                    $errors = array_merge($errors, $this->testRules($options, $route));
                }
            }
        }

        $errorCount = count($errors);
        $this->assertEmpty($errors, "The following routes ({$errorCount}) failed boundary testing:\n\n" . implode("\n", $errors) . "\n");
    }

    protected function testMiddleware($boundary, $route): array
    {
        $errors = [];
        $middleware = $route->gatherMiddleware();
        $routeName = $route->getName() ?? $route->getActionName();

        foreach ($boundary['middleware'] as $middlewareExpected) {
            if (!in_array($middlewareExpected, $middleware, true)) {
                $errors[] = "$routeName should have middleware $middlewareExpected but does not (" . implode(',', $middleware) . ')';
            }
        }

        return $errors;
    }

    protected function testRules($boundary, $route): array
    {
        $errors = [];
        $routeName = $route->getName() ?? $route->getActionName();

        foreach($boundary['rules'] as $ruleName => $rule) {
            if($rule instanceof \Closure) {
                if(!$rule($route)) {
                    $errors[] = "$routeName failed rule: $ruleName";
                }
            } else if(class_exists($rule)) {
                $ruleClass = new $rule($route);
                if($ruleClass instanceof BoundaryRule) {
                    if(!$ruleClass->handle()) {
                        $errors[] = "$routeName failed rule: $ruleName";
                    }
                }
            }
        }

        return $errors;
    }
}