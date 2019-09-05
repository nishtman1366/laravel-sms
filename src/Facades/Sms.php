<?php
/**
 * Created by PhpStorm.
 * User: nishtman
 * Date: 9/2/19
 * Time: 3:16 PM
 */

namespace Nishtman\Sms\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static string test()
 * @method static \Nishtman\Sms\Sms provider(string $provider = null)
 * @method static \Nishtman\Sms\Sms set(string $key, $value)
 * @method static mixed get(string $key)
 * @method static mixed send(string $to, string $body)
 * @method static array delivery(int $referenceId)
 * @method static int getCredits()
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return new \Nishtman\Sms\Sms();
    }
}