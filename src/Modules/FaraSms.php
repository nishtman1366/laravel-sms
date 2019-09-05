<?php
/**
 * Created by PhpStorm.
 * User: nishtman
 * Date: 9/5/19
 * Time: 7:56 AM
 */

namespace Nishtman\Sms\Modules;


class FaraSms implements SmsInterface
{

    public function send(array $to, array $text, bool $isFlash): array
    {
        // TODO: Implement send() method.
    }

    public function delivery(int $referenceId): array
    {
        // TODO: Implement delivery() method.
    }

    public function getCredits(): int
    {
        // TODO: Implement getCredits() method.
    }

    public function message(int $status): string
    {
        // TODO: Implement message() method.
    }
}