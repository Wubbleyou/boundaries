<?php

namespace Wubbleyou\Boundaries\Tests;

use Tests\TestCase;

use Wubbleyou\Boundaries\Helpers\RouteListGeneration;
use Wubbleyou\Boundaries\BoundaryRules\BoundaryRule;
use Illuminate\Support\Facades\Route;

class BaseBoundaryTest extends TestCase
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
     * Test all routes against the provided BoundaryRules.
     * @return void
     */
    public function testBoundaryRules(): void
    {
        $boundaries = $this->getRoutes();
        $errors = [];

        foreach ($boundaries as $routeName => $rules) {
            $route = Route::getRoutes()->getByName($routeName);

            if ($route === null) {
                $errors[] = "$routeName does not exist, it is probably not registered by the Laravel app - was is removed?";
            } else {
                foreach($rules as $rule) {
                    if($rule instanceof \Closure) {
                        $errors = array_merge($errors, $rule($route, $this, $routeName));
                    } else if($rule instanceof BoundaryRule) {
                        $errors = array_merge($errors, $rule->handle($route, $this, $routeName));
                    }
                }
            }
        }

        $errorCount = count($errors);
        $this->assertEmpty($errors, "The following routes ({$errorCount}) failed boundary rules:\n\n" . implode("\n", $errors) . "\n");
    }
}