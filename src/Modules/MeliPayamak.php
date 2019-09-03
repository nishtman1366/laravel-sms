<?php

namespace Nishtman\Sms\Modules;

use Illuminate\Support\Facades\Config;
use SoapClient;

class MeliPayamak implements SmsInterface
{

    private $username;
    private $password;
    private $number;

    /**
     * MeliPayamak constructor.
     */
    public function __construct()
    {
        $this->username = Config::get('sms.providers.MeliPayamak.username');
        $this->password = Config::get('sms.providers.MeliPayamak.password');
        $this->number = Config::get('sms.providers.MeliPayamak.number');
    }


    public function send(string $to, string $text, bool $isFlash = false): array
    {
        $data = [
            'username' => $this->username,
            'password' => $this->password,
            'to' => $to,
            'from' => $this->number,
            'text' => $text,
            'isflash' => $isFlash,
        ];


        $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', ['encoding' => 'UTF-8']);

        $result = $sms_client->SendSimpleSMS2($data)->SendSimpleSMS2Result;
        $status = 0;
        $referenceId = null;
        if ($result > 12) {
            $status = 1;
            $referenceId = $result;
        }
        $finalResult = [
            'status' => $status,
            'message' => $this->message($status),
            'referenceId' => $referenceId
        ];

        return $finalResult;
    }


    public function delivery(int $referenceId): array
    {
        $data = [
            'username' => $this->username,
            'password' => $this->password,
            'recId' => $referenceId,
        ];

        $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', ['encoding' => 'UTF-8']);

        $result = $sms_client->GetDelivery($data)->GetDeliveryResult;
        return [$result];
    }

    public function getCredits(): int
    {
        $data = [
            'username' => $this->username,
            'password' => $this->password,
        ];
        $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', ['encoding' => 'UTF-8']);
        $result = $sms_client->GetCredit($data)->GetCreditResult;
        return round($result);
    }

    public function message(int $status): string
    {
        $messages = [
            'نام کاربری یا رمزعبور اشتباه است.',
            'درخواست با موفقیت انجام شد.',
            'اعتبار کافی نمی باشد.',
            'محدودیت در ارسال روزانه',
            'محدودیت در حجم ارسال',
            'شماره فرستنده معتبر نمی باشد.',
            'سامانه در حال بروزرسانی می باشد.',
            'متن حاوی کلمات فیلتر شده می باشد.',
            'ارسال از خطوط عمومی از طریق وب سرویس امکان پذیر نمی باشد.',
            'کاربر مورد نظر فعال نمی باشد.',
            'ارسال نشده',
            'مدارک کاربر کامل نمی باشد.',
        ];

        return $messages[$status];
    }
}