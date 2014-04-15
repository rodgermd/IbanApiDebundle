<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 15.04.14
 * Time: 9:54
 */

namespace Rodgermd\IbanApiDeBundle\Controller;

use Rodgermd\IbanApiDeBundle\Generator\IbanGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class IbanController
 *
 * @package Rodgermd\IbanApiDeBundle\Controller
 */
class IbanController extends Controller
{

    /**
     * Generate IBAN action
     * @Route("/generate-iban", name="rodgermd.iban.generate", options={"expose"=true})
     */
    public function generateIbanAction()
    {
        return $this->getGenerator()->generateIban();
    }

    /**
     * Validates IBAN
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/validate-iban", name="rodgermd.iban.validate", options={"expose"=true})
     */
    public function validateIbanAction()
    {
        return $this->getGenerator()->validateIban();
    }

    /**
     * Generates BIC from IBAN
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/generate-bic", name="rodgermd.bic.generate", options={"expose"=true})
     */
    public function generateBicAction()
    {
        return $this->getGenerator()->generateBic();
    }

    /**
     * Gets generator
     *
     * @return IbanGenerator
     */
    protected function getGenerator()
    {
        return $this->container->get('rodgermd.iban.generator');
    }
} 