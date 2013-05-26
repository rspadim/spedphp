<?php

/**
 * Spedphp (http://www.nfephp.org/)
 *
 * @link      http://github.com/nfephp-org/spedphp for the canonical source repository
 * @copyright Copyright (c) 2008-2013 NFePHP (http://www.nfephp.org)
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @package   Spedphp
 */

namespace Spedphp\Common\Pkcs12;

use Spedphp\Common\Pkcs12\Asn;

class Pkcs12Certs
{
    //propriedades da clase
    public $certsDir;
    public $pfxName;
    public $cnpj;
    public $pubKey;
    public $priKey;
    public $certKey;
    public $pubKeyFile;
    public $priKeyFile;
    public $certKeyFile;
    public $expireTimestamp;
    protected $keyPass;
    public $error='';

    //constantes utilizadas na assinatura digital do xml
    const URLDSIG = 'http://www.w3.org/2000/09/xmldsig#';
    const URLCANONMETH = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    const URLSIGMETH = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    const URLTRANSFMETH1 ='http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    const URLTRANSFMETH2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    const URLDIGESTMETH = 'http://www.w3.org/2000/09/xmldsig#sha1';
    
    public function __construct($dir, $pass, $cnpj)
    {
        //limpar bobagens
        $this->certsDir = trim($dir);
        $this->keyPass = trim($pass);
        $this->cnpj = trim($cnpj);
        $this->init();
    }//fim __construct
    
    private function init()
    {
        if (!is_dir($this->certsDir)) {
            $this->error = "O caminho para os arquivos dos certificados deve ser passado!!";
            return false;
        }
        if (substr($this->certsDir, -1) !== DIRECTORY_SEPARATOR) {
            $this->certsDir .= DIRECTORY_SEPARATOR;
        }
        //monta o path completo com o nome da chave privada
        $this->priKeyFile = $this->certsDir.$this->cnpj.'_priKEY.pem';
        //monta o path completo com o nome da chave publica
        $this->pubKeyFile =  $this->certsDir.$this->cnpj.'_pubKEY.pem';
        //monta o path completo com o nome do certificado (chave publica e privada) em formato pem
        $this->certKeyFile = $this->certsDir.$this->cnpj.'_certKEY.pem';
        //verifica se os certificados existem
        if (is_file($this->priKeyFile) && is_file($this->pubKeyFile) && is_file($this->certKeyFile)) {
            //se as chaves existem deve ser verificado sua validade
            $this->pubKey = file_get_contents($this->pubKeyFile);
            $this->priKey = file_get_contents($this->priKeyFile);
            $this->certKey = file_get_contents($this->certKeyFile);
            if (!openssl_x509_read($this->pubKey)) {
                //arquivo não pode ser lido como um certificado então deletar
                $this->removePemFiles();
                $this->leaveParam();
                $this->error = "Certificado não instalado. Instale um novo certificado pfx!!";
                return false;
            } else {
                //já que o certificado existe, verificar seu prazo de validade
                return $this->validCerts($this->pubKey);
            }
        }
        return true;
    }//fim init
    
    public function loadNewCert($pfxName)
    {
        //monta o caminho completo até o certificado pfx
        $pfxCert = $this->certsDir.$pfxName;
        if (!is_file($pfxCert)) {
            $this->error = "Arquivo não localizado! [$pfxCert]";
        }
        //carrega o certificado em um string
        $pfxContent = file_get_contents($pfxCert);
        //carrega os certificados e chaves para um array denominado $x509certdata
        if (!openssl_pkcs12_read($pfxContent, $x509certdata, $this->keyPass)) {
            $this->error = "O certificado não pode ser lido!! 
                Senha errada ou arquivo corrompido ou formato inválido!!";
            return false;
        }
        //verifica sua data de validade
        if (!$this->validCerts($x509certdata['cert'])) {
            return false;
        }
        $cnpjCert = Asn::getCNPJCert($x509certdata['cert']);
        if ($this->cnpj != $cnpjCert) {
            $this->error = "O Certificado informado pertence a outro CNPJ!!";
            return false;
        }
        //monta o path completo com o nome da chave privada
        $this->priKeyFile = $this->certsDir.$this->cnpj.'_priKEY.pem';
        //monta o path completo com o nome da chave publica
        $this->pubKeyFile =  $this->certsDir.$this->cnpj.'_pubKEY.pem';
        //monta o path completo com o nome do certificado (chave publica e privada) em formato pem
        $this->certKeyFile = $this->certsDir.$this->cnpj.'_certKEY.pem';
        $this->removePemFiles();
        //recriar os arquivos pem com o arquivo pfx
        file_put_contents($this->priKeyFile, $x509certdata['pkey']);
        file_put_contents($this->pubKeyFile, $x509certdata['cert']);
        file_put_contents($this->certKeyFile, $x509certdata['pkey']."\r\n".$x509certdata['cert']);
        $this->pubKey=$x509certdata['cert'];
        $this->priKey=$x509certdata['pkey'];
        $this->certKey=$x509certdata['pkey']."\r\n".$x509certdata['cert'];
        return true;
    } //fim loadCerts

    public function signXML($docxml, $tagid)
    {
        if (is_file($docxml)) {
            $xml = file_get_contents($docxml);
        } else {
            $xml = $docxml;
        }
        $fileIndex = fopen($this->priKEY, "r");
        $privKey = fread($fileIndex, 8192);
        fclose($fileIndex);
        $pkeyid = openssl_get_privatekey($privKey);
        // limpeza do xml com a retirada dos CR, LF e TAB
        $order = array("\r\n", "\n", "\r", "\t");
        $replace = '';
        $xml = str_replace($order, $replace, $xml);
        libxml_use_internal_errors(true); // Habilita a manipulaçao de erros da libxml
        libxml_clear_errors(); //limpar erros anteriores que possam estar em memória
        $xmldoc = new \DOMDocument('1.0', 'utf-8');// carrega o documento no DOM
        $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
        $xmldoc->formatOutput = false;
        if ($xmldoc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
            $root = $xmldoc->documentElement;
        } else {
            $msg = "Erro ao carregar XML, provavel erro na passagem do parâmetro docxml ou no próprio xml!!";
            $errors = libxml_get_errors();
            if (!empty($errors)) {
                $eIndex = 1;
                foreach ($errors as $error) {
                    $msg .= "\n  [$eIndex]-" . trim($error->message);
                    $eIndex++;
                }
                libxml_clear_errors();
            }
            throw new NfephpException($msg);
        }
        $node = $xmldoc->getElementsByTagName($tagid)->item(0);//extrair a tag com os dados a serem assinados
        if (!isset($node)) {
            $msg = "A tag < $tagid > não existe no XML!!";
            throw new NfephpException($msg);
        }
        $idNfe = trim($node->getAttribute("Id"));
        $dados = $node->C14N(false, false, null, null);//extrai os dados da tag para uma string
        $hashValue = hash('sha1', $dados, true);//calcular o hash dos dados
        $digValue = base64_encode($hashValue);
        $signatureNode = $xmldoc->createElementNS(URLDSIG, 'Signature');
        $root->appendChild($signatureNode);
        $signedInfoNode = $xmldoc->createElement('SignedInfo');
        $signatureNode->appendChild($signedInfoNode);
        $newNode = $xmldoc->createElement('CanonicalizationMethod');
        $signedInfoNode->appendChild($newNode);
        $newNode->setAttribute('Algorithm', URLCANONMETH);
        $newNode = $xmldoc->createElement('SignatureMethod');
        $signedInfoNode->appendChild($newNode);
        $newNode->setAttribute('Algorithm', URLSIGMETH);
        $referenceNode = $xmldoc->createElement('Reference');
        $signedInfoNode->appendChild($referenceNode);
        $referenceNode->setAttribute('URI', '#'.$idNfe);
        $transformsNode = $xmldoc->createElement('Transforms');
        $referenceNode->appendChild($transformsNode);
        $newNode = $xmldoc->createElement('Transform');
        $transformsNode->appendChild($newNode);
        $newNode->setAttribute('Algorithm', URLTRANSFMETH1);
        $newNode = $xmldoc->createElement('Transform');
        $transformsNode->appendChild($newNode);
        $newNode->setAttribute('Algorithm', URLTRANSFMETH2);
        $newNode = $xmldoc->createElement('DigestMethod');
        $referenceNode->appendChild($newNode);
        $newNode->setAttribute('Algorithm', URLDIGESTMETH);
        $newNode = $xmldoc->createElement('DigestValue', $digValue);
        $referenceNode->appendChild($newNode);
        // extrai os dados a serem assinados para uma string
        $dados = $signedInfoNode->C14N(false, false, null, null);
        $signature = '';
        openssl_sign($dados, $signature, $pkeyid);
        $signatureValue = base64_encode($signature);
        $newNode = $xmldoc->createElement('SignatureValue', $signatureValue);
        $signatureNode->appendChild($newNode);
        $keyInfoNode = $xmldoc->createElement('KeyInfo');
        $signatureNode->appendChild($keyInfoNode);
        $x509DataNode = $xmldoc->createElement('X509Data');
        $keyInfoNode->appendChild($x509DataNode);
        $cert = $this->cleanCerts($this->pubKEY);
        $newNode = $xmldoc->createElement('X509Certificate', $cert);
        $x509DataNode->appendChild($newNode);
        $xml = $xmldoc->saveXML();
        openssl_free_key($pkeyid);
        //retorna o documento assinado
        return $xml;
    } //fim signXML
    
        
    public function verifySignature($xml = '', $tag = '', &$err = '')
    {
        if ($xml=='') {
            $msg = "O parâmetro xml está vazio.";
            $err = $msg;
            return false;
        }
        if ($tag=='') {
            $msg = "O parâmetro tag está vazio.";
            $err = $msg;
            return false;
        }
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        if (!is_file($xml)) {
            $dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        } else {
            $dom->load($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        }
        $errors = libxml_get_errors();
        if (!empty($errors)) {
            $msg = "O arquivo informado não é um xml.";
            $err = $msg;
            return false;
        }
        $tagBase = $dom->getElementsByTagName($tag)->item(0);
        // validar digest value
        $tagInf = $tagBase->C14N(false, false, null, null);
        $hashValue = hash('sha1', $tagInf, true);
        $digestCalculado = base64_encode($hashValue);
        $digestInformado = $dom->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        if ($digestCalculado != $digestInformado) {
            $msg = "O conteúdo do XML não confere com o Digest Value.\nDigest calculado [{$digestCalculado}], informado no XML [{$digestInformado}].\nO arquivo pode estar corrompido ou ter sido adulterado.";
            $err = $msg;
            return false;
        }
        // Remontando o certificado
        $x509Certificate = $dom->getElementsByTagName('X509Certificate')->item(0)->nodeValue;
        $x509Certificate =  "-----BEGIN CERTIFICATE-----\n".
        $this->splitLines($x509Certificate)."\n-----END CERTIFICATE-----\n";
        $pubKey = openssl_pkey_get_public($x509Certificate);
        if ($pubKey === false) {
            $msg = "Ocorreram problemas ao remontar a chave pública. Certificado incorreto ou corrompido!!";
            $err = $msg;
            return false;
        }
        // remontando conteudo que foi assinado
        $signContent = $dom->getElementsByTagName('SignedInfo')->item(0)->C14N(false, false, null, null);
        // validando assinatura do conteudo
        $signContentXML = $dom->getElementsByTagName('SignatureValue')->item(0)->nodeValue;
        $signContentXML = base64_decode(str_replace(array("\r", "\n"), '', $signContentXML));
        $resp = openssl_verify($signContent, $signContentXML, $pubKey);
        if ($resp != 1) {
            $msg = "Problema ({$resp}) ao verificar a assinatura do digital!!";
            $err = $msg;
            return false;
        }
        return true;
    } // fim verifySignatureXML
    
    
    protected function validCerts($cert)
    {
        $data = openssl_x509_read($cert);
        $certData = openssl_x509_parse($data);
        // reformata a data de validade;
        $ano = substr($certData['validTo'], 0, 2);
        $mes = substr($certData['validTo'], 2, 2);
        $dia = substr($certData['validTo'], 4, 2);
        //obtem o timestamp da data de validade do certificado
        $dValid = gmmktime(0, 0, 0, $mes, $dia, $ano);
        // obtem o timestamp da data de hoje
        $dHoje = gmmktime(0, 0, 0, date("m"), date("d"), date("Y"));
        // compara a data de validade com a data atual
        $this->expireTimestamp = $dValid;
        if ($dHoje > $dValid) {
            $this->error = "Data de validade vencida! [Valido até $dia/$mes/$ano]";
            $this->removePemFiles();
            $this->leaveParam();
            return false;
        }
        return true;
    } //fim validCerts
    
    protected function cleanCerts($certFile)
    {
        //inicializa variavel
        $data = '';
        //carregar a chave publica do arquivo pem
        $pubKey = file_get_contents($certFile);
        //carrega o certificado em um array usando o LF como referencia
        $arCert = explode("\n", $pubKey);
        foreach ($arCert as $curData) {
            //remove a tag de inicio e fim do certificado
            if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 &&
                    strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
                //carrega o resultado numa string
                $data .= trim($curData);
            }
        }
        return $data;
    }//fim cleanCerts
    
    private function removePemFiles()
    {
        if (is_file($this->pubKeyFile)) {
            unlink($this->pubKeyFile);
        }
        if (is_file($this->priKeyFile)) {
            unlink($this->priKeyFile);
        }
        if (is_file($this->certKeyFile)) {
            unlink($this->certKeyFile);
        }
    }//fim removePemFiles
    
    private function leaveParam()
    {
        $this->pfxName='';
        $this->pubKey='';
        $this->priKey='';
        $this->certKey='';
        $this->pubKeyFile='';
        $this->priKeyFile='';
        $this->certKeyFile='';
        $this->expireTimestamp='';
    }// fim leaveParam

    /**
     * splitLines
     * Divide a string do certificado publico em linhas com 76 caracteres (padrão original)
     * 
     * @name splitLines
     * @param string $cnt certificado
     * @return string certificado reformatado 
     */
    public static function splitLines($cnt = '')
    {
        if ($cnt != '') {
            $cnt = rtrim(chunk_split(str_replace(array("\r", "\n"), '', $cnt), 76, "\n"));
        }
        return $cnt;
    }//fim splitLines
}//fim da classe
