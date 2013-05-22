<?php

namespace SpedPHP\Common\Soap;

use SpedPHP\Common\Exception;

/**
 * Esta classe trata os wsdl para comunicação com os webservices 
 *
 * @author Roberto L. Machado
 */
class Wsdl
{
    public function updateWsdl()
    {
        $wsFile = '../config/nfe_ws2.xml';
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
    }//fim downLoadWsdl
}
