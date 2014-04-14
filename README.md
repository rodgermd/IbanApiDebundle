IbanApiDebundle
===============

Integration of http://www.iban-api.de/soap-service/

Usage
-----

Get service:

~~~
$api = $container->get('rodgermd.iban.api');

$iban = $api->generateIBAN($countryCode, $bankIdentification, $accountNr); # Throws IbanApiErrorException on error

$valid = $api->validateIban($iban);

$bic = $api->getBicFromIban($iban); # Throws IbanApiErrorException on error
~~~
