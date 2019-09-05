nishtman/laravel-sms
======
This library helps developers to easily call the web services of sms providers in Iran using the Larval Framework.

## Supported providers
- [MeliPayamak](http://melipayamak.ir "MeliPayamak")
- [HostIranSms](http://sms.hostiran.ir "HostIranSms")
- [SmsIr](http://sms.ir "sms.ir")
- [Ghasedak](http://ghasedak.io "ghasedak.io")
- [Kavenegar](http://kavenegar.com "kavenegar.com")

## Installation

#### Requirements:
- `php >= 7.0`
- `laravel >= 5.0`

Run the Composer update command

    $ composer require nishtman/sms

- First, enter the settings for your short message provider in the `config/sms.php` file and enter the module name you want in the `default` section.

<a name="basic-usage"></a>
## Usage

#### Sending sms
```php
public function send()
{
	/*
	* Instance the sms object
	*/
	$sms = new \Nishtman\Sms\Sms();
	$result = $sms->send('09123456789', 'text message');
	/*
	* or you can use facades
	*/
	$result = Nishtman\Sms\Facades\Sms::send('09123456789', 'text message');
}
```
#### Selecting provider
```php
public function send()
{
        /*
         * Instance the sms object
         */
        $sms = new \Nishtman\Sms\Sms();
        $result = $sms->provider('HostIran')->send('09123456789', 'text message')
        /*
         * or you can use facades
         */
        $result = Nishtman\Sms\Facades\Sms::provider('HostIran')->send('09123456789', 'text message');
}
```

## Methods and api

```php
/*
* Set the provider
*/
provider(string $provider): \Nishtman\Sms\sms
```

```php
/*
* Set sms message
*/
send(string $to, string $text): array

/*
* Array
* (
*     [status] => 'integer status code'
*     [message] => 'string message'
*     [referenceId] => 'integer reference id to get the delivery report'
* )
*/

```

```php
/*
* Get the delivery status
*/
delivery(int $referenceId): array

/*
* Array
* (
*     [string] => 'string status text'
* )
*/

```

```php
/*
* Get the credits value
*/
getCredits(int $referenceId): int

/*
* Array
* (
*     [int] => 'your credit amount'
* )
*/

```

## Test

## Pull requests

## License
