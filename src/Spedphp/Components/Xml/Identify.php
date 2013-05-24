<?php

namespace SpedPHP\Components\Xml;

use SpedPHP\Common\Exception;

/**
 * Esta classe perimite a identificação do xml
 * passado como parâmetro e permite também a identificação do 
 * arquivo xsd válido para sua validação
 *
 * @author administrador
 */
class Identify
{
    public function typeXml()
    {
        $xmlfile = file_get_contents($filename);
        $xml = simplexml_load_string($xmlfile);
        $tagroot = $xml->getName();
        switch ($tagroot){
            case 'NFe':
                //NFe sem protocolo
                break;
            case 'nfeProc':
                //NFe com o protocolo
                break;
            case 'CTe':
                //CTe sem protocolo
                break;
            case 'cteProc':
                //CTe com o protocolo
                break;
            case 'evento':
                //evento sem o protocolo
                break;
            case 'envEvento':
                //Envio de evento
                break;
            case 'retEnvEvento':
                //Retorno de evento
                break;
            case 'procEventoNFe':
                //Evento com o protocolo
                break;
            case 'ConsCad':
                //consulta de cadastro
                break;
            case 'consReciNFe':
                //consulta recibo
                break;
            case 'retConsReciNFe':
                //retorno da consulta do recibo
                break;
            case 'cancNFe':
                //solicitação de cancelamento - DEPRECATE
                break;
            case 'retCancNFe':
                //retorno da solicitação de cancelamento - DEPRECATE
                break;
            case 'inutNFe':
                //solicitação de inutilização
                break;
            case 'retInutNFe':
                //retorno da solicitação de inutilização
                break;
            case 'consSitNFe':
                //consulta da situação da NFe
                break;
            case 'retConsSitNFe':
                //retorno da consulta da situação da NFe
                break;
            case 'consStatServ':
                //consulta do status do serviço
                break;
            case 'retConsStatServ':
                //retorno da consulta do status do serviço
                break;
        }
    }//fim tipo xml

    public function findXsd($xml)
    {
        //pegar o elemento
        //pegar a versão

        $aXSD = array(
            'cancNFe'=>'cancNFe_v',
            'CCe' => 'CCe_v',
            '' => 'confRecebto_v',
            '' => 'consCad_v',
            '' => 'consNFeDest_v',
            '' => 'consReciNFe_v',
            '' => 'consSitNFe_v',
            '' => 'consSitNFe_v',
            '' => 'consStatServ_v',
            '' => 'downloadNFe_v',
            '' => 'envCCe_v',
            '' => 'envConfRecebto_v',
            '' => 'envEvento_v',
            '' => 'enviNFe_v',
            '' => 'inutNFe_v',
            '' => 'nfe_v',
            '' => 'procCancNFe_v',
            '' => 'procCCeNFe_v',
            '' => 'procConfRecebtoNFe_v',
            '' => 'procEventoNFe_v',
            '' => 'procInutNFe_v',
            '' => 'procNFe_v',
            '' => 'retCancNFe_v',
            '' => 'retConsCad_v',
            '' => 'retconsNFeDest_v',
            '' => 'retConsReciNFe_v',
            '' => 'retConsSitNFe_v',
            '' => 'retConsSitNFe_v',
            '' => 'retConsStatServ_v',
            '' => 'retDownloadNFe_v',
            '' => 'retEnvCCe_v',
            '' => 'retEnvConfRecebto_v',
            '' => 'retEnvEvento_v',
            '' => 'retEnviNFe_v',
            '' => 'retInutNFe_v'
        );


    }
}
