<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace library\Soap;

use library\Soap\CorrectedSoapClient;
use library\Exception\NfephpException;
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
     * @throws \library\Exception\NfephpException
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
    public function send($UF = '', $SVAN = false, $SCAN = false, $namespace = '', $cabecalho = '', $dados = '', $metodo = '', $ambiente = '2' )
    {
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
    
    /**
     * 
     * @param type $ufsigla
     * @param type $wsfile
     */
    public function downloadWsdl($ufsigla = '', $wsfile = '')
    {
        if ($wsfile == ''){
            
        }
        
        if ($ufsigla != '') {
            
        }
        $pubKey = $this->pubKEY;
        $priKey = $this->priKEY;
        
        //carrega o conteudo da lista de webservices
        $xml = file_get_contents($wsfile);
        //converte o xml em array
        //$xml2a = new XML2Array();
        
        $ws = XML2Array::createArray($xml);
        
        //para cada UF
        foreach($ws['WS']['UF'] as $uf){
            $sigla = $uf['sigla'];
            if ($ufsigla != '') {
                if ($sigla == $ufsigla) {
                    $ambiente = array('homologacao','producao');
                    //para cada ambiente
                    foreach($ambiente as $amb){
                        $h = $uf[$amb];
                        if (isset($h)){
                            foreach($h as $k => $j){
                                $nome = $k;
                                $url=$j['@value'];
                                $metodo=$j['@attributes']['method'];
                                $versao = $j['@attributes']['version'];
                                if ($url != ''){
                                    $aS[] = $sigla;
                                    $aA[] = $amb;
                                    $aN[] = $nome;
                                    $aU[] = $url.'?wsdl';
                                    $aM[] = $metodo;
                                    $aV[] = $versao;
                                }    
                            }
                        }
                    }
                }
            } else {
                $ambiente = array('homologacao','producao');
                //para cada ambiente
                foreach($ambiente as $amb){
                    $h = $uf[$amb];
                    if (isset($h)){
                        foreach($h as $k => $j){
                            $nome = $k;
                            $url=$j['@value'];
                            $metodo=$j['@attributes']['method'];
                            $versao = $j['@attributes']['version'];
                            if ($url != ''){
                                $aS[] = $sigla;
                                $aA[] = $amb;
                                $aN[] = $nome;
                                $aU[] = $url.'?wsdl';
                                $aM[] = $metodo;
                                $aV[] = $versao;
                            }    
                        }
                    }
                }
            }    
        }//fim foreach
        
        //inicia o loop para baixar os arquivos wsdl
        $i = 0;
        foreach($aS as $s){
            $urlsefaz = $aU[$i];
            if (!is_dir($this->pathWsdl.$aA[$i])) {
                mkdir($this->pathWsdl.$aA[$i], 0777);
            }
            $fileName = $this->pathWsdl.$aA[$i].DIRECTORY_SEPARATOR.$aS[$i].'_'.$aM[$i].'.asmx';
             
            //inicia comunicação com curl
            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($oCurl, CURLOPT_URL, $urlsefaz.'');
            curl_setopt($oCurl, CURLOPT_PORT , 443);
            curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
            curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabeçalho de resposta
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 3);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($oCurl, CURLOPT_SSLCERT, $pubKey);
            curl_setopt($oCurl, CURLOPT_SSLKEY, $priKey);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            $__xml = curl_exec($oCurl);
            $info = curl_getinfo($oCurl);
            curl_close($oCurl);
            //verifica se foi retornado o wsdl
            $n = strpos($__xml,'<wsdl:def');
            if ($n === false){
                //não retornou um wsdl
            } else {
                $wsdl = trim(substr($__xml, $n));
                $wsdl = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".$wsdl;
                if (is_file($fileName)) {
                    unlink($fileName);
                }
                file_put_contents($fileName,$wsdl);
                chmod($fileName, 777);
            }    
            $i++;
        } //fim do processo    
    }//fim downloadWsdl        
    
}//fim da classe NatSoap
