# Wubbleyou Boundaries
Boundaries is a DX tool used to generate tests to automatically scan your routes for misconfigured Middleware and Policies.

## Installation
Add the repo to your `composer.json`

```
"repositories":[
    {
        "type": "vcs",
        "url": "git@github.com:Wubbleyou/boundaries.git"
    }
],
```

Install the composer package normally

```
composer require wubbleyou/boundaries
```

## Usage
To get started you'll need to run the following commands to generate the base test files:
```
php/sail artisan wubbleyou:generate-middleware-test
```
```
php/sail artisan wubbleyou:generate-policy-test
```

You can also generate both tests with this command:
```
php/sail artisan wubbleyou:generate-tests
```

## Configuration
Now you've got your tests generated and saved in `tests\Feature\Boundaries` you might notice a trait has been placed in `app\Traits\BoundaryRouteTrait.php` which contains 2 methods, please read the additional information at the bottom to understand why you need to supply these.

### getWhitelist()
The `getWhitelist()` method allows you to return an array of all the routes you want to be ignored by Wubbleyou\Boundaries.

```
return [
    'login',
    'register',
    'homepage',
    'about',
];
```

### getRoutes()
The `getRoutes()` method allows you to return an array to specify the exact assertions that should be ran on each route, here's an example:

```
$admin = User::factory()->make(['is_admin', true]);
$userOne = User::factory()->make();
$userTwo = User::factory()->make();

return [
    'users.change-password' => [
        'middleware' => ['web', 'auth'],
        'rules' => [
            'ruleOne' => function() {return true;},
            'ruleTwo' => function() {return false;},
            'ruleThree' => TestRule::class,
        ],
        'tests' => [
            'get' => [
                [
                    'expected' => 200, // Expected HTTP response code
                    'user' => $admin, // The user we're testing as
                    'params' => ['user' => $userOne], // Any parameters the route requires
                ],
                [
                    'expected' => 200, // Expected HTTP response code
                    'user' => $userOne, // The user we're testing as
                    'params' => ['user' => $userOne], // Any parameters the route requires
                ],
                [
                    'expected' => 403, // Expected HTTP response code
                    'user' => $userOne, // The user we're testing as
                    'params' => ['user' => $userTwo], // Any parameters the route requires
                ],
                [
                    'expected' => 302, // Expected HTTP response code
                    'params' => ['user' => $userOne], // Any parameters the route requires
                ],
            ]
        ]
    ]
];
```

**You can supplying the following options:**

- Middleware
- Rules
- Tests

#### Middleware
Middleware is an array of all the middleware you want to check this route has, if the specific route doesn't match all of these middleware the test will fail.

#### Rules
Rules are Closures or BoundaryRule classes you can supply that return a boolean, `false` will cause the test to fail. You can generate a new BoundaryRule class using the following command:

```
php/sail artisan wubbleyou:generate-rule RuleName
```

An example BoundaryRule would look like:

```
<?php

namespace App\BoundaryRules;

use Wubbleyou\Boundaries\BoundaryRules\BoundaryRule;

class BasicTestRule extends BoundaryRule {
    public function handle(): bool
    {
        // $this->route contains the current route we're testing against
        return false;
    }
}
```

#### Tests
Supplying tests will run policy assertions based on the information you pass through, an example test could be like follows:

```
'get' => [
    [
        'expected' => 200, // Expected HTTP response code
        'user' => $admin, // The user we're testing as
        'params' => ['user' => $userOne], // Any parameters the route requires
    ],
    [
        'expected' => 200, // Expected HTTP response code
        'user' => $userOne, // The user we're testing as
        'params' => ['user' => $userOne], // Any parameters the route requires
    ],
    [
        'expected' => 403, // Expected HTTP response code
        'user' => $userOne, // The user we're testing as
        'params' => ['user' => $userTwo], // Any parameters the route requires
    ],
    [
        'expected' => 302, // Expected HTTP response code
        'params' => ['user' => $userOne], // Any parameters the route requires
    ],
]
```

You supply the request type (GET/POST/PUT/DELETE) and then a multidimensional array to specify the expected HTTP response code, optionally attach a user (to run the test as `actingAs`) and optionally any parameters the route relies on.

#### Additional Information
Boundaries testing will fail if every route is not accounted for in either `getWhitelist()` or `getRoutes()`, if you want to generate a list of routes you that aren't in either of those run the following command:

```
php/sail artisan wubbleyou:missing-routes
```

Please note this command requires you to have generated the BoundaryRouteTrait to function, if you need to generate just the trait without any of the tests you can run:

```
php/sail artisan wubbleyou:generate-route-trait
```