<?php

namespace Spedphp\Common\Soap;

use Spedphp\Common\Soap\CurlSoap;
use Spedphp\Common\Exception;
use LSS\XML2Array;

/**
 * Esta classe trata os wsdl para comunicação com os webservices 
 *
 * @author Roberto L. Machado
 */
class Wsdl
{
    public function updateWsdl($wsdlDir, $wsFile, $privateKey, $publicKey)
    {
        $retorno = true;
        $xml = file_get_contents($wsFile);
        //converte o xml em array
        $ws = XML2Array::createArray($xml);
        //para cada UF
        foreach ($ws['WS']['UF'] as $uf) {
            $sigla = $uf['sigla'];
            $ambiente = array('homologacao','producao');
            //para cada ambiente
            foreach ($ambiente as $amb) {
                $h = $uf[$amb];
                if (isset($h)) {
                    foreach ($h as $k => $j) {
                        $nome = $k;
                        $url=$j['@value'];
                        $metodo=$j['@attributes']['method'];
                        $versao = $j['@attributes']['version'];
                        if ($url != '') {
                            $urlsefaz = $url.'?wsdl';
                            $fileName = $wsdlDir.DIRECTORY_SEPARATOR.$amb.DIRECTORY_SEPARATOR.
                                    $sigla.'_'.$metodo.'.asmx';
                            if ($wsdl = $this->downLoadWsdl($urlsefaz, $privateKey, $publicKey)) {
                                file_put_contents($fileName, $wsdl);
                                chmod($fileName, 755);
                                //echo $fileName;
                                //return true;
                            } else {
                                return false;
                            }
                        }
                    }
                }
            }
        }
        return true;
    }//fim updateWsdl
    
    protected function downLoadWsdl($url, $privateKey, $publicKey)
    {
        $soap = new CurlSoap($privateKey, $publicKey, $timeout = 10);
        return $soap->getWsdl($url);
    }//fim downLoadWsdl
}//fim WSDL
