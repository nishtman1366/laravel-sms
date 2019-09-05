<?php

namespace Nishtman\Sms\Modules;


use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class SmsIr implements SmsInterface
{

    public $token;
    public $apiKey;
    public $secretKey;
    public $client;

    /**
     * SmsIr constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $client = new Client([
            'base_uri' => 'http://RestfulSms.com',
            'timeout' => 10.0,
        ]);
        $this->client = $client;
        $data = [
            'json' => [
                'UserApiKey' => Config::get('sms.providers.SmsIr.apiKey'),
                'SecretKey' => Config::get('sms.providers.SmsIr.secretKey')
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ];
        try {
            $response = $client->post('api/Token', $data);
            $result = json_decode($response->getBody());
            if (!$result->IsSuccessful)
                throw new \Exception('Unable to retrieve security token');

            $this->token = $result->TokenKey;
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function send(array $to, array $text, bool $isFlash = false): array
    {
        $data = [
            'json' => [
                'Messages' => $text,
                'MobileNumbers' => $to,
                'LineNumber' => Config::get('sms.providers.smsIr.lineNumber'),
                'SendDateTime' => null,
                'CanContinueInCaseOfError' => false
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-sms-ir-secure-token' => $this->token,
            ]
        ];
        $result = $this->client->post('api/MessageSend', $data);
        $result = json_decode($result->getBody(), true);
        $status = 0;
        $r = [];
        if ($result['IsSuccessful']) {
            $status = 1;
            foreach ($result['ids'] as $id) {
                $r[] = $id['ID'];
            }
        }
        $finalData = [
            'status' => $status,
            'message' => $result['Message'],
            'referenceId' => implode(',', $r)
        ];
        return $finalData;
    }

    public function delivery(int $referenceId): array
    {
        $data = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-sms-ir-secure-token' => $this->token,
            ]
        ];
        $result = $this->client->get('api/MessageSend?id=' . $referenceId, $data);
        $result = json_decode($result->getBody(), true);
        if ($result['IsSuccessful']) {
            return [$result['Messages']['DeliveryStatus']];
        }
        return [0];
    }

    public function getCredits(): int
    {
        $data = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-sms-ir-secure-token' => $this->token,
            ]
        ];
        $result = $this->client->get('api/credit', $data);
        $result = json_decode($result->getBody(), true);
        if ($result['IsSuccessful']) {
            return $result['Credit'];
        }
        return 0;
    }

    public function message(int $status): string
    {
        // TODO: Implement message() method.
    }
}