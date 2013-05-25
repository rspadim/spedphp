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

use Common\Exception\NfephpException;

class CurlSoap
{
    public $soapDebug = '';
    public $soapTimeout = 10;
    public $soapDebug = '';
    public $aError = array();
    private $pubKEY;
    private $priKEY;
    private $proxyIP = '';
    private $proxyPORT = '';
    private $proxyUSER = '';
    private $proxyPASS = '';
    
    public function __construct($privateKey, $publicKey, $timeout = 10)
    {
        $this->priKEY = $privateKey;
        $this->pubKEY = $publicKey;
        $this->soapTimeout = $timeout;
    }//fim __construct
    
    /**
     * setProxy
     * @param type $ipNumber
     * @param type $port
     * @param type $user
     * @param type $pass
     * @return boolean
     * @throws \Common\Exception\NfephpException
     * @throws NfephpException
     */
    public function setProxy($ipNumber, $port, $user = '', $pass = '')
    {
        $this->proxyIP = $ipNumber;
        $this->proxyPORT = $port;
        $this->proxyUSER = $user;
        $this->proxyPASS = $pass;
    }//fim setProxy
    
    /**
     * send
     * Função alternativa para estabelecer comunicaçao com servidor SOAP 1.2 da SEFAZ,
     * usando as chaves publica e privada parametrizadas na contrução da classe.
     * Conforme Manual de Integração Versão 4.0.1 Utilizando cURL e não o SOAP nativo
     *
     * @name send
     * @param type $urlsefaz
     * @param type $namespace
     * @param type $cabecalho
     * @param type $dados
     * @param type $metodo
     * @param type $ambiente
     * @return boolean|string
     * @throws \Common\Exception\NfephpException
     * @throws NfephpException
     */
    public function send($urlsefaz = '', $namespace = '', $cabecalho = '', $dados = '', $metodo = '')
    {
        try {
            if ($urlsefaz == '') {
                $msg = "URL do webservice não disponível no arquivo xml das URLs da SEFAZ.";
                throw new NfephpException($msg);
            }
            $data = ''.'<?xml version="1.0" encoding="utf-8"?>'.'<soap12:Envelope ';
            $data .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
            $data .= 'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ';
            $data .= 'xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">';
            $data .= '<soap12:Header>'.$cabecalho.'</soap12:Header>';
            $data .= '<soap12:Body>'.$dados.'</soap12:Body>';
            $data .= '</soap12:Envelope>';
     
            //[Informational 1xx]
            $cCode['100']="Continue";
            $cCode['101']="Switching Protocols";
            //[Successful 2xx]
            $cCode['200']="OK";
            $cCode['201']="Created";
            $cCode['202']="Accepted";
            $cCode['203']="Non-Authoritative Information";
            $cCode['204']="No Content";
            $cCode['205']="Reset Content";
            $cCode['206']="Partial Content";
            //[Redirection 3xx]
            $cCode['300']="Multiple Choices";
            $cCode['301']="Moved Permanently";
            $cCode['302']="Found";
            $cCode['303']="See Other";
            $cCode['304']="Not Modified";
            $cCode['305']="Use Proxy";
            $cCode['306']="(Unused)";
            $cCode['307']="Temporary Redirect";
            //[Client Error 4xx]
            $cCode['400']="Bad Request";
            $cCode['401']="Unauthorized";
            $cCode['402']="Payment Required";
            $cCode['403']="Forbidden";
            $cCode['404']="Not Found";
            $cCode['405']="Method Not Allowed";
            $cCode['406']="Not Acceptable";
            $cCode['407']="Proxy Authentication Required";
            $cCode['408']="Request Timeout";
            $cCode['409']="Conflict";
            $cCode['410']="Gone";
            $cCode['411']="Length Required";
            $cCode['412']="Precondition Failed";
            $cCode['413']="Request Entity Too Large";
            $cCode['414']="Request-URI Too Long";
            $cCode['415']="Unsupported Media Type";
            $cCode['416']="Requested Range Not Satisfiable";
            $cCode['417']="Expectation Failed";
            //[Server Error 5xx]
            $cCode['500']="Internal Server Error";
            $cCode['501']="Not Implemented";
            $cCode['502']="Bad Gateway";
            $cCode['503']="Service Unavailable";
            $cCode['504']="Gateway Timeout";
            $cCode['505']="HTTP Version Not Supported";
            
            //tamanho da mensagem
            $tamanho = strlen($data);
            //estabelecimento dos parametros da mensagem
            $parametros = array(
                'Content-Type: application/soap+xml;charset=utf-8;action="'.$namespace."/".$metodo.'"',
                'SOAPAction: "'.$metodo.'"',
                "Content-length: $tamanho");
            //incializa cURL
            $oCurl = curl_init();
            //setting da seção soap
            if ($this->proxyIP != '') {
                curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, 1);
                curl_setopt($oCurl, CURLOPT_PROXYTYPE, "CURLPROXY_HTTP");
                curl_setopt($oCurl, CURLOPT_PROXY, $this->proxyIP.':'.$this->proxyPORT);
                if ($this->proxyPASS != '') {
                    curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->proxyUSER.':'.$this->proxyPASS);
                    curl_setopt($oCurl, CURLOPT_PROXYAUTH, "CURLAUTH_BASIC");
                } //fim if senha proxy
            }//fim if aProxy
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soapTimeout);
            curl_setopt($oCurl, CURLOPT_URL, $urlsefaz.'');
            curl_setopt($oCurl, CURLOPT_PORT, 443);
            curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
            curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabeçalho de resposta
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 3);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2); // verifica o host evita MITM
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($oCurl, CURLOPT_SSLCERT, $this->pubKEY);
            curl_setopt($oCurl, CURLOPT_SSLKEY, $this->priKEY);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $parametros);
            //inicia a conexão
            $xml = curl_exec($oCurl);
            //obtem as informações da conexão
            $info = curl_getinfo($oCurl);
            //coloca as informações em uma variável
            $txtInfo ="";
            $txtInfo .= "URL=$info[url]\n";
            $txtInfo .= "Content type=$info[content_type]\n";
            $txtInfo .= "Http Code=$info[http_code]\n";
            $txtInfo .= "Header Size=$info[header_size]\n";
            $txtInfo .= "Request Size=$info[request_size]\n";
            $txtInfo .= "Filetime=$info[filetime]\n";
            $txtInfo .= "SSL Verify Result=$info[ssl_verify_result]\n";
            $txtInfo .= "Redirect Count=$info[redirect_count]\n";
            $txtInfo .= "Total Time=$info[total_time]\n";
            $txtInfo .= "Namelookup=$info[namelookup_time]\n";
            $txtInfo .= "Connect Time=$info[connect_time]\n";
            $txtInfo .= "Pretransfer Time=$info[pretransfer_time]\n";
            $txtInfo .= "Size Upload=$info[size_upload]\n";
            $txtInfo .= "Size Download=$info[size_download]\n";
            $txtInfo .= "Speed Download=$info[speed_download]\n";
            $txtInfo .= "Speed Upload=$info[speed_upload]\n";
            $txtInfo .= "Download Content Length=$info[download_content_length]\n";
            $txtInfo .= "Upload Content Length=$info[upload_content_length]\n";
            $txtInfo .= "Start Transfer Time=$info[starttransfer_time]\n";
            $txtInfo .= "Redirect Time=$info[redirect_time]\n";
            $txtInfo .= "Certinfo=$info[certinfo]\n";
            //obtem o tamanho do xml
            $num = strlen($xml);
            //localiza a primeira marca de tag
            $xPos = stripos($xml, "<");
            //se não exixtir não é um xml
            if ($xPos !== false) {
                $xml = substr($xml, $xPos, $num-$xPos);
            } else {
                $xml = '';
            }
            //carrega a variavel debug
            $this->soapDebug = $data."\n\n".$txtInfo."\n".$xml;
            //testa se um xml foi retornado
            if ($xml === false || $xPos === false) {
                //não houve retorno
                $msg = curl_error($oCurl) . $info['http_code'] . $cCode[$info['http_code']];
                throw new NfephpException($msg);
            } else {
                //houve retorno mas ainda pode ser uma mensagem de erro do webservice
                if ($info['http_code'] > 200) {
                    $msg = $info['http_code'] . $cCode[$info['http_code']];
                    throw new NfephpException($msg);
                }
            }
            
        } catch (NfephpException $e) {
            $this->aError[] = $e->getMessage();
            throw $e;
            curl_close($oCurl);
            return false;
        }
        curl_close($oCurl);
        return $xml;
    } //fim curlSOAP
}//fim da classe CurlSoap
