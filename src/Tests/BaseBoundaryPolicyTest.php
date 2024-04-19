<?php

namespace Wubbleyou\Boundaries\Tests;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class BaseBoundaryPolicyTest extends TestCase
{
    public function testBoundaryAccess(): void
    {
        $errors = [];

        foreach($this->getRoutes() as $route => $options) {
            if(isset($options['tests']) && !empty($options['tests'])) {
                foreach($options['tests'] as $reqType => $cases) {
                    foreach($cases as $case) {
                        $expectedStatus = $case['expected'];
                        $params = (isset($case['params']) && is_array($case['params'])) ? $case['params'] : [];

                        if(isset($case['user']) && !empty($case['user'])) {
                            $testCase = $this->actingAs($case['user'], 'web')
                                ->withExceptionHandling()
                                ->$reqType(route($route, $params));

                            // WEIRD: We have to log out here to not break the next test where we're expecting a guest user?
                            Auth::logout();
                        } else {
                            $testCase = $this->withExceptionHandling()
                                ->$reqType(route($route, $params));
                        }

                        if ($expectedStatus !== $testCase->getStatusCode()) {
                            $user = (isset($case['user']) && !empty($case['user'])) ? $case['user']->email : 'Guest user';
                            $errors[] = "{$reqType}:{$route} does not match for {$user} - Expected {$expectedStatus}, got {$testCase->getStatusCode()}\n";
                        }
                    }
                }
            }
        }

        $this->assertEmpty($errors, "The following boundary policy tests (" . count($errors) . ") did not return the expected error codes:\r\n" . implode("\r\n", $errors));
    }
}