<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 14.04.14
 * Time: 16:04
 */

namespace Rodgermd\IbanApiDeBundle\Api;

use Rodgermd\IbanApiDeBundle\Exception\IbanApiErrorException;

/**
 * Class IbanAPI
 *
 * @package Rodgermd\IbanApiDeBundle\Api
 */
class IbanAPI
{

    const WSDL  = 'http://www.iban-api.de:8081/IbanService?wsdl';
    const ERROR = 'ERROR';


    /** @var \SoapClient */
    protected $client;

    /**
     * Object constructor
     */
    public function __construct()
    {
        $this->client = new \SoapClient(self::WSDL, array('trace' => true));
    }

    /**
     * Generates IBAN code
     *
     * @param $countryCode
     * @param $bankIdentification
     * @param $accountNr
     *
     * @throws \Rodgermd\IbanApiDeBundle\Exception\IbanApiErrorException
     * @return string
     */
    public function generateIBAN($countryCode, $bankIdentification, $accountNr)
    {
        if (!($countryCode && $bankIdentification && $accountNr)) {
            throw new IbanApiErrorException("Missing data");
        }

        $function = 'generateIban';

        $result = $this->client->__soapCall(
            $function,
            array(
                array(
                    'contryCode'    => $countryCode,
                    'bankIdent'     => $bankIdentification,
                    'accountNumber' => $accountNr
                )
            )
        );

        return $this->parseResult($result, $function);
    }

    /**
     * Tests get BIC code from IBANE
     *
     * @param $iban
     *
     * @throws \Rodgermd\IbanApiDeBundle\Exception\IbanApiErrorException
     * @return string
     */
    public function getBicFromIban($iban)
    {
        if (!$iban) {
            throw new IbanApiErrorException("Missing data");
        }

        $function = 'getBicFromIban';

        return $this->parseResult($this->client->__soapCall($function, array(array('iban' => $iban,))), $function);
    }

    /**
     * Validates IBAN
     *
     * @param $iban
     *
     * @return boolean
     */
    public function validateIban($iban)
    {
        $function = 'validateIban';

        return $this->parseResult($this->client->__soapCall($function, array(array('iban' => $iban,))), $function) == 'true';
    }

    /**
     * Parses the result
     *
     * @param \stdClass $result
     * @param           $function
     *
     * @return mixed
     * @throws \Rodgermd\IbanApiDeBundle\Exception\IbanApiErrorException
     */
    protected function parseResult(\stdClass $result, $function)
    {
        if ($result->$function == self::ERROR) {
            throw new IbanApiErrorException(sprintf('Error calling %s, %s', $function, $this->client->__getLastRequest()));
        }

        return $result->$function;
    }
} 