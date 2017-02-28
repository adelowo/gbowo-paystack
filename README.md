# Gbowo-Paystack - An extra set of plugins for Gbowo's Paystack adapter

> WIP

> This package is an add-on to the Paystack Adapter provided by [Gbowo][gbowo] and is therefore not guaranteed to have the same API as that of an add-on package of any alternative payment adapter supported by [Gbowo][gbowo]

- [Installation](#install)
- [Available Plugins](#plugins)


<h2 id="install"></h2>


<h2 id="plugins"></h2>

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

$paystack->addPlugin(new Paystack\Customer\DeactivateAuthorization(PaystackAdapter::API_LINK));

$isDeactivated = $paystack->deactivateAuthorization("AUTH_cod3_h3r3");

//$isDeactivated is a boolean which truthiness determines if the authorization code was succesffuly deactivated
```

- `Paystack\Transaction\ExportTransactions` : Export all of your transactions with Paystack

```php

$paystack->addPlugin(new Paystack\Customer\ExportTransactions(PaystackAdapter::API_LINK));

//can also pass in an array into the method call,
//e.g ["settled" => true, "currency" => "NGN", "status" => "Some status"]
//The dictionary would be converted to a query string which is sent alongside the request. 
//Do review the docs for valid params.
$pathToFile = $paystack->exportTransactions();

//$pathToFile would contain a link to a csv file which you then have to download
```

- `Paystack\Transaction\GetTransaction` : Fetch the details of a specific transaction

```php

$paystack->addPlugin(new Paystack\Customer\GetTransaction(PaystackAdapter::API_LINK));

$data = $paystack->getTransaction("20911");

//$data would contain everything paystack knows about that transaction
```


### Contributing

Awesome, I'd love that. Fork, send PR. But hey, unit testing is one honking great idea. Let's have more of that.

### Bug Reports, Issue tracking and Security Vulnerabilities

Please make use of the [issue tracker](https://github.com/adelowo/gbowo-paystack/issues) for bug reports, feature request and others except Security issues. If you do discover a vulnerability, please send a mail to `me@lanreadelowo.com`.

### License
[MIT](http://opensource.org/licenses/MIT)

[gbowo]: https://github.com/adelowo/gbowo