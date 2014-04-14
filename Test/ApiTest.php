<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 14.04.14
 * Time: 16:12
 */

namespace Rodgermd\IbanApiDeBundle\Test;

use Rodgermd\IbanApiDeBundle\Api\IbanAPI;
use Rodgermd\IbanApiDeBundle\Exception\IbanApiErrorException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ApiTest
 *
 * @package Rodgermd\IbanApiDeBundle\Test
 */
class ApiTest extends WebTestCase
{
    /** @var  IbanAPI */
    protected $api;

    /**
     * On before each test
     */
    public function setUp()
    {
        $this->api = new IbanAPI();
    }

    /**
     * tests generate IBAN
     */
    public function testGenerateIBAN()
    {
        try {
            $this->api->generateIBAN('', '', '');
            $this->assertTrue(false, 'The exception should be thrown');
        } catch (IbanApiErrorException $exception) {
        }

        $result = $this->api->generateIBAN('DE', '50010517', '648479930');
        $this->assertEquals('DE48500105170648479930', $result);
    }

    /**
     * Validates IBAN
     */
    public function testValidateIBAN()
    {
        $this->assertFalse($this->api->validateIban('123'));
        $this->assertTrue($this->api->validateIban('DE48500105170648479930'));
    }

    /**
     * Test get BIC code
     */
    public function testGetBicFromIban()
    {
        try {
            $this->api->getBicFromIban('test');
            $this->assertTrue(false, 'The error should be thrown');
        } catch (IbanApiErrorException $exception) {
        }

        $result = $this->api->getBicFromIban('DE48500105170648479930');
        $this->assertEquals('INGDDEFFXXX', $result);
    }
} 