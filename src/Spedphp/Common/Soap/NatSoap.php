<?php

/**
 * Spedphp (http://www.nfephp.org/)
 *
 * @link      http://github.com/nfephp-org/spedphp for the canonical source repository
 * @copyright Copyright (c) 2008-2013 NFePHP (http://www.nfephp.org)
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @package   Spedphp
 */

namespace Spedphp\Common\Soap;

use Spedphp\Common\Soap\CorrectedSoapClient;
use Spedphp\Common\Exception\NfephpException;
use LSS\XML2Array;

class NatSoap
{

    public $soapDebug = '';
    public $soapTimeout = 10;
    public $aError = array();
    public $pathWsdl = '';
   
    private $certKEY;
    private $pubKEY;
    private $priKEY;
    
    private $proxyIP = '';
    private $proxyPORT = '';
    private $proxyUSER = '';
    private $proxyPASS = '';
    
    /**
     * 
     * @param type $publicKey
     * @param type $privateKey
     * @param type $certificateKey
     * @param type $pathWsdl
     * @param type $timeout
     * @return boolean
     * @throws \Common\Exception\NfephpException
     * @throws NfephpException
     */
    public function __construct($publicKey = '', $privateKey = '', $certificateKey = '', $pathWsdl = '', $timeout = 10)
    {
        try {
            if ($certificateKey == '' || $privateKey = '' || $publicKey == '') {
                $msg = 'O path para as chaves deve ser passado na instânciação da classe.';
                throw new NfephpException($msg);
            }
            if ($pathWsdl == '') {
                $msg = 'O path para os arquivos WSDL deve ser passado na instânciação da classe.';
                throw new NfephpException($msg);
            }
            $this->pubKEY = $publicKey;
            $this->priKEY = $privateKey;
            $this->certKEY = $certificateKey;
            $this->pathWsdl = $pathWsdl;
            $this->soapTimeout = $timeout;
            
        } catch (NfephpException $e) {
            $this->aError[] = $e->getMessage();
            throw $e;
            return false;
        }
    }//fim __construct
    
    /**
     * send
     * Estabelece comunicaçao com servidor SOAP 1.1 ou 1.2 da SEFAZ,
     * usando as chaves publica e privada parametrizadas na contrução da classe.
     * Conforme Manual de Integração Versão 4.0.1 
     *
     * @name send
     * @param string $urlsefaz
     * @param string $namespace
     * @param string $cabecalho
     * @param string $dados
     * @param string $metodo
     * @param numeric $ambiente  tipo de ambiente 1 - produção e 2 - homologação
     * @param string $UF unidade da federação, necessário para diferenciar AM, MT e PR
     * @return mixed false se houve falha ou o retorno em xml do SEFAZ
     */
    public function send(
        $UF = '',
        $SVAN = false,
        $SCAN = false,
        $namespace = '',
        $cabecalho = '',
        $dados = '',
        $metodo = '',
        $ambiente = '2'
    ) {
        try {
            if (!class_exists("SoapClient")) {
                $msg = "A classe SOAP não está disponível no PHP, veja a configuração.";
                throw new NfephpException($msg);
            }
            //ativa retorno de erros soap
            use_soap_error_handler(true);
            //versão do SOAP
            $soapver = SOAP_1_2;
            if ($ambiente == 1) {
                $ambiente = 'producao';
            } else {
                $ambiente = 'homologacao';
            }
            $usef = "_$metodo.asmx";
            $urlsefaz = "$this->pathWsdl/$ambiente/$UF$usef";
            if ($this->enableSVAN) {
                //se for SVAN montar o URL baseado no metodo e ambiente
                $urlsefaz = "$this->pathWsdl/$ambiente/SVAN$usef";
            }
            //verificar se SCAN ou SVAN
            if ($this->enableSCAN) {
                //se for SCAN montar o URL baseado no metodo e ambiente
                $urlsefaz = "$this->pathWsdl/$ambiente/SCAN$usef";
            }
            if ($this->soapTimeout == 0) {
                $tout = 999999;
            } else {
                $tout = $this->soapTimeout;
            }
            //completa a url do serviço para baixar o arquivo WSDL
            $URL = $urlsefaz.'?WSDL';
            $this->soapDebug = $urlsefaz;
            $options = array(
                'encoding'      => 'UTF-8',
                'verifypeer'    => false,
                'verifyhost'    => true,
                'soap_version'  => $soapver,
                'style'         => SOAP_DOCUMENT,
                'use'           => SOAP_LITERAL,
                'local_cert'    => $this->certKEY,
                'trace'         => true,
                'compression'   => 0,
                'exceptions'    => true,
                'connection_timeout' => $tout,
                'cache_wsdl'    => WSDL_CACHE_NONE
            );
            //instancia a classe soap
            $oSoapClient = new CorrectedSoapClient($URL, $options);
            //monta o cabeçalho da mensagem
            $varCabec = new SoapVar($cabecalho, XSD_ANYXML);
            $header = new SoapHeader($namespace, 'nfeCabecMsg', $varCabec);
            //instancia o cabeçalho
            $oSoapClient->setSoapHeaders($header);
            //monta o corpo da mensagem soap
            $varBody = new SoapVar($dados, XSD_ANYXML);
            //faz a chamada ao metodo do webservices
            $resp = $oSoapClient->soapCall($metodo, array($varBody));
            if (is_soap_fault($resp)) {
                $soapFault = "SOAP Fault: (faultcode: {$resp->faultcode}, faultstring: {$resp->faultstring})";
            }
            $resposta = $oSoapClient->getLastResponse();
            $this->soapDebug .= "\n" . $soapFault;
            $this->soapDebug .= "\n" . $oSoapClient->getLastRequestHeaders();
            $this->soapDebug .= "\n" . $oSoapClient->getLastRequest();
            $this->soapDebug .= "\n" . $oSoapClient->getLastResponseHeaders();
            $this->soapDebug .= "\n" . $oSoapClient->getLastResponse();
        } catch (NfephpException $e) {
            //$this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return $resposta;
    } //fim nfeSOAP
}//fim da classe NatSoap
