<?php
/**
 * Created by PhpStorm.
 * User: nishtman
 * Date: 9/3/19
 * Time: 8:45 AM
 */

namespace Nishtman\Sms\Modules;


use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

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
                'UserApiKey' => Config::get('sms.providers.smsIr.apiKey'),
                'SecretKey' => Config::get('sms.providers.smsIr.secretKey')
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ];
        print('trying to get token' . PHP_EOL);
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


    public function send(string $to, string $text, bool $isFlash = false): array
    {
        $data = [
            'json' => [
                'Messages' => explode(',', $text),
                'MobileNumbers' => explode(',', $to),
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
        return json_decode($result->getBody());
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