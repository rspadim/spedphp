<?php

/*
 * NFePHP (http://www.nfephp.org/)
 *
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 * @copyright Copyright (c) 2008-2013 NFePHP (http://www.nfephp.org)
 * @license   LGPL v3
 * @license   GPL v3
 * @package   NFePHP
 */

namespace Common\Soap;

/**
 * 
 * Classe complementar
 * necessária para a comunicação SOAP 1.2
 * Remove algumas tags para adequar a comunicação
 * ao padrão "esquisito" utilizado pelas SEFAZ
 *
 * @version 1.0.4
 * @package NFePHP
 * @name CorrectSoapClient
 *
 */
class CorrectedSoapClient extends SoapClient
{
    
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $request = str_replace(':ns1', '', $request);
        $request = str_replace('ns1:', '', $request);
        $request = str_replace("\n", '', $request);
        $request = str_replace("\r", '', $request);
        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }
}
