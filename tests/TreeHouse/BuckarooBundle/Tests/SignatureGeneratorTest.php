<?php

namespace TreeHouse\BuckarooBundle\Tests;

use TreeHouse\BuckarooBundle\SignatureGenerator;

class SignatureGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_generate_a_signature()
    {
        $secretKey = '1234';
        $data = ['fruit' => 'apple'];
        $generator = new SignatureGenerator($secretKey);

        $expectedSignature = '7110eda4d09e062aa5e4a390b0a572ac0d2c0220';
        $actualSignature = $generator->generate($data);

        $this->assertSame($expectedSignature, $actualSignature);
    }
}
