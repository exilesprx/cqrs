<?php

namespace tests\Unit\CQRS\Repositories;

use CQRS\Repositories\PayloadHelper;
use Faker\Factory;
use Faker\Generator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PayloadHelperSpec extends ObjectBehavior
{
    /**
     * @var Generator
     */
    private $faker;

    public function let()
    {
        $this->faker = Factory::create();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PayloadHelper::class);
    }

    public function it_should_return_array()
    {
        $name = $this->faker->name();

        $arg = [
            'name' => $name
        ];

        $this->filterNullValues($arg)->shouldReturn(
            [
                'name' => $name
            ]
        );
    }

    public function it_should_return_empty_array()
    {
        $name = null;

        $arg = [
            'name' => $name
        ];

        $this->filterNullValues($arg)->shouldReturn([]);
    }

    public function it_should_remove_empty_values()
    {
        $name = $this->faker->name();
        $title = $this->faker->title();

        $arg = [
            'name' => $name,
            'title' => $title,
            'age' => null
        ];

        $this->filterNullValues($arg)->shouldReturn(
            [
                'name' => $name,
                'title' => $title
            ]
        );
    }
}
