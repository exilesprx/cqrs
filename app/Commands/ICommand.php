<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:23 PM
 */

namespace CQRS\Commands;


/**
 * Interface ICommand
 * @package CQRS\Events
 */
interface ICommand
{
    /**
     * @return string
     */
    public static function getShortName() : string;
}