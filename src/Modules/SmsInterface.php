<?php
/**
 * Created by PhpStorm.
 * User: nishtman
 * Date: 9/2/19
 * Time: 4:13 PM
 */

namespace Nishtman\Sms\Modules;


interface SmsInterface
{
    public function send(string $to, string $text, bool $isFlash): array;

    public function delivery(int $referenceId): array;

    public function getCredits(): int;

    public function message(int $status): string;
}