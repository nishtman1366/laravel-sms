<?php
/**
 * Created by PhpStorm.
 * User: nishtman
 * Date: 9/2/19
 * Time: 3:16 PM
 */

namespace Nishtman\Sms;


use Illuminate\Support\Facades\Config;

class Sms
{
    private $provider;
    private $to;

    /**
     * Sms constructor.
     */
    public function __construct()
    {
        $this->provider = Config::get('sms.default');
    }

    /**
     * @param string $provider
     * @return Sms
     */
    public function provider(string $provider)
    {
        if (!is_null($provider))
            $this->provider = $provider;
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set(string $key, $value)
    {
        $this->{$key} = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return null
     */
    public function get(string $key)
    {
        if (property_exists($this, $key))
            return $this->{$key};

        return null;
    }

    public function send(string $to, string $text)
    {
        $className = 'Nishtman\\Sms\\Modules\\' . $this->provider;
        $module = new $className;
        $result = $module->send($to, $text);
        return $result;
    }

    public function delivery(int $referenceId): array
    {
        $className = 'Nishtman\\Sms\\Modules\\' . $this->provider;
        $module = new $className;
        $result = $module->delivery($referenceId);
        return $result;
    }

    public function getCredits(): int
    {
        $className = 'Nishtman\\Sms\\Modules\\' . $this->provider;
        $module = new $className;
        $result = $module->getCredits();
        return $result;
    }
}