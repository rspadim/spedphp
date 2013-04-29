<?php

/*
 * NFePHP (http://www.nfephp.org/)
 *
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 * @copyright Copyright (c) 2008-2013 NFePHP (http://www.nfephp.org)
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @package   NFePHP
 */

namespace library\Pkcs12;

use library\Exception\NfephpException;

class Pkcs12Certs
{
    
    public $certsDir;
    public $pfxName;
    public $keyPass;
    public $cnpj;
    public $pubKEY;
    public $priKEY;
    public $certKEY;
    public $certMonthsToExpire;
    public $certDaysToExpire;
    public $pfxTimestamp;

    //constantes utilizadas na assinatura digital do xml
    const URLDSIG = 'http://www.w3.org/2000/09/xmldsig#';
    const URLCANONMETH = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    const URLSIGMETH = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    const URLTRANSFMETH1 ='http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    const URLTRANSFMETH2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    const URLDIGESTMETH = 'http://www.w3.org/2000/09/xmldsig#sha1';
    
    /**
     * __contruct 
     * 
     * @param string $certsDir  Diretorio onde os certificados serão armazenados
     * @param string $pfxName   Nome do arquivo pfrx colocado no diretório acima indicado
     * @param string $keyPass   Senha para acesso aos dados do certificado digital
     * @param string $cnpj      CNPJ do proprietário do certificado
     * @return boolean          True em caso de sucesso ou false em caso de erro  
     * @throws \library\Exception\NfephpException
     * @throws NfephpException
     */
    public function __construct($certsDir = '', $pfxName = '', $keyPass = '', $cnpj = '')
    {
        try {
            if ($certsDir == '') {
                $msg = "O caminho para os arquivos dos certificados deve ser passado!!";
                throw new NfephpException($msg);
            }
            if ($pfxName == '') {
                $msg = "O nome do certificado .pfx deve ser passado!!";
                throw new NfephpException($msg);
            }
            if ($keyPass == '') {
                $msg = "A senha do certificado .pfx deve ser passado!!";
                throw new NfephpException($msg);
            }
            if ($cnpj == '') {
                $msg = "O numero do CNPJ do certificado deve ser passado!!";
                throw new NfephpException($msg);
            }
            //limpar bobagens
            $certsDir = trim($certsDir);
            $pfxName = trim($pfxName);
            $keyPass = trim($keyPass);
            $cnpj = trim($cnpj);
            
            if (substr($certsDir, -1) !== DIRECTORY_SEPARATOR) {
                $certsDir .= DIRECTORY_SEPARATOR;
            }
            if (!file_exists($certsDir . $pfxName)){
                $msg = "O arquivo do certificado pfx não foi encontrado!!";
                throw new NfephpException($msg);
            }
            $this->certsDir = $certsDir;
            $this->pfxName = $pfxName;
            $this->keyPass = $keyPass;
            $this->cnpj = $cnpj;
        } catch (NfephpException $e) {
            throw $e;
            return false;
        }
        return true;
    }//fim __construct
    
    /**
     * loadCerts
     * 
     * Carrega o certificado pfx e gera as chaves privada e publica no
     * formato pem para a assinatura e para uso do SOAP e registra as
     * variaveis de ambiente.
     * Esta função deve ser invocada antes das outras do sistema que
     * dependam do certificado.
     * Além disso esta função também avalia a validade do certificado.
     * Os certificados padrão A1 (que são usados pelo sistema) tem validade
     * limitada à 1 ano e caso esteja vencido a função retornará false.
     *
     * Resultado
     *  A função irá criar o certificado digital (chaves publicas e privadas)
     *  no formato pem e grava-los no diretorio indicado em $this->certsDir
     *  com os nomes :
     *     CNPJ_priKEY.pem
     *     CNPJ_pubKEY.pem
     *     CNPJ_certKEY.pem
     *  Estes arquivos também serão carregados nas variáveis da classe
     *  $this->priKEY (com o caminho completo para o arquivo CNPJ_priKEY.pem)
     *  $this->pubKEY (com o caminho completo para o arquivo CNPJ_pubKEY.pem)
     *  $this->certKEY (com o caminho completo para o arquivo CNPJ_certKEY.pem)
     *
     * @name loadCerts
     * @param	boolean $testaVal True testa a validade do certificado ou false não testa
     * @return	boolean true se o certificado foi carregado e false se não
     */
    public function loadCerts($testaVal = true)
    {
        try {
            if (!function_exists('openssl_pkcs12_read')) {
                $msg = "Função não existente: openssl_pkcs12_read!!";
                throw new NfephpException($msg);
            }
            //monta o path completo com o nome da chave privada
            $this->priKEY = $this->certsDir.$this->cnpj.'_priKEY.pem';
            //monta o path completo com o nome da chave prublica
            $this->pubKEY =  $this->certsDir.$this->cnpj.'_pubKEY.pem';
            //monta o path completo com o nome do certificado (chave publica e privada) em formato pem
            $this->certKEY = $this->certsDir.$this->cnpj.'_certKEY.pem';
            //verificar se o nome do certificado e
            //o path foram carregados nas variaveis da classe
            if ($this->certsDir == '' || $this->pfxName == '') {
                $msg = "Um certificado deve ser passado para a classe pelo arquivo de configuração!! ";
                throw new NfephpException($msg);
            }
            //monta o caminho completo até o certificado pfx
            $pfxCert = $this->certsDir.$this->pfxName;
            //verifica se o arquivo existe
            if (!file_exists($pfxCert)) {
                $msg = "Certificado não encontrado!! $pfxCert";
                throw new NfephpException($msg);
            }
            //carrega o certificado em um string
            $pfxContent = file_get_contents($pfxCert);
            //carrega os certificados e chaves para um array denominado $x509certdata
            if (!openssl_pkcs12_read($pfxContent, $x509certdata, $this->keyPass)) {
                $msg = "O certificado não pode ser lido!! Provavelmente corrompido ou com formato inválido!!";
                throw new NfephpException($msg);
            }
            if ($testaVal) {
                //verifica sua validade
                if (!$aResp = $this->validCerts($x509certdata['cert'])) {
                    $msg = "Certificado invalido!! - " . $aResp['error'];
                    throw new NfephpException($msg);
                }
            }
            //aqui verifica se existem as chaves em formato PEM
            //se existirem pega a data da validade dos arquivos PEM
            //e compara com a data de validade do PFX
            //caso a data de validade do PFX for maior que a data do PEM
            //deleta dos arquivos PEM, recria e prossegue
            $flagNovo = false;
            if (file_exists($this->pubKEY)) {
                $cert = file_get_contents($this->pubKEY);
                if (!$data = openssl_x509_read($cert)) {
                    //arquivo não pode ser lido como um certificado
                    //então deletar
                    $flagNovo = true;
                } else {
                    //pegar a data de validade do mesmo
                    $cert_data = openssl_x509_parse($data);
                    // reformata a data de validade;
                    $ano = substr($cert_data['validTo'], 0, 2);
                    $mes = substr($cert_data['validTo'], 2, 2);
                    $dia = substr($cert_data['validTo'], 4, 2);
                    //obtem o timeestamp da data de validade do certificado
                    $dValPubKey = gmmktime(0, 0, 0, $mes, $dia, $ano);
                    //compara esse timestamp com o do pfx que foi carregado
                    if ($dValPubKey < $this->pfxTimestamp) {
                        //o arquivo PEM é de um certificado anterior
                        //então apagar os arquivos PEM
                        $flagNovo = true;
                    }//fim teste timestamp
                }//fim read pubkey
            } else {
                //arquivo não localizado
                $flagNovo = true;
            }//fim if file pubkey
            //verificar a chave privada em PEM
            if (!file_exists($this->priKEY)) {
                //arquivo não encontrado
                $flagNovo = true;
            }
            //verificar o certificado em PEM
            if (!file_exists($this->certKEY)) {
                //arquivo não encontrado
                $flagNovo = true;
            }
            //criar novos arquivos PEM
            if ($flagNovo) {
                if (file_exists($this->pubKEY)) {
                    unlink($this->pubKEY);
                }
                if (file_exists($this->priKEY)) {
                    unlink($this->priKEY);
                }
                if (file_exists($this->certKEY)) {
                    unlink($this->certKEY);
                }
                //recriar os arquivos pem com o arquivo pfx
                if (!file_put_contents($this->priKEY, $x509certdata['pkey'])) {
                    $msg = "Impossivel gravar no diretório!!! Permissão negada!!";
                    throw new NfephpException($msg);
                }
                $n = file_put_contents($this->pubKEY, $x509certdata['cert']);
                $n = file_put_contents($this->certKEY, $x509certdata['pkey']."\r\n".$x509certdata['cert']);
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return true;
    } //fim loadCerts

    /**
     * validCerts
     * 
     * Validaçao do cerificado digital, além de indicar
     * a validade, este metodo carrega a propriedade
     * mesesToexpire da classe que indica o numero de
     * meses que faltam para expirar a validade do mesmo
     * esta informacao pode ser utilizada para a gestao dos
     * certificados de forma a garantir que sempre estejam validos
     *
     * @name validCerts
     * @param    string  $cert Certificado digital no formato pem
     * @param    array   $aRetorno variavel passa por referência Array com os dados do certificado
     * @return	boolean true ou false
     */
    protected function validCerts($cert = '', &$aRetorno = '')
    {
        try {
            if ($cert == '') {
                $msg = "O certificado é um parâmetro obrigatorio.";
                throw new NfephpException($msg);
            }
            if (!$data = openssl_x509_read($cert)) {
                $msg = "O certificado não pode ser lido pelo SSL - $cert .";
                throw new NfephpException($msg);
            }
            $flagOK = true;
            $errorMsg = "";
            $cert_data = openssl_x509_parse($data);
            // reformata a data de validade;
            $ano = substr($cert_data['validTo'], 0, 2);
            $mes = substr($cert_data['validTo'], 2, 2);
            $dia = substr($cert_data['validTo'], 4, 2);
            //obtem o timestamp da data de validade do certificado
            $dValid = gmmktime(0, 0, 0, $mes, $dia, $ano);
            // obtem o timestamp da data de hoje
            $dHoje = gmmktime(0, 0, 0, date("m"), date("d"), date("Y"));
            // compara a data de validade com a data atual
            if ($dValid < $dHoje) {
                $flagOK = false;
                $errorMsg = "A Validade do certificado expirou em ["  . $dia.'/'.$mes.'/'.$ano . "]";
            } else {
                $flagOK = $flagOK && true;
            }
            //diferença em segundos entre os timestamp
            $diferenca = $dValid - $dHoje;
            // convertendo para dias
            $diferenca = round($diferenca /(60*60*24), 0);
            //carregando a propriedade
            $daysToExpire = $diferenca;
            // convertendo para meses e carregando a propriedade
            $m = ($ano * 12 + $mes);
            $n = (date("y") * 12 + date("m"));
            //numero de meses até o certificado expirar
            $monthsToExpire = ($m-$n);
            $this->certMonthsToExpire = $monthsToExpire;
            $this->certDaysToExpire = $daysToExpire;
            $this->pfxTimestamp = $dValid;
            $aRetorno = array('status'=>$flagOK,'error'=>$errorMsg,'meses'=>$monthsToExpire,'dias'=>$daysToExpire);
        } catch (NfephpException $e) {
            throw $e;
            return false;
        }
        return true;
    } //fim validCerts

    /**
     * cleanCerts
     * Retira as chaves de inicio e fim do certificado digital
     * para inclusão do mesmo na tag assinatura do xml
     *
     * @name cleanCerts
     * @param $certFile
     * @return mixed false ou string contendo a chave digital limpa
     */
    protected function cleanCerts($certFile)
    {
        try {
            //inicializa variavel
            $data = '';
            //carregar a chave publica do arquivo pem
            if (!$pubKey = file_get_contents($certFile)) {
                $msg = "Arquivo não encontrado - $certFile .";
                throw new NfephpException($msg);
            }
            //carrega o certificado em um array usando o LF como referencia
            $arCert = explode("\n", $pubKey);
            foreach ($arCert as $curData) {
                //remove a tag de inicio e fim do certificado
                if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
                    //carrega o resultado numa string
                    $data .= trim($curData);
                }
            }
        } catch (NfephpException $e) {
            throw $e;
            return false;
        }
        return $data;
    }//fim cleanCerts
    
    
    /**
     * signXML
     * 
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * os arquivos XML
     *
     * @name signXML
     * @param	mixed $docxml Path para o arquivo xml ou String contendo o arquivo XML a ser assinado
     * @param   string $tagid TAG do XML que devera ser assinada
     * @return	mixed false se houve erro ou string com o XML assinado
     */
    public function signXML($docxml, $tagid = '')
    {
        try {
            if ($tagid == '') {
                $msg = "Uma tag deve ser indicada para que seja assinada!!";
                throw new NfephpException($msg);
            }
            if ($docxml == '') {
                $msg = "Um xml deve ser passado para que seja assinado!!";
                throw new NfephpException($msg);
            }
            if (is_file($docxml)) {
                $xml = file_get_contents($docxml);
            } else {
                $xml = $docxml;
            }
            //testar o xml a procura de erros antes de prosseguir
            // obter o chave privada para a ssinatura
            $fp = fopen($this->priKEY, "r");
            $priv_key = fread($fp, 8192);
            fclose($fp);
            $pkeyid = openssl_get_privatekey($priv_key);
            // limpeza do xml com a retirada dos CR, LF e TAB
            $order = array("\r\n", "\n", "\r", "\t");
            $replace = '';
            $xml = str_replace($order, $replace, $xml);
            // Habilita a manipulaçao de erros da libxml
            libxml_use_internal_errors(true);
            //limpar erros anteriores que possam estar em memória
            libxml_clear_errors();
            // carrega o documento no DOM
            $xmldoc = new DOMDocument('1.0', 'utf-8');
            $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
            $xmldoc->formatOutput = false;
            // muito importante deixar ativadas as opçoes para limpar os espacos em branco
            // e as tags vazias
            if ($xmldoc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $root = $xmldoc->documentElement;
            } else {
                $msg = "Erro ao carregar XML, provavel erro na passagem do parâmetro docxml ou no próprio xml!!";
                $errors = libxml_get_errors();
                if (!empty($errors)) {
                    $i = 1;
                    foreach ($errors as $error) {
                        $msg .= "\n  [$i]-" . trim($error->message);
                    }
                    libxml_clear_errors();
                }
                throw new NfephpException($msg);
            }
            //extrair a tag com os dados a serem assinados
            $node = $xmldoc->getElementsByTagName($tagid)->item(0);
            if (!isset($node)) {
                $msg = "A tag < $tagid > não existe no XML!!";
                throw new NfephpException($msg);
            }
            $id = trim($node->getAttribute("Id"));
            $idnome = preg_replace('/[^0-9]/', '', $id);
            //extrai os dados da tag para uma string
            $dados = $node->C14N(false, false, null, null);
            //calcular o hash dos dados
            $hashValue = hash('sha1', $dados, true);
            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);
            //monta a tag da assinatura digital
            $Signature = $xmldoc->createElementNS(URLDSIG, 'Signature');
            $root->appendChild($Signature);
            $SignedInfo = $xmldoc->createElement('SignedInfo');
            $Signature->appendChild($SignedInfo);
            //Cannocalization
            $newNode = $xmldoc->createElement('CanonicalizationMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', URLCANONMETH);
            //SignatureMethod
            $newNode = $xmldoc->createElement('SignatureMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', URLSIGMETH);
            //Reference
            $Reference = $xmldoc->createElement('Reference');
            $SignedInfo->appendChild($Reference);
            $Reference->setAttribute('URI', '#'.$id);
            //Transforms
            $Transforms = $xmldoc->createElement('Transforms');
            $Reference->appendChild($Transforms);
            //Transform
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', URLTRANSFMETH1);
            //Transform
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', URLTRANSFMETH2);
            //DigestMethod
            $newNode = $xmldoc->createElement('DigestMethod');
            $Reference->appendChild($newNode);
            $newNode->setAttribute('Algorithm', URLDIGESTMETH);
            //DigestValue
            $newNode = $xmldoc->createElement('DigestValue', $digValue);
            $Reference->appendChild($newNode);
            // extrai os dados a serem assinados para uma string
            $dados = $SignedInfo->C14N(false, false, null, null);
            //inicializa a variavel que irá receber a assinatura
            $signature = '';
            //executa a assinatura digital usando o resource da chave privada
            $resp = openssl_sign($dados, $signature, $pkeyid);
            //codifica assinatura para o padrao base64
            $signatureValue = base64_encode($signature);
            //SignatureValue
            $newNode = $xmldoc->createElement('SignatureValue', $signatureValue);
            $Signature->appendChild($newNode);
            //KeyInfo
            $KeyInfo = $xmldoc->createElement('KeyInfo');
            $Signature->appendChild($KeyInfo);
            //X509Data
            $X509Data = $xmldoc->createElement('X509Data');
            $KeyInfo->appendChild($X509Data);
            //carrega o certificado sem as tags de inicio e fim
            $cert = $this->cleanCerts($this->pubKEY);
            //X509Certificate
            $newNode = $xmldoc->createElement('X509Certificate', $cert);
            $X509Data->appendChild($newNode);
            //grava na string o objeto DOM
            $xml = $xmldoc->saveXML();
            // libera a memoria
            openssl_free_key($pkeyid);
        } catch (NfephpException $e) {
            //$this->setError($e->getMessage());
            throw $e;
            return false;
        }
        //retorna o documento assinado
        return $xml;
    } //fim signXML
    
        
    /**
     * verifySignatureXML
     * Verifica correção da assinatura no xml
     * 
     * @param string $xml xml a ser verificado (path para o arquivo ou o conteúdo do arquivo)
     * @param string $tag tag que é assinada
     * @param string $err variavel passada como referencia onde são retornados os erros
     * @return boolean false se não confere e true se confere
     */
    protected function verifySignature($xml, $tag, &$err)
    {
        try {
            // Habilita a manipulaçao de erros da libxml
            libxml_use_internal_errors(true);
            $dom = new DOMDocument('1.0', 'utf-8');
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
            $X509Certificate = $dom->getElementsByTagName('X509Certificate')->item(0)->nodeValue;
            $X509Certificate =  "-----BEGIN CERTIFICATE-----\n".
            $this->splitLines($X509Certificate)."\n-----END CERTIFICATE-----\n";
            $pubKey = openssl_pkey_get_public($X509Certificate);
            if ($pubKey === false) {
                $msg = "Ocorreram problemas ao remontar a chave pública. Certificado incorreto ou corrompido!!";
                $err = $msg;
                return false;
            }
            // remontando conteudo que foi assinado
            $conteudoAssinado = $dom->getElementsByTagName('SignedInfo')->item(0)->C14N(false, false, null, null);
            // validando assinatura do conteudo
            $conteudoAssinadoNoXML = $dom->getElementsByTagName('SignatureValue')->item(0)->nodeValue;
            $conteudoAssinadoNoXML = base64_decode(str_replace(array("\r", "\n"), '', $conteudoAssinadoNoXML));
            $ok = openssl_verify($conteudoAssinado, $conteudoAssinadoNoXML, $pubKey);
            if ($ok != 1) {
                $msg = "Problema ({$ok}) ao verificar a assinatura do digital!!";
                $err = $msg;
                return false;
            }
        } catch (NfephpException $e) {
            //$this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return true;
    } // fim verifySignatureXML
}//fim da classe
