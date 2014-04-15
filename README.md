IbanApiDebundle
===============

Integration of http://www.iban-api.de/soap-service/

Add Controller for ajax actions
====

Add to routing.yml:

~~~
rodgermd_iban_controller:
    resource: "@RodgermdIbanApiDeBundle/Controller/"
    type: annotation
    prefix: /iban
~~~

Usage
===

Get service:

~~~
$api = $container->get('rodgermd.iban.api');
~~~

Get IBAN code
---
Throws IbanApiErrorException on error

~~~
$iban = $api->generateIBAN($countryCode, $bankIdentification, $accountNr); 
~~~

Validate IBAN code
---
~~~
$valid = $api->validateIban($iban);
~~~

Get BIC code from IBAN
---
Throws IbanApiErrorException on error

~~~
$bic = $api->getBicFromIban($iban); 
~~~
