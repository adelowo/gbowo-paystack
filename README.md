## Gbowo-Paystack - An extra set of plugins for Gbowo's Paystack adapter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adelowo/gbowo-paystack.svg?style=flat-square)](https://packagist.org/packages/adelowo/gbowo-paystack)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/adelowo/gbowo-paystack/master.svg?style=flat-square)](https://travis-ci.org/adelowo/gbowo-paystack)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/adelowo/gbowo-paystack.svg?style=flat-square)](https://scrutinizer-ci.com/g/adelowo/gbowo-paystack/?branch=master)
[![Quality Score](https://img.shields.io/scrutinizer/g/adelowo/gbowo-paystack.svg?style=flat-square)](https://scrutinizer-ci.com/g/adelowo/gbowo-paystack)
[![Total Downloads](https://img.shields.io/packagist/dt/adelowo/gbowo-paystack.svg?style=flat-square)](https://packagist.org/packages/adelowo/gbowo-paystack)

> This package is an add-on to the Paystack Adapter provided by [Gbowo][gbowo] and is therefore not guaranteed to have the same API as that of an add-on package of any alternative payment adapter supported by [Gbowo][gbowo]

- [Installation](#install)
- [Available Plugins](#plugins)


<h2 id="install"></h2>

```bash
$ composer require adelowo/gbowo-paystack
```
### Usage

[Gbowo's doc][gbowo] is highly recommended and is a good place to start.

Depending on what you are trying to accomplish, you'd ideally want to take a look at all available plugins here:

<h2 id="plugins"></h2>

> Only add plugins you need. There isn't a reason to add everything into the adapter.

- `Paystack\Customer\CreateCustomer` : Create a new customer

```php

$paystack->addPlugin(new Paystack\Customer\CreateCustomer(PaystackAdapter::API_LINK));

$data = $paystack->createCustomer(["email" => "me@lanreadelowo.com", "first_name" => "Lanre", "last_name" => "Adelowo"]);

//$data contains the details of the newly created customer

```

- `Paystack\Customer\UpdateCustomer` : Update a customer's data

```php

$paystack->addPlugin(new Paystack\Customer\UpdateCustomer(PaystackAdapter::API_LINK));

$data = $paystack->updateCustomer("customerCode", ["email" => "lanre@coolstartup.com"]);

//$data contains the details of the updated customer

```

- `Paystack\Customer\DeactivateAuthorization` : Deactivate the authorization code for a user. If you do this, you are essentially ___forgetting___ a user's card and would lose the ability to charge that card.
 
> Example usecase => When a user is deleting his account or deletes a card.

```php

$paystack->addPlugin(new Paystack\Transaction\DeactivateAuthorization(PaystackAdapter::API_LINK));

$isDeactivated = $paystack->deactivateAuthorization("AUTH_cod3_h3r3");

//$isDeactivated is a boolean which truthiness determines if the authorization code was succesffuly deactivated
```

- `Paystack\Transaction\ExportTransactions` : Export all of your transactions with Paystack

```php

$paystack->addPlugin(new Paystack\Transaction\ExportTransactions(PaystackAdapter::API_LINK));

//can also pass in an array into the method call,
//e.g ["settled" => true, "currency" => "NGN", "status" => "Some status"]
//The dictionary would be converted to a query string which is sent alongside the request. 
//Do review the docs for valid params.
$pathToFile = $paystack->exportTransactions();

//$pathToFile would contain a link to a csv file which you then have to download
```

- `Paystack\Transaction\GetTransaction` : Fetch the details of a specific transaction

```php

$paystack->addPlugin(new Paystack\Transaction\GetTransaction(PaystackAdapter::API_LINK));

$data = $paystack->getTransaction("20911");

//$data would contain everything paystack knows about that transaction
```

- `Paystack\Subscription\CreateSubscription` - Add a new subscription to the dashboard

```php
$paystack->addPlugin(new Paystack\Subscription\CreateSubscription(PaystackAdapter::API_LINK));

$data = $paystack->createSubscription(string $customerCode, string $planCode, string $customerAuthCode = "");
//The customer auth code can be excluded as it is only useful for customers with multiple authorizations.
//Please check the docs.

```

- `Paystack\Subscription\GetAllSubscriptions` - Retrieve all subscriptions in the dashboard

```php
$paystack->addPlugin(new Paystack\Subscription\GetAllSubscriptions(PaystackAdapter::API_LINK));

$data = $paystack->getAllSubscriptions();

```

- `Paystack\Subscription\GetSubscription` - Retrieve a certain subscription from the dashboard

```php
$paystack->addPlugin(new Paystack\Subscription\GetSubscription(PaystackAdapter::API_LINK));

$data = $paystack->getSubscription("SUB_code");

```

- `Paystack\Plan\CreatePlan` - Adds a new plan to the dashboard

```php
$paystack->addPlugin(new Paystack\Plan\CreatePlan(PaystackAdapter::API_LINK));

$params = ["name" => "some plan", "amount" => 1000, "interval" => "hourly"];
//visit the api docs to see all possible data that can be sent

$data = $paystack->createPlan($params);

```

- `Paystack\Plan\UpdatePlan` - Updates a plan in the dashboard

```php
$paystack->addPlugin(new Paystack\Plan\UpdatePlan(PaystackAdapter::API_LINK));

$params = ["name" => "renaming this plan", "amount" => 2000, "interval" => "weekly"];
//visit the api docs to see all possible data that can be sent

$status = $paystack->updatePlan(
	"PLN_gx2wn530m0i3w3m",
         ["name" => "renaming this plan yet again", "amount" => \Gbowo\toKobo(200000), "interval" => "weekly"]);

	
```

- `Paystack\Bank\ListBanks` - Fetch a list of all banks known to Paystack

```php

$paystack->addPlugin(new ListBanks(PaystackAdapter::API_LINK));

$banks = $paystack->listBanks();
// $paystack->listBanks(["perPage" => 20, "page" => 2]); //show 20 banks and show results from the second page (the results are paginated) 

```


- `Paystack\Bank\GetBVN` - Fetch the details of a user's BVN (Bank Verification Number)

```php
$paystack->addPlugin(new GetBVN(PaystackAdapter::API_LINK));
$data = $paystack->getBVN("12345678901"); //Must be 11 digits, else an exception is thrown
```


- `Paystack\Bank\GetAccountDetails` - Fetch the details of a user's account number.

```php

$paystack->addPlugin(new GetAccountDetails(PaystackAdapter::API_LINK));

//Yeah, that's a valid account number. Run the code to get my bank details and throw me some cash :)
$data = $paystack->getAccountDetails(["account_number" => "0115544526", "bank_code" => "058"]));
```

- `Paystack\Bank\GetCardBIN` - Fetch the details of a card via it's BIN (Bank Identification number)

```php
$paystack->addPlugin(new GetCardBIN(PaystackAdapter::API_LINK));
$data = $paystack->getCardBIN("123456");
```


### Contributing

Awesome, I'd love that. Fork, send PR. But hey, unit testing is one honking great idea. Let's have more of that.

### Bug Reports, Issue tracking and Security Vulnerabilities

Please make use of the [issue tracker](https://github.com/adelowo/gbowo-paystack/issues) for bug reports, feature request and others except Security issues. If you do discover a vulnerability, please send a mail to `me@lanreadelowo.com`.

### License
[MIT](http://opensource.org/licenses/MIT)

[gbowo]: https://github.com/adelowo/gbowo
