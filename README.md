BuckarooBundle
==============

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

Symfony bundle to integrate Buckaroo functionality.


## Usage

The bundle provides a client that can interact with Buckaroo. See the
following example for a SEPA direct debit transaction:

```php
$request = new SimpleSepaDirectDebitTransactionRequest();
$request->setAmount(Money::EUR(1000));
$request->setCustomerAccountName($bankAccountHolder);
$request->setCustomerIban($iban);
$request->setInvoiceNumber($invoiceNumber);
$request->setMandate(new Mandate($reference, new \DateTime());

$this->buckaroo->send($request);
```


## Documentation

1. [Setup][doc-setup]

[doc-setup]: /docs/01-setup.md


## Security

If you discover any security related issues, please email dev@treehouse.nl
instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


## Credits

- [Peter Kruithof][link-author]
- [All Contributors][link-contributors]


[ico-version]:       https://img.shields.io/packagist/v/treehouselabs/buckaroo-bundle.svg?style=flat-square
[ico-license]:       https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]:        https://img.shields.io/travis/treehouselabs/buckaroo-bundle/master.svg?style=flat-square
[ico-scrutinizer]:   https://img.shields.io/scrutinizer/coverage/g/treehouselabs/buckaroo-bundle.svg?style=flat-square
[ico-code-quality]:  https://img.shields.io/scrutinizer/g/treehouselabs/buckaroo-bundle.svg?style=flat-square
[ico-downloads]:     https://img.shields.io/packagist/dt/treehouselabs/buckaroo-bundle.svg?style=flat-square

[link-packagist]:    https://packagist.org/packages/treehouselabs/buckaroo-bundle
[link-travis]:       https://travis-ci.org/treehouselabs/buckaroo-bundle
[link-scrutinizer]:  https://scrutinizer-ci.com/g/treehouselabs/buckaroo-bundle/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/treehouselabs/buckaroo-bundle
[link-downloads]:    https://packagist.org/packages/treehouselabs/buckaroo-bundle
[link-author]:       https://github.com/pkruithof
[link-contributors]: ../../contributors
