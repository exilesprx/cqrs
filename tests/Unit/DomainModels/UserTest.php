<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/10/17
 * Time: 5:04 PM
 */

namespace Tests\DomainModels;

use CQRS\DomainModels\User;
use Faker\Factory;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserTest extends TestCase
{
    private $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create();
    }

    public function testCreateUserFromRequest()
    {
        $request = new Request();

        $request->attributes->add([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->word
        ]);

        $user =  User::fromRequest($request);

        $this->assertNotNull($user->getName());
        $this->assertNotNull($user->getEmail());
        $this->assertNotNull($user->getPassword());
    }

    public function testCreateUserFromPayload() {

        $version = 1;
        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password
        ];

        $user = User::fromPayload($version, $payload);

        $this->assertNotNull($user->getName());
        $this->assertNotNull($user->getEmail());
        $this->assertNotNull($user->getPassword());
        $this->assertNotNull($user->getAggregateVersion());
    }
}
