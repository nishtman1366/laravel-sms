<?php
/**
 * Created by PhpStorm.
 * User: nishtman
 * Date: 9/5/19
 * Time: 7:56 AM
 */

namespace Nishtman\Sms\Modules;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class Kavehnegar implements SmsInterface
{
    private $apiKey;
    private $client;

    /**
     * Kavehnegar constructor.
     */
    public function __construct()
    {
        $this->apiKey = Config::get('sms.providers.Kavehnegar.apiKey');
        $client = new Client([
            'base_uri' => 'http://api.kavenegar.com',
            'timeout' => 10.0,
        ]);
        $this->client = $client;
    }

    public function send(array $to, array $text, bool $isFlash = false): array
    {
        $data = [
            'form_params' => [
                'message' => implode(',', $text),
                'receptor' => implode(',', $to),
                'sender' => Config::get('sms.providers.Kavehnegar.sender'),
                'date' => null,
                'type' => null,
                'localid' => null
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'apikey' => $this->apiKey,
            ]
        ];
        $uri = sprintf('v1/%s/sms/send', $this->apiKey);
        try {
            $result = $this->client->post($uri, $data);
            $result = json_decode($result->getBody(), true);
            $status = 0;
            $r = [];
            if ($result['result']['code'] == 200) {
                $status = 1000;
                foreach ($result['items'] as $item) {
                    $r[] = $item;
                }
            }
            $finalData = [
                'status' => $status,
                'message' => $this->message($status),
                'referenceId' => implode(',', $r)
            ];
        } catch (ClientException $exception) {
            $finalData = [
                'status' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
        return $finalData;
    }

    public function delivery(int $referenceId): array
    {
        $data = [
            'form_params' => [
                'id' => $referenceId,
                'type' => 1,
            ],
            'headers' => [
                'apikey' => $this->apiKey,
            ]
        ];
        $uri = sprintf('v1/%s/sms/status', $this->apiKey);
        try {
            $result = $this->client->post($uri, $data);
            $result = json_decode($result->getBody(), true);
            $finalData = [$result[0]['status']];
        } catch (ClientException $exception) {
            $finalData = [
                'status' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
        return $finalData;
    }

    public function getCredits(): int
    {
        $data = [
            'headers' => [
                'apikey' => $this->apiKey,
            ]
        ];
        try {
            $result = $this->client->get('v2/account/info', $data);
            $result = json_decode($result->getBody(), true);
            return $result['items'][0]['balance'];
        } catch (ClientException $exception) {
            return 0;
        }
    }

    public function message(int $status): string
    {
        $data = [
            1 => 'نام کاربری یا رمز عبور معتبر نمی باشد .',
            2 => 'آرایه ها خالی می باشد.',
            3 => 'طول آرایه بیشتر از 100 می باشد .',
            4 => 'طول آرایه ی فرستنده و گیرنده و متن پیام با یکدیگر تطابق ندارد .',
            5 => 'امکان گرفتن پیام جدید وجود ندارد .',
            6 => ' - حساب کاربری غیر فعال می باشد.'
                . '- نام کاربری و یا رمز عبور خود را به درستی وارد نمی کنید .'
                . '- در صورتی که به تازگی وب سرویس را فعال کرده اید از منوی تنظیمات _رمز عبور ، رمز عبور وب سرویس خود را مجدد تنظیم نمائید . ',
            7 => 'امکان دسترسی به خط مورد نظر وجود ندارد .',
            8 => 'شماره گیرنده نامعتبر است .',
            9 => 'حساب اعتبار ریالی مورد نیاز را دارا نمی باشد.',
            10 => 'خطایی در سیستم رخ داده است . دوباره سعی کنید .',
            11 => 'نامعتبر می باشد . IP',
            20 => 'شماره مخاطب فیلتر شده می باشد.',
            21 => 'ارتباط با سرویس دهنده قطع می باشد.',
            24 => 'امکان استفاده از این سرویس در پلن رایگان وجود ندارد.',
            1000 => 'پیام با موفقیت ارسال شد.',

        ];
        return $data[$status];
    }
}