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

/**
 * 
 * Classe complementar
 * necessária para a comunicação SOAP 1.2
 * Remove algumas tags para adequar a comunicação
 * ao padrão "esquisito" utilizado pelas SEFAZ
 *
 * @name CorrectSoapClient
 *
 */
class CorrectedSoapClient extends SoapClient
{
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $aFind = array(":ns1","ns1:","\n","\r");
        $aReplace = array("","","","");
        $request = str_replace($aFind, $aReplace, $request);
        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }
}//fim da classe
