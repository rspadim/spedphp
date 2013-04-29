<?php

require_once '/usr/share/php/PHPUnit/Autoload.php';
require_once '../vendor/autoload.php';

use library\Aux\AuxClass; 

class AuxClassTest extends PHPUnit_Framework_TestCase
{
    public function testListDir()
    {
        $aux = new AuxClass;
        $l = array('DaccePHP.php','DanfePHP.php','NFePHP.php' );
        $list = $aux::listDir('/var/www/spedphp/src/NFePHP/','*.php');
        $this->assertEquals($l,$list);
    }
    
}



?>
