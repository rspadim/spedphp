<?php

/**
 * Spedphp (http://www.nfephp.org/)
 *
 * @link      http://github.com/nfephp-org/spedphp for the canonical source repository
 * @copyright Copyright (c) 2008-2013 NFePHP (http://www.nfephp.org)
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @package   Spedphp
 */

namespace Spedphp\Common\Pkcs12;

class Asn
{
    
    /**
     * getCNPJCert
     * Obtem o CNPJ do certificado digital
     * @param string $cert_pem conteudo do certificado 
     * @return string CNPJ
     */
    public static function getCNPJCert($certPem)
    {
        $certDer = self::pem2Der($certPem);
        $data = self::getOIDdata($certDer, '2.16.76.1.3.3');
        return $data[0][1][1][0][1];
    }//fim getCNPJCert

    
    /**
     * 
     * @param type $data
     * @param type $contextEspecific
     * @return type
     */
    protected static function parseASN($data, $contextEspecific = false)
    {
        $result = array();
        while (strlen($data) > 1) {
            $class = ord($data[0]);
            switch ($class) {
                case 0x30:
                    // Sequence
                    self::parseSequence($data, $result);
                    break;
                case 0x31:
                    self::parseSetOf($data, $result);
                    break;
                case 0x01:
                    // Boolean type
                    $booleanValue = (ord($data[2]) == 0xff);
                    $data = substr($data, 3);
                    $result[] = array(
                        'boolean (1)',
                        $booleanValue);
                    break;
                case 0x02:
                    // Integer type
                    $len = ord($data[1]);
                    $bytes = 0;
                    self::getLength($len, $bytes, $data);
                    $integerData = substr($data, 2 + $bytes, $len);
                    $data = substr($data, 2 + $bytes + $len);
                    if ($len == 16) {
                        $result[] = array(
                            'integer('.$len.')',
                            $integerData);
                        break;
                    } else {
                        $value = 0;
                        if ($len <= 4) {
                            // metodo funciona bem para inteiros pequenos
                            for ($i = 0; $i < strlen($integerData); $i++) {
                                $value = ($value << 8) | ord($integerData[$i]);
                            }
                        } else {
                            // metodo trabalha com inteiros arbritrários
                            if (extension_loaded('bcmath')) {
                                for ($i = 0; $i < strlen($integerData); $i++) {
                                    $value = bcadd(bcmul($value, 256), ord($integerData[$i]));
                                }
                            } else {
                                $value = -1;
                            }
                        }
                        $result[] = array('integer(' . $len . ')', $value);
                        break;
                    }
                    break;
                case 0x03:
                    self::parseBitString($data, $result);
                    break;
                case 0x04:
                    self::parseOctetSting($data, $result, $contextEspecific);
                    break;
                case 0x0C:
                    self::parseUtf8String($data, $result, $contextEspecific);
                    break;
                case 0x05:
                    // Null type
                    $data = substr($data, 2);
                    $result[] = array('null', null);
                    break;
                case 0x06:
                    self::parseOIDtype($data, $result);
                    break;
                case 0x16:
                    self::parseIA5String($data, $result);
                    break;
                case 0x12:
                case 0x14:
                case 0x15:
                case 0x81:
                    self::parseString($data, $result);
                    break;
                case 0x80:
                    // Character string type
                    $len = ord($data[1]);
                    $bytes = 0;
                    self::getLength($len, $bytes, $data);
                    $stringData = substr($data, 2 + $bytes, $len);
                    $data = substr($data, 2 + $bytes + $len);
                    $result[] = array(
                        'string (' . $len . ')',
                        self::printHex($stringData));
                    break;
                case 0x13:
                case 0x86:
                    // Printable string type
                    $len = ord($data[1]);
                    $bytes = 0;
                    self::getLength($len, $bytes, $data);
                    $stringData = substr($data, 2 + $bytes, $len);
                    $data = substr($data, 2 + $bytes + $len);
                    $result[] = array(
                        'Printable String (' . $len . ')',
                        $stringData);
                    break;
                case 0x17:
                    // Time types
                    $len = ord($data[1]);
                    $bytes = 0;
                    self::getLength($len, $bytes, $data);
                    $timeData = substr($data, 2 + $bytes, $len);
                    $data = substr($data, 2 + $bytes + $len);
                    $result[] = array(
                        'utctime (' . $len . ')',
                        $timeData);
                    break;
                case 0x82:
                    // X509v3 extensions?
                    $len = ord($data[1]);
                    $bytes = 0;
                    self::getLength($len, $bytes, $data);
                    $sequenceData = substr($data, 2 + $bytes, $len);
                    $data = substr($data, 2 + $bytes + $len);
                    $result[] = array(
                        'extension : X509v3 extensions ('.$len.')',
                        array(self::parseASN($sequenceData)));
                    break;
                case 0xa0:
                    // Extensions
                    $len = ord($data[1]);
                    $bytes = 0;
                    self::getLength($len, $bytes, $data);
                    $extensionData = substr($data, 2 + $bytes, $len);
                    $data = substr($data, 2 + $bytes + $len);
                    $result[] = array(
                        'Context Especific (' . $len . ')',
                        array(self::parseASN($extensionData, true)));
                    break;
                case 0xa3:
                    // Extensions
                    $len = ord($data[1]);
                    $bytes = 0;
                    self::getLength($len, $bytes, $data);
                    $extensionData = substr($data, 2 + $bytes, $len);
                    $data = substr($data, 2 + $bytes + $len);
                    $result[] = array(
                        'extension (0xA3)  (' . $len . ')',
                        array(self::parseASN($extensionData)));
                    break;
                case 0xe6:
                    $extensionData = substr($data, 0, 1);
                    $data = substr($data, 1);
                    $result[] = array(
                        'extension (0xE6) (' . $len . ')',
                        dechex($extensionData));
                    break;
                case 0xa1:
                    $extensionData = substr($data, 0, 1);
                    $data = substr($data, 6);
                    $result[] = array(
                        'extension (0xA1) (' . $len . ')',
                        dechex($extensionData));
                    break;
                default:
                    // Unknown
                    $result[] = 'UNKNOWN' .  $data;
                    $data = '';
                    break;
            }
        }
        if (count($result) > 1) {
            return $result;
        } else {
            return array_pop($result);
        }
    }//fim parseASN

    protected static function parseSequence(&$data, &$result)
    {
        // Sequence
        $len = ord($data[1]);
        $bytes = 0;
        self::getLength($len, $bytes, $data);
        $sequenceData = substr($data, 2 + $bytes, $len);
        $data = substr($data, 2 + $bytes + $len);
        $values = self::parseASN($sequenceData);
        if (!is_array($values) || is_string($values[0])) {
            $values = array($values);
        }
        $result[] = array(
                        'sequence ('.$len.')',
                        $values);
    }
    
    protected static function parseOIDtype(&$data, &$result)
    {
        //lista com os números e descrição dos OID
        include_once('oidsTable.php');
        // Object identifier type
        $len = ord($data[1]);
        $bytes = 0;
        self::getLength($len, $bytes, $data);
        $oidData = substr($data, 2 + $bytes, $len);
        $data = substr($data, 2 + $bytes + $len);
        // Unpack the OID
        $plain  = floor(ord($oidData[0]) / 40);
        $plain .= '.' . ord($oidData[0]) % 40;
        $value = 0;
        $iCount = 1;
        while ($iCount < strlen($oidData)) {
            $value = $value << 7;
            $value = $value | (ord($oidData[$iCount]) & 0x7f);
            if (!(ord($oidData[$iCount]) & 0x80)) {
                $plain .= '.' . $value;
                $value = 0;
            }
            $iCount++;
        }
        if (isset($oidsTable[$plain])) {
            $result[] =  array(
                         'oid('.$len . '): '.$plain,
                         $oidsTable[$plain]);
        } else {
            $result[] = array(
                       'oid('.$len.'): '.$plain,
                        $plain);
        }
    }
    
    protected static function parseSetOf(&$data, &$result)
    {
        $len = ord($data[1]);
        $bytes = 0;
        self::getLength($len, $bytes, $data);
        $sequenceData = substr($data, 2 + $bytes, $len);
        $data = substr($data, 2 + $bytes + $len);
        $result[] = array(
                    'set (' . $len . ')',
                     self::parseASN($sequenceData));
    }
    
    protected static function parseOctetSting(&$data, &$result, $contextEspecific)
    {
        // Octetstring type
        $len = ord($data[1]);
        $bytes = 0;
        self::getLength($len, $bytes, $data);
        $octectstringData = substr($data, 2 + $bytes, $len);
        $data = substr($data, 2 + $bytes + $len);
        if ($contextEspecific) {
            $result[] = array(
                       'octet string('.$len.')',
                        $octectstringData);
        } else {
            $result[] = array(
                        'octet string ('.$len.')',
                        self::parseASN($octectstringData));
        }
    }

    protected static function parseUtf8String(&$data, &$result, $contextEspecific)
    {
        // UTF8 STRING
        $len = ord($data[1]);
        $bytes = 0;
        self::getLength($len, $bytes, $data);
        $octectstringData = substr($data, 2 + $bytes, $len);
        $data = substr($data, 2 + $bytes + $len);
        if ($contextEspecific) {
            $result[] = array(
                        'utf8 string('.$len.')',
                        $octectstringData);
        } else {
            $result[] = array(
                        'utf8 string ('.$len.')',
                        self::parseASN($octectstringData));
        }
    }

    protected static function parseIA5String(&$data, &$result)
    {
        // Character string type
        $len = ord($data[1]);
        $bytes = 0;
        self::getLength($len, $bytes, $data);
        $stringData = substr($data, 2 + $bytes, $len);
        $data = substr($data, 2 + $bytes + $len);
        $result[] = array(
                    'IA5 String (' . $len . ')',
                    $stringData);
    }
    
    protected static function parseString(&$data, &$result)
    {
        // Character string type
        $len = ord($data[1]);
        $bytes = 0;
        self::getLength($len, $bytes, $data);
        $stringData = substr($data, 2 + $bytes, $len);
        $data = substr($data, 2 + $bytes + $len);
        $result[] = array(
                    'string (' . $len . ')',
                    $stringData);
    }

    protected static function parseBitString(&$data, &$result)
    {
        // Bitstring type
        $len = ord($data[1]);
        $bytes = 0;
        self::getLength($len, $bytes, $data);
        $bitstringData = substr($data, 2 + $bytes, $len);
        $data = substr($data, 2 + $bytes + $len);
        $result[] = array(
                    'bit string ('.$len.')',
                    'UnsedBits:'.ord($bitstringData[0]).':'.ord($bitstringData[1]));
    }

    /**
     * printHex
     * Retorna o valor em caracteres hexadecimais
     * 
     * @param strint $value 
     * @return string
     */
    protected static function printHex($value)
    {
        $tabVal = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
        for ($i=0; $i<strlen($value); $i++) {
            $lsig = ord(substr($value, $i, 1)) % 16;
            $msig = (ord(substr($value, $i, 1)) - $lsig) / 16;
            $lessSig = $tabVal[$lsig];
            $moreSig = $tabVal[$msig];
            $hex .=  $moreSig.$lessSig;
        }
        return $hex;
    }//fim printHex

    /**
     * getLength
     * Obtêm o comprimento do conteúdo de uma sequência de dados do certificado
     * 
     * @param numeric $len variável passada por referência
     * @param numeric $bytes variável passada por referência
     * @param string $data campo a 
     */
    protected static function getLength(&$len, &$bytes, $data)
    {
        $len = ord($data[1]);
        $bytes = 0;
        // Testa se tamanho menor/igual a 127 bytes,
        // se for, então $len já é o tamanho do conteúdo
        if ($len & 0x80) {
            // Testa se tamanho indefinido (nao deve ocorrer em uma codificação DER)
            if ($len == chr(0x80)) {
                // Tamanho indefinido, limitado por 0x0000h
                $len = strpos($data, chr(0x00).chr(0x00));
                $bytes = 0;
            } else {
                //é tamanho definido. diz quantos bytes formam o tamanho
                $bytes = $len & 0x0f;
                $len = 0;
                for ($i = 0; $i < $bytes; $i++) {
                    $len = ($len << 8) | ord($data[$i + 2]);
                }
            }
        }
    }//fim getLength

    /**
     * pem2Der
     * Transforma o certificado do formato PEM para o formato DER
     * 
     * @param type $pem_data
     * @return type
     */
    protected static function pem2Der($pemData)
    {
        $begin = "CERTIFICATE-----";
        $end = "-----END";
        //extrai o conteúdo do certificado entre as marcas BEGIN e END
        $pemData = substr($pemData, strpos($pemData, $begin) + strlen($begin));
        $pemData = substr($pemData, 0, strpos($pemData, $end));
        //converte o resultado para binário obtendo um certificado em formato DER
        $derData = base64_decode($pemData);
        return $derData;
    }//fim pem2Der
    
    
    /**
     * getOIDdata
     * Recupera a informação referente ao OID contido no certificado
     * Este método assume que a OID está inserida dentro de uma estrutura do
     * tipo "sequencia", como primeiro elemento da estrutura
     * 
     * @param string $certDer
     * @param string $oidNumber
     * @return array
     */
    protected static function getOIDdata($certDer, $oidNumber)
    {
        //converte onumero OTD de texto para hexadecimal
        $oidHexa = self::oidtoHex($oidNumber);
        //Divide o certificado usando a OID como marcador,uma antes do OID e outra contendo o OID.
        //Normalmente o certificado será dividido em duas partes, pois em geral existe
        //apenas um OID de cada tipo no certificado, mas podem haver mais.
        $partes = explode($oidHexa, $certDer);
        $ret = array();
        //se count($partes) > 1 então o OID foi localizado no certificado
        if (count($partes)>1) {
            //O inicio da sequencia que nos interessa pode estar a 3 ou 2 digitos
            //antes do inicio da OID, isso depende do numero de bytes usados para
            //identificar o tamanho da sequencia
            for ($i=1; $i<count($partes); $i++) {
                //recupera da primeira parte os 4 últimos digitos na parte sem o OID
                $xcv4 = substr($partes[$i-1], strlen($partes[$i-1])-4, 4);
                //recupera da primeira parte os 3 ultimos digitos na parte sem o OID
                $xcv3 = substr($partes[$i-1], strlen($partes[$i-1])-3, 3);
                //recupera da primeira parte os 2 ultimos digitos na parte em o OID
                $xcv2 = substr($partes[$i-1], strlen($partes[$i-1])-2, 2);
                //verifica se o primeiro digito é Hex 030
                if ($xcv4[0] == chr(0x30)) {
                    //se for, então tamanho é definido por esses 4 bytes
                    $xcv = $xcv4;
                } else {
                    //se for, então tamanho é definido por esses 3 bytes
                    if ($xcv3[0] == chr(0x30)) {
                        $xcv = $xcv3;
                    } else {
                        //então tamanho é definido por esses 2 bytes
                        $xcv = $xcv2;
                    }
                }
                //reconstroi a sequencia, marca do tamanho do campo, OID e
                //a parte do certificado com o OID
                $data = $xcv . $oidHexa . $partes[$i];
                //converte para decimal, o segundo digito da sequencia
                $len = ord($data[1]);
                $bytes = 0;
                // obtem tamanho da parte de dados da oid
                self::getLength($len, $bytes, $data);
                // Obtem o conjunto de bytes pertencentes a oid
                $oidData = substr($data, 2 + $bytes, $len);
                //parse dos dados da oid
                $ret[] =  self::parseASN($oidData);
            }
        }
        return $ret;
    }//fim getOIDdata
    
    
    /**
     * oidtoHex
     * Converte o numero de identificação do OID em uma representação asc,
     * coerente com o formato do certificado
     * 
     * @param string $oid numero OID (com os pontos de separação)
     * @return string sequencia em hexadecimal 
     */
    protected static function oidtoHex($oid)
    {
        if ($oid == '') {
            return '';
        }
        $abBinary = array();
        //coloca cada parte do numero do OID em uma linha da matriz
        $partes = explode('.', $oid);
        $num = 0;
        $bun = 0;
        //para cada numero compor o valor asc do mesmo
        for ($num = 0; $num < count($partes); $num++) {
            if ($num==0) {
                $bun = 40 * $partes[$num];
            } elseif ($num==1) {
                $bun +=  $partes[$num];
                $abBinary[] = $bun;
            } else {
                    $abBinary = self::xBase128($abBinary, $partes[$num], 1);
            }
        }
        $value =chr(0x06) . chr(count($abBinary));
        //para cada item da matriz compor a string de retorno como caracter
        foreach ($abBinary as $item) {
            $value .= chr($item);
        }
        return $value;
    }//fim oidtoHex

    
    /**
     * xBase128
     * 
     * @param numeric $abIn
     * @param numeric $qIn 
     * @param numeric $flag
     * @return numeric
     */
    protected static function xBase128($abIn, $qIn, $flag)
    {
        $abc = $abIn;
        if ($qIn > 127) {
            $abc = self::xBase128($abc, floor($qIn/128), 0);
        }
        $qIn = $qIn % 128;
        if ($flag) {
            $abc[] = $qIn;
        } else {
            $abc[] = 0x80 | $qIn;
        }
        return $abc;
    }//fim xBase128
}
