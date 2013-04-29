<?php

/**
 * NFePHP (http://www.nfephp.org/)
 *
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 * @copyright Copyright (c) 2008-2013 NFePHP (http://www.nfephp.org)
 * @license   LGPL v3
 * @license   GPL v3
 * @package   NFePHP
 */

namespace library\Exception;

/**
 * Classe complementar 
 * necessária para extender a classe base Exception
 * Usada no tratamento de erros da API
 * 
 * @version 1.1.0
 * @package NFePHP
 * @name nfephpException
 * 
 */
class NfephpException extends Exception
{
    
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
        $this->sendNotifications();
        $this->logError();
    }


    protected function sendNotifications()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

 
    protected function logError()
    {
        // fazer algum log aqui
    }
}
