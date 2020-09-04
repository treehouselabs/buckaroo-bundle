<?php

namespace TreeHouse\BuckarooBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use TreeHouse\BuckarooBundle\Request\IdealTransactionSpecificationRequest;

class IdealTransactionSpecificationRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_converted_to_array()
    {
        $request = new IdealTransactionSpecificationRequest();
        $data = $request->toArray();

        $this->assertSame('ideal', $data['services']);
        $this->assertSame(true, $data['latestversiononly']);
    }
}
