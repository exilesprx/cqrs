<?php

namespace tests\Unit\CQRS\Events;

use CQRS\Events\UserCreatedEvent;
use Faker\Factory;
use Faker\Generator;
use PhpSpec\ObjectBehavior;

/**
 * Class UserCreatedEventSpec
 * @package spec\CQRS\Events
 */
class UserCreatedEventSpec extends ObjectBehavior
{
    /**
     * @var Generator
     */
    private $faker;

    /**
     *
     */
    public function let()
    {
        $this->faker = Factory::create();
    }

    /**
     *
     */
    function it_is_initializable()
    {
        $this->shouldHaveType(UserCreatedEvent::class);
    }

    /**
     *
     */
    public function it_calls_handle_and_sets_properties()
    {
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $this->handle($name, $email, $password);

        $this->getName()->shouldBe($name);
        $this->getEmail()->shouldBe($email);
        $this->getPassword()->shouldBe($password);
    }

    /**
     *
     */
    public function it_gets_event_name()
    {
        $this->getShortName()->shouldBe(UserCreatedEvent::class);
    }
}
