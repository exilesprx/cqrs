<?php

namespace CQRS\Repositories;


/**
 * Class PayloadHelper
 * @package CQRS\Repositories
 */
class PayloadHelper
{
    /**
     * @param iterable $payload
     * @return array
     */
    public function filterNullValues(iterable $payload)
    {
        return array_filter(
            $payload,
            function($item) {
                return !empty($item) || !is_null($item);
            }
        );
    }
}