<?php

namespace TreeHouse\BuckarooBundle\Tests\Request;

use Money\Currency;
use Money\Money;
use TreeHouse\BuckarooBundle\Model\Mandate;
use TreeHouse\BuckarooBundle\Request\SimpleSepaDirectDebitTransactionRequest;

class SimpleSepaDirectDebitTransactionRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_converted_to_array()
    {
        $request = new SimpleSepaDirectDebitTransactionRequest();
        $request->setInvoiceNumber($invoiceNumber = 123456789);
        $request->setAmount($amount = new Money(1234, new Currency('EUR')));
        $request->setCustomerBic($bic = 'ING2NLA');
        $request->setCustomerIban($iban = 'NL12ING0123456789');
        $request->setCustomerAccountName($bankAccountHolder = 'P. Puk');
        $request->setDatetimeCollect($datetimeCollect = new \DateTime());
        $request->setMandate(new Mandate($mandateReference = 1234567890, $datetimeMandate = new \DateTime()));

        $data = $request->toArray();

        $this->assertSame('' . $amount->getAmount() / 100, $data['amount']);
        $this->assertSame($amount->getCurrency()->getCode(), $data['currency']);
        $this->assertSame('simplesepadirectdebit', $data['payment_method']);
        $this->assertSame('Pay', $data['service_simplesepadirectdebit_action']);
        $this->assertSame($bic, $data['service_simplesepadirectdebit_CustomerBIC']);
        $this->assertSame($iban, $data['service_simplesepadirectdebit_CustomerIBAN']);
        $this->assertSame($datetimeMandate->format('Y-m-d'), $data['service_simplesepadirectdebit_MandateDate']);
        $this->assertSame($mandateReference, $data['service_simplesepadirectdebit_MandateReference']);
        $this->assertSame($bankAccountHolder, $data['customeraccountname']);
        $this->assertSame(true, $data['StartRecurrent']);
    }
}
