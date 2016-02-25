<?php

namespace TreeHouse\BuckarooBundle\Tests\Request;

use Money\Currency;
use Money\Money;
use TreeHouse\BuckarooBundle\Model\ReturnUrl;
use TreeHouse\BuckarooBundle\Request\IdealTransactionRequest;

class IdealTransactionRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_converted_to_array()
    {
        $request = new IdealTransactionRequest();
        $request->setAmount($amount = new Money(1234, new Currency('EUR')));
        $request->setDescription($description = 'This is a nice description');
        $request->setIssuer($issuer = 'ING');
        $request->setReturnUrl($returnUrl = new ReturnUrl('http://www.return.to/here/'));
        $request->setInvoiceNumber($invoiceNumber = 1234567890);

        $data = $request->toArray();

        $this->assertSame('' . $amount->getAmount() / 100, $data['amount']);
        $this->assertSame($amount->getCurrency()->getCode(), $data['currency']);
        $this->assertSame($description, $data['description']);
        $this->assertSame('ideal', $data['payment_method']);
        $this->assertSame($returnUrl->getSuccess(), $data['return']);
        $this->assertSame($returnUrl->getCancel(), $data['returncancel']);
        $this->assertSame($returnUrl->getError(), $data['returnerror']);
        $this->assertSame($returnUrl->getReject(), $data['returnreject']);
        $this->assertSame('Pay', $data['service_ideal_action']);
        $this->assertSame($issuer, $data['service_ideal_issuer']);
    }
}
