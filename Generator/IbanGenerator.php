<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 15.04.14
 * Time: 9:33
 */

namespace Rodgermd\IbanApiDeBundle\Generator;

use Rodgermd\IbanApiDeBundle\Api\IbanAPI;
use Rodgermd\IbanApiDeBundle\Exception\IbanApiErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IbanGenerator
 *
 * @package Rodgermd\IbanApiDeBundle\Generator
 */
class IbanGenerator
{
    /** @var \Symfony\Component\HttpFoundation\Request */
    protected $request;
    /** @var \Rodgermd\IbanApiDeBundle\Api\IbanAPI */
    protected $api;

    /**
     * Object constructor
     *
     * @param Request $request
     * @param IbanAPI $api
     */
    public function __construct(Request $request, IbanAPI $api)
    {
        $this->request = $request;
        $this->api     = $api;
    }

    /**
     * Generates IBAN code from request parameters
     *
     * @return Response
     */
    public function generateIban()
    {
        try {
            $iban = $this->api->generateIBAN($this->getCountryCode(), $this->getBankCode(), $this->getAccountNr());
            $bic  = $this->api->getBicFromIban($iban);

            return $this->jsonResponse(compact('iban', 'bic'));
        } catch (IbanApiErrorException $exception) {
            return $this->failedResponse();
        }
    }

    /**
     * Checks if iban is valid
     *
     * @return Response
     */
    public function validateIban()
    {
        $iban = $this->getIban();

        return $this->jsonResponse(array('iban' => $iban, 'valid' => $this->api->validateIban($iban)));
    }

    /**
     * Generates BIC from IBAN
     *
     * @return Response
     */
    public function generateBic()
    {
        $iban = $this->getIban();

        try {
            $bic = $this->api->getBicFromIban($iban);

            return $this->jsonResponse(compact('iban', 'bic'));
        } catch (IbanApiErrorException $exception) {
            return $this->failedResponse();
        }

    }

    /**
     * Prepares json response
     *
     * @param array $contentArray
     *
     * @return Response
     */
    protected function jsonResponse($contentArray)
    {
        if (!array_key_exists('success', $contentArray)) {
            $contentArray['success'] = true;
        }

        return new Response(json_encode($contentArray), 200, array('content-type' => 'application/json'));
    }

    protected function failedResponse()
    {
        return $this->jsonResponse(array('success' => false));
    }

    /**
     * Gets IBAN from request
     *
     * @return string
     */
    protected function getIban()
    {
        return $this->request->get('iban');
    }

    /**
     * Gets country code from request
     *
     * @return string
     */
    protected function getCountryCode()
    {
        return $this->request->get('country');
    }

    /**
     * Gets Bank code from request
     *
     * @return string
     */
    protected function getBankCode()
    {
        return $this->request->get('bank_code');
    }

    /**
     * Gets account number from request
     *
     * @return string
     */
    protected function getAccountNr()
    {
        return $this->request->get('account_nr');
    }
} 