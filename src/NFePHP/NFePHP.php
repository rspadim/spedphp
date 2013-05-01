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

namespace NFePHP;

use library\Exception\NfephpException;
use library\Soap\NfephpSoapClient;
use library\Pkcs12\Pkcs12;


/**
 * @package   NFePHP
 * @name      NFePHP
 * @version   4.0.0
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 */

class NFePHP
{

    // propriedades da classe

    /**
    * raizDir
    * Diretorio raiz da API
    * @var string
    */
    public $raizDir='';
    /**
     * arqDir
     * Diretorio raiz de armazenamento das notas
     * @var string
     */
    public $arqDir='';
    /**
     * pdfDir
     * Diretorio onde são armazenados temporariamente as notas em pdf
     * @var string
     */
    public $pdfDir ='';
    /**
     * entDir
     * Diretorio onde são armazenados temporariamente as notas criadas (em txt ou xml)
     * @var string
     */
    public $entDir='';
    /**
     * valDir
     * Diretorio onde são armazenados temporariamente as notas já validadas pela API
     * @var string
     */
    public $valDir='';
    /**
     * repDir
     * Diretorio onde são armazenados as notas reprovadas na validação da API
     * @var string
     */
    public $repDir='';
    /**
     * assDir
     * Diretorio onde são armazenados temporariamente as notas já assinadas
     * @var string
     */
    public $assDir='';
    /**
     * envDir
     * Diretorio onde são armazenados temporariamente as notas enviadas
     * @var string
     */
    public $envDir='';
    /**
     * aprDir
     * Diretorio onde são armazenados temporariamente as notas aprovadas
     * @var string
     */
    public $aprDir='';
    /**
     * denDir
     * Diretorio onde são armazenados as notas denegadas
     * @var string
     */
    public $denDir='';
    /**
     * rejDir
     * Diretorio onde são armazenados os retornos e as notas com as rejeitadas após o envio do lote
     * @var string
     */
    public $rejDir='';
    /**
     * canDir
     * Diretorio onde são armazenados os pedidos e respostas de cancelamento
     * @var string
     */
    public $canDir='';
    /**
     * inuDir
     * Diretorio onde são armazenados os pedidos de inutilização de numeros de notas
     * @var string
     */
    public $inuDir='';
    /**
     * cccDir
     * Diretorio onde são armazenados os pedidos das cartas de correção
     * @var string
     */
    public $cccDir='';
    /**
     * evtDir
     * Diretorio de arquivos dos eventos como as Manuifetações do Destinatário
     * @var string
     */
    public $evtDir='';
    /**
     * dpcDir
     * Diretorio de arquivos dos DPEC
     * @var string
     */
    public $dpcDir='';
    /**
     * tempDir
     * Diretorio de arquivos temporarios ou não significativos para a operação do sistema
     * @var string
     */
    public $temDir='';
    /**
     * recDir
     * Diretorio de arquivos temporarios das NFe recebidas de terceiros
     * @var string
     */
    public $recDir='';
    /**
     * conDir
     * Diretorio de arquivos das notas recebidas de terceiros e já validadas
     * @var string
     */
    public $conDir='';
    /**
     * libsDir
     * Diretorios onde estão as bibliotecas e outras classes
     * @var string
     */
    public $libsDir='';
    /**
     * certsDir
     * Diretorio onde estão os certificados
     * @var string
     */
    public $certsDir='';
    /**
     * imgDir
     * Diretorios com a imagens, fortos, logos, etc..
     * @var string
     */
    public $imgDir='';
    /**
     * xsdDir
     * diretorio que contem os esquemas de validação
     * estes esquemas devem ser mantidos atualizados
     * @var string
     */
    public $xsdDir='';
    /**
     * xmlURLfile
     * Arquivo xml com as URL do SEFAZ de todos dos Estados
     * @var string
     */
    public $xmlURLfile='nfe_ws2.xml';
    /**
     * enableSCAN
     * Habilita contingência ao serviço SCAN ao invés do webservice estadual
     * @var boolean
     */
    public $enableSCAN=false;
    /**
     * enableDEPC
     * Habilita contingência por serviço DPEC ao invés do webservice estadual
     * @var boolean
     */
    public $enableDPEC=false;
    /**
     * enableSVAN
     * Indica o acesso ao serviço SVAN
     * @var boolean
     */
    public $enableSVAN=false;
    /**
     * modSOAP
     * Indica o metodo SOAP a usar 1-SOAP Nativo ou 2-cURL
     * @var string
     */
    public $modSOAP='2';
    /**
     * soapTimeout
     * Limite de tempo que o SOAP aguarda por uma conexão
     * @var integer 0-indefinidamente ou numero de segundos
     */
    public $soapTimeout = 10;
    /**
     * tpAmb
     * Tipo de ambiente 1-produção 2-homologação
     * @var string
     */
    protected $tpAmb='';
    /**
     * schemeVer
     * String com o nome do subdiretorio onde se encontram os schemas
     * atenção é case sensitive
     * @var string
     */
    protected $schemeVer;
    /**
     * aProxy
     * Matriz com as informações sobre o proxy da rede para uso pelo SOAP
     * @var array IP PORT USER PASS
     */
    public $aProxy='';
    /**
     * keyPass
     * Senha de acesso a chave privada
     * @var string
     */
    private $keyPass='';
    /**
     * passPhrase
     * palavra passe para acessar o certificado (normalmente não usada)
     * @var string
     */
    private $passPhrase='';
    /**
     * certName
     * Nome do certificado digital
     * @var string
     */
    private $certName='';
    /**
     * certMonthsToExpire
     * Meses que faltam para o certificado expirar
     * @var integer
     */
    public $certMonthsToExpire=0;
    /**
     * certDaysToExpire
     * Dias que faltam para o certificado expirar
     * @var integer
     */
    public $certDaysToExpire=0;
    /**
     * pfxTimeStamp
     * Timestamp da validade do certificado A1 PKCS12 .pfx
     * @var timestamp
     */
    private $pfxTimestamp=0;
    /**
     * priKEY
     * Path completo para a chave privada em formato pem
     * @var string
     */
    protected $priKEY='';
    /**
     * pubKEY
     * Path completo para a chave public em formato pem
     * @var string
     */
    protected $pubKEY='';
    /**
     * certKEY
     * Path completo para o certificado (chave privada e publica) em formato pem
     * @var string
     */
    protected $certKEY='';
    /**
     * empName
     * Razão social da Empresa
     * @var string
     */
    protected $empName='';
    /**
     * cnpj
     * CNPJ do emitente
     * @var string
     */
    protected $cnpj='';
    /**
     * cUF
     * Código da unidade da Federação IBGE
     * @var string
     */
    protected $cUF='';
    /**
     * UF
     * Sigla da Unidade da Federação
     * @var string
     */
    protected $UF='';
    /**
     * timeZone
     * Zona de tempo GMT
     */
    protected $timeZone = '-03:00';
    /**
     * anoMes
     * Variável que contem o ano com 4 digitos e o mes com 2 digitos
     * Ex. 201003
     * @var string
     */
    private $anoMes='';
    /**
     * aURL
     * Array com as url dos webservices
     * @var array
     */
    public $aURL='';
    /**
     * aCabec
     * @var array
     */
    public $aCabec='';
    /**
     * errMsg
     * Mensagens de erro do API
     * @var string
     */
    public $errMsg='';
    /**
     * errStatus
     * Status de erro
     * @var boolean
     */
    public $errStatus=false;
    /**
     * URLbase
     * Base da API
     * @var string
     */
    public $URLbase = '';
    /**
     * soapDebug
     * Mensagens de debug da comunicação SOAP
     * @var string
     */
    public $soapDebug='';
    /**
     * debugMode
     * Ativa ou desativa as mensagens de debug da classe
     * @var string
     */
    protected $debugMode=2;

    /**
     * URLPortal
     * Instância do WebService
     * @var string
     */
    private $URLPortal='http://www.portalfiscal.inf.br/nfe';

    /**
     * URLxsi
     * Instância do WebService
     * @var string
     */
    private $URLxsi='http://www.w3.org/2001/XMLSchema-instance';
    /**
     * URLxsd
     * Instância do WebService
     * @var string
     */
    private $URLxsd='http://www.w3.org/2001/XMLSchema';
    /**
     * URLnfe
     * Instância do WebService
     * @var string
     */
    private $URLnfe='http://www.portalfiscal.inf.br/nfe';
    /**
     * aliaslist
     * Lista dos aliases para os estados que usam o SEFAZ VIRTUAL
     * @var array
     */
    private $aliaslist = array('AC'=>'SVRS',
                               'AL'=>'SVRS',
                               'AM'=>'AM',
                               'AN'=>'AN',
                               'AP'=>'SVRS',
                               'BA'=>'BA',
                               'CE'=>'CE',
                               'DF'=>'SVRS',
                               'ES'=>'SVAN',
                               'GO'=>'GO',
                               'MA'=>'SVAN',
                               'MG'=>'MG',
                               'MS'=>'MS',
                               'MT'=>'MT',
                               'PA'=>'SVAN',
                               'PB'=>'SVRS',
                               'PE'=>'PE',
                               'PI'=>'SVAN',
                               'PR'=>'PR',
                               'RJ'=>'SVRS',
                               'RN'=>'SVAN',
                               'RO'=>'SVRS',
                               'RR'=>'SVRS',
                               'RS'=>'RS',
                               'SC'=>'SVRS',
                               'SE'=>'SVRS',
                               'SP'=>'SP',
                               'TO'=>'SVRS',
                               'SCAN'=>'SCAN',
                               'SVAN'=>'SVAN',
                               'DPEC'=>'DPEC');
    /**
     * cUFlist
     * Lista dos numeros identificadores dos estados
     * @var array
     */
    private $cUFlist = array('AC'=>'12',
                             'AL'=>'27',
                             'AM'=>'13',
                             'AP'=>'16',
                             'BA'=>'29',
                             'CE'=>'23',
                             'DF'=>'53',
                             'ES'=>'32',
                             'GO'=>'52',
                             'MA'=>'21',
                             'MG'=>'31',
                             'MS'=>'50',
                             'MT'=>'51',
                             'PA'=>'15',
                             'PB'=>'25',
                             'PE'=>'26',
                             'PI'=>'22',
                             'PR'=>'41',
                             'RJ'=>'33',
                             'RN'=>'24',
                             'RO'=>'11',
                             'RR'=>'14',
                             'RS'=>'43',
                             'SC'=>'42',
                             'SE'=>'28',
                             'SP'=>'35',
                             'TO'=>'17',
                             'SVAN'=>'91');

    /**
     * cUFlist
     * Lista dos numeros identificadores dos estados
     * @var array
     */
    private $UFList=array('11'=>'RO',
                          '12'=>'AC',
                          '13'=>'AM',
                          '14'=>'RR',
                          '15'=>'PA',
                          '16'=>'AP',
                          '17'=>'TO',
                          '21'=>'MA',
                          '22'=>'PI',
                          '23'=>'CE',
                          '24'=>'RN',
                          '25'=>'PB',
                          '26'=>'PE',
                          '27'=>'AL',
                          '28'=>'SE',
                          '29'=>'BA',
                          '31'=>'MG',
                          '32'=>'ES',
                          '33'=>'RJ',
                          '35'=>'SP',
                          '41'=>'PR',
                          '42'=>'SC',
                          '43'=>'RS',
                          '50'=>'MS',
                          '51'=>'MT',
                          '52'=>'GO',
                          '53'=>'DF',
                          '91'=>'SVAN');

    /**
     * tzUFlist
     * Lista das zonas de tempo para os estados brasileiros
     * @var array
     */
    private $tzUFlist = array('AC'=>'America/Rio_Branco',
                              'AL'=>'America/Sao_Paulo',
                              'AM'=>'America/Manaus',
                              'AP'=>'America/Sao_Paulo',
                              'BA'=>'America/Bahia',
                              'CE'=>'America/Fortaleza',
                              'DF'=>'America/Sao_Paulo',
                              'ES'=>'America/Sao_Paulo',
                              'GO'=>'America/Sao_Paulo',
                              'MA'=>'America/Sao_Paulo',
                              'MG'=>'America/Sao_Paulo',
                              'MS'=>'America/Campo_Grande',
                              'MT'=>'America/Cuiaba',
                              'PA'=>'America/Belem',
                              'PB'=>'America/Sao_Paulo',
                              'PE'=>'America/Recife',
                              'PI'=>'America/Sao_Paulo',
                              'PR'=>'America/Sao_Paulo',
                              'RJ'=>'America/Sao_Paulo',
                              'RN'=>'America/Sao_Paulo',
                              'RO'=>'America/Porto_Velho',
                              'RR'=>'America/Boa_Vista',
                              'RS'=>'America/Sao_Paulo',
                              'SC'=>'America/Sao_Paulo',
                              'SE'=>'America/Sao_Paulo',
                              'SP'=>'America/Sao_Paulo',
                              'TO'=>'America/Sao_Paulo');

    /**
     * aMail
     * Matriz com os dados para envio de emails
     * FROM HOST USER PASS
     * @var array
     */
    public $aMail='';
    /**
     * logopath
     * Variável que contem o path completo para a logo a ser impressa na DANFE
     * @var string $logopath
     */
    public $danfelogopath = '';
    /**
     * danfelogopos
     * Estabelece a posição do logo no DANFE
     * L-Esquerda C-Centro e R-Direita
     * @var string
     */
    public $danfelogopos = 'C';
    /**
     * danfeform
     * Estabelece o formato do DANFE
     * P-Retrato L-Paisagem (NOTA: somente o formato P é funcional, por ora)
     * @var string P-retrato ou L-Paisagem
     */
    public $danfeform = 'P';
    /**
     * danfepaper
     * Estabelece o tamanho da página
     * NOTA: somente o A4 pode ser utilizado de acordo com a ISO
     * @var string
     */
    public $danfepaper = 'A4';
    /**
     * danfecanhoto
     * Estabelece se o canhoto será impresso ou não
     * @var boolean
     */
    public $danfecanhoto = true;
    /**
     * danfefont
     * Estabelece a fonte padrão a ser utilizada no DANFE
     * de acordo com o Manual da SEFAZ usar somente Times
     * @var string
     */
    public $danfefont = 'Times';
    /**
     * danfeprinter
     * Estabelece a printer padrão a ser utilizada na impressão da DANFE
     * @var string
     */
    public $danfeprinter = '';

    /////////////////////////////////////////////////
    // CONSTANTES usadas no controle das exceções
    /////////////////////////////////////////////////
    const STOP_MESSAGE  = 0; // apenas um aviso, o processamento continua
    const STOP_CONTINUE = 1; // questionamento ?, perecido com OK para continuar o processamento
    const STOP_CRITICAL = 2; // Erro critico, interrupção total

    /**
     * __construct
     * Método construtor da classe
     * Este método utiliza o arquivo de configuração localizado no diretorio config
     * para montar os diretórios e várias propriedades internas da classe, permitindo
     * automatizar melhor o processo de comunicação com o SEFAZ.
     *
     * Este metodo pode estabelecer as configurações a partir do arquivo config.php ou
     * através de um array passado na instanciação da classe.
     *
     * @param array $aConfig Opcional dados de configuração
     * @param number $mododebug Opcional 2-Não altera nenhum paraâmetro 1-SIM ou 0-NÃO (2 default)
     * @return  boolean true sucesso false Erro
     */
    public function __construct($aConfig = '', $mododebug = 2)
    {

        $this->raizDir = dirname(__DIR__). DIRECTORY_SEPARATOR .  'NFePHP' . DIRECTORY_SEPARATOR;
        $this->certsDir = dirname(__DIR__). DIRECTORY_SEPARATOR .  'certs' . DIRECTORY_SEPARATOR;
        $this->imgDir = dirname(__DIR__). DIRECTORY_SEPARATOR .  'images' . DIRECTORY_SEPARATOR;

        if (is_numeric($mododebug)) {
            $this->debugMode = $mododebug;
        }
        if ($mododebug == 1) {
            //ativar modo debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }
        if ($mododebug == 0) {
            //desativar modo debug
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        //verifica se foi passado uma matriz de configuração na inicialização da classe
        if (is_array($aConfig)) {
            $this->tpAmb=$aConfig['ambiente'];
            $this->empName=$aConfig['empresa'];
            $this->UF=$aConfig['UF'];
            $this->cUF=$this->cUFlist[$aConfig['UF']];
            $this->cnpj=$aConfig['cnpj'];
            $this->certName=$aConfig['certName'];
            $this->keyPass=$aConfig['keyPass'];
            $this->passPhrase=$aConfig['passPhrase'];
            $this->arqDir = $aConfig['arquivosDir'];
            $this->xmlURLfile = $aConfig['arquivoURLxml'];
            $this->URLbase = $aConfig['baseurl'];
            $this->danfelogopath = $aConfig['danfeLogo'];
            $this->danfelogopos = $aConfig['danfeLogoPos'];
            $this->danfeform = $aConfig['danfeFormato'];
            $this->danfepaper = $aConfig['danfePapel'];
            $this->danfecanhoto = $aConfig['danfeCanhoto'];
            $this->danfefont = $aConfig['danfeFonte'];
            $this->danfeprinter = $aConfig['danfePrinter'];
            $this->schemeVer = $aConfig['schemes'];
            if ($aConfig['proxyIP'] != '') {
                $this->aProxy = array('IP'=>$aConfig['proxyIP'],'PORT'=>$aConfig['proxyPORT'],'USER'=>$aConfig['proxyUSER'],'PASS'=>$aConfig['proxyPASS']);
            }
            if ($aConfig['mailFROM'] != '') {
                $this->aMail = array('mailFROM'=>$aConfig['mailFROM'],'mailHOST'=>$aConfig['mailHOST'],'mailUSER'=>$aConfig['mailUSER'],'mailPASS'=>$aConfig['mailPASS'],'mailPROTOCOL'=>$aConfig['mailPROTOCOL'],'mailFROMmail'=>$aConfig['mailFROMmail'],'mailFROMname'=>$aConfig['mailFROMname'],'mailREPLYTOmail'=>$aConfig['mailREPLYTOmail'],'mailREPLYTOname'=>$aConfig['mailREPLYTOname']);
            }
        } else {

            //testa a existencia do arquivo de configuração
            if (is_file($this->raizDir.'config.php')) {
                //carrega o arquivo de configuração
                include($this->raizDir.'config.php');
                // carrega propriedades da classe com os dados de configuração
                // a sring $sAmb será utilizada para a construção dos diretorios
                // dos arquivos de operação do sistema
                $this->tpAmb=$ambiente;
                //carrega as propriedades da classe com as configurações
                $this->empName=$empresa;
                $this->UF=$UF;
                $this->cUF=$this->cUFlist[$UF];
                $this->cnpj=$cnpj;
                $this->certName=$certName;
                $this->keyPass=$keyPass;
                $this->passPhrase=$passPhrase;
                $this->arqDir = $arquivosDir;
                $this->xmlURLfile = $arquivoURLxml;
                $this->URLbase = $baseurl;
                $this->danfelogopath = $danfeLogo;
                $this->danfelogopos = $danfeLogoPos;
                $this->danfeform = $danfeFormato;
                $this->danfepaper = $danfePapel;
                $this->danfecanhoto = $danfeCanhoto;
                $this->danfefont = $danfeFonte;
                $this->danfeprinter = $danfePrinter;
                $this->schemeVer = $schemes;
                if ($proxyIP != '') {
                    $this->aProxy = array('IP'=>$proxyIP,'PORT'=>$proxyPORT,'USER'=>$proxyUSER,'PASS'=>$proxyPASS);
                }
                if ($mailFROM != '') {
                    $this->aMail = array('mailFROM'=>$mailFROM,'mailHOST'=>$mailHOST,'mailUSER'=>$mailUSER,'mailPASS'=>$mailPASS,'mailPROTOCOL'=>$mailPROTOCOL,'mailFROMmail'=>$mailFROMmail,'mailFROMname'=>$mailFROMname,'mailREPLYTOmail'=>$mailREPLYTOmail,'mailREPLYTOname'=>$mailREPLYTOname);
                }
            } else {
                // caso não exista arquivo de configuração retorna erro
                $msg = "Não foi localizado o arquivo de configuração.\n";
                if ($this->exceptions) {
                    throw new NfephpException($msg);
                }
                return false;
            }
        }
        //estabelece o ambiente
        $sAmb = ($this->tpAmb == 2) ? 'homologacao' : 'producao';
        //carrega propriedade com ano e mes ex. 200911
        $this->anoMes = date('Ym');
        //carrega o caminho para os schemas
        $this->xsdDir = $this->raizDir . 'schemes'. DIRECTORY_SEPARATOR;
        //verifica o ultimo caracter da variável $arqDir
        // se não for um DIRECTORY_SEPARATOR então colocar um
        if (substr($this->arqDir, -1, 1) != DIRECTORY_SEPARATOR) {
            $this->arqDir .= DIRECTORY_SEPARATOR;
        }
        // monta a estrutura de diretorios utilizados na manipulação das NFe
        $this->entDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'entradas' . DIRECTORY_SEPARATOR;
        $this->assDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'assinadas' . DIRECTORY_SEPARATOR;
        $this->valDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'validadas' . DIRECTORY_SEPARATOR;
        $this->rejDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'rejeitadas' . DIRECTORY_SEPARATOR;
        $this->envDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'enviadas' . DIRECTORY_SEPARATOR;
        $this->aprDir=$this->envDir . 'aprovadas' . DIRECTORY_SEPARATOR;
        $this->denDir=$this->envDir . 'denegadas' . DIRECTORY_SEPARATOR;
        $this->repDir=$this->envDir . 'reprovadas' . DIRECTORY_SEPARATOR;
        $this->canDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'canceladas' . DIRECTORY_SEPARATOR;
        $this->inuDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'inutilizadas' . DIRECTORY_SEPARATOR;
        $this->cccDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'cartacorrecao' . DIRECTORY_SEPARATOR;
        $this->evtDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'eventos' . DIRECTORY_SEPARATOR;
        $this->dpcDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'dpec' . DIRECTORY_SEPARATOR;
        $this->temDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'temporarias' . DIRECTORY_SEPARATOR;
        $this->recDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'recebidas' . DIRECTORY_SEPARATOR;
        $this->conDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'consultadas' . DIRECTORY_SEPARATOR;
        $this->pdfDir=$this->arqDir . $sAmb . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
        //monta a arvore de diretórios necessária e estabelece permissões de acesso
        if (!is_dir($this->arqDir)) {
            mkdir($this->arqDir, 0777);
        }
        if (!is_dir($this->arqDir . DIRECTORY_SEPARATOR . $sAmb)) {
            mkdir($this->arqDir . DIRECTORY_SEPARATOR . $sAmb, 0777);
        }
        if (!is_dir($this->entDir)) {
            mkdir($this->entDir, 0777);
        }
        if (!is_dir($this->assDir)) {
            mkdir($this->assDir, 0777);
        }
        if (!is_dir($this->valDir)) {
            mkdir($this->valDir, 0777);
        }
        if (!is_dir($this->rejDir)) {
            mkdir($this->rejDir, 0777);
        }
        if (!is_dir($this->envDir)) {
            mkdir($this->envDir, 0777);
        }
        if (!is_dir($this->aprDir)) {
            mkdir($this->aprDir, 0777);
        }
        if (!is_dir($this->denDir)) {
            mkdir($this->denDir, 0777);
        }
        if (!is_dir($this->repDir)) {
            mkdir($this->repDir, 0777);
        }
        if (!is_dir($this->canDir)) {
            mkdir($this->canDir, 0777);
        }
        if (!is_dir($this->inuDir)) {
            mkdir($this->inuDir, 0777);
        }
        if (!is_dir($this->cccDir)) {
            mkdir($this->cccDir, 0777);
        }
        if (!is_dir($this->evtDir)) {
            mkdir($this->evtDir, 0777);
        }
        if (!is_dir($this->dpcDir)) {
            mkdir($this->dpcDir, 0777);
        }
        if (!is_dir($this->temDir)) {
            mkdir($this->temDir, 0777);
        }
        if (!is_dir($this->recDir)) {
            mkdir($this->recDir, 0777);
        }
        if (!is_dir($this->conDir)) {
            mkdir($this->conDir, 0777);
        }
        if (!is_dir($this->pdfDir)) {
            mkdir($this->pdfDir, 0777);
        }
        //carregar uma matriz com os dados para acesso aos WebServices SEFAZ
        $this->aURL = $this->loadSEFAZ(
            $this->raizDir . $this->xmlURLfile,
            $this->tpAmb,
            $this->UF
        );

        $pk = new \library\Pkcs12\Pkcs12Certs($this->certsDir, $this->certName, $this->keyPass, $this->cnpj, true);

        //se houver erro no carregamento dos certificados passe para erro
        if (!$retorno = $pk->loadCerts()) {
            $msg = "Erro no carregamento dos certificados.";
            throw new NfephpException($msg);
        }
        //definir o timezone default para o estado do emitente
        $tz = $this->tzUFlist[$this->UF];
        date_default_timezone_set($tz);
        //estados que participam do horario de verão
        $aUFhv = array('ES','GO','MG','MS','PR','RJ','RS','SP','SC','TO');
        //corrigir o timeZone
        if ($this->UF == 'AC' ||
            $this->UF == 'AM' ||
            $this->UF == 'MT' ||
            $this->UF == 'MS' ||
            $this->UF == 'RO' ||
            $this->UF == 'RR'
        ) {
            $this->timeZone = '-04:00';
        }
        //verificar se estamos no horário de verão *** depende da configuração do servidor ***
        if (date('I') == 1) {
            //estamos no horario de verão verificar se o estado está incluso
            if (in_array($this->UF, $aUFhv)) {
                $tz = (int) $this->timeZone;
                $tz++;
                $this->timeZone = '-'.sprintf("%02d", abs($tz)).':00';//poderia ser obtido com date('P')
            }
        }//fim check horario verao
        return true;
    } //fim construct

    /**
     * addProt
     * Este método adiciona a tag do protocolo a NFe, preparando a mesma
     * para impressão e envio ao destinatário.
     * Também pode ser usada para substituir o protocolo de autorização
     * pelo protocolo de cancelamento, nesse caso apenas para a gestão interna
     * na empresa, esse arquivo com o cancelamento não deve ser enviado ao cliente.
     *
     * @name addProt
     * @param string $nfefile path completo para o arquivo contendo a NFe
     * @param string $protfile path completo para o arquivo contendo o protocolo, cancelamento ou evento de cancelamento
     * @return string Retorna a NFe com o protocolo
     */
    public function addProt($nfefile = '', $protfile = '')
    {
        try {
            if ($nfefile == '' || $protfile == '') {
                $msg = 'Para adicionar o protocolo, ambos os caminhos devem ser passados. Para a nota e para o protocolo!';
                throw new NfephpException($msg);
            }
            if (!is_file($nfefile) || !is_file($protfile)) {
                $msg = 'Algum dos arquivos não foi localizado no caminho indicado ! ' . $nfefile. ' ou ' .$protfile;
                throw new NfephpException($msg);
            }
            //carrega o arquivo na variável
            $docnfe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $docnfe->formatOutput = false;
            $docnfe->preserveWhiteSpace = false;
            $xmlnfe = file_get_contents($nfefile);
            if (!$docnfe->loadXML($xmlnfe, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $msg = 'O arquivo indicado como NFe não é um XML! ' . $nfefile;
                throw new NfephpException($msg);
            }
            $nfe = $docnfe->getElementsByTagName("NFe")->item(0);
            if (!isset($nfe)) {
                $msg = 'O arquivo indicado como NFe não é um xml de NFe! ' . $nfefile;
                throw new NfephpException($msg);
            }
            $infNFe = $docnfe->getElementsByTagName("infNFe")->item(0);
            $versao = trim($infNFe->getAttribute("versao"));
            $id = trim($infNFe->getAttribute("Id"));
            $chave = preg_replace('/[^0-9]/', '', $id);
            $DigestValue = !empty($docnfe->getElementsByTagName('DigestValue')->item(0)->nodeValue) ? $docnfe->getElementsByTagName('DigestValue')->item(0)->nodeValue : '';
            if ($DigestValue == '') {
                $msg = 'O XML da NFe não está assinado! ' . $nfefile;
                throw new NfephpException($msg);
            }
            //carrega o protocolo e seus dados
            //protocolo do lote enviado
            $prot = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $prot->formatOutput = false;
            $prot->preserveWhiteSpace = false;
            $xmlprot = file_get_contents($protfile);
            if (!$prot->loadXML($xmlprot, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $msg = 'O arquivo indicado para ser protocolado na NFe é um XML! ' . $protfile;
                throw new NfephpException($msg);
            }
            //protocolo de autorização
            $protNFe = $prot->getElementsByTagName("protNFe")->item(0);
            if (isset($protNFe)) {
                $protver     = trim($protNFe->getAttribute("versao"));
                $tpAmb       = $protNFe->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                $verAplic    = $protNFe->getElementsByTagName("verAplic")->item(0)->nodeValue;
                $chNFe       = $protNFe->getElementsByTagName("chNFe")->item(0)->nodeValue;
                $dhRecbto    = $protNFe->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
                $nProt       = $protNFe->getElementsByTagName("nProt")->item(0)->nodeValue;
                $digVal      = $protNFe->getElementsByTagName("digVal")->item(0)->nodeValue;
                $cStat       = $protNFe->getElementsByTagName("cStat")->item(0)->nodeValue;
                $xMotivo     = $protNFe->getElementsByTagName("xMotivo")->item(0)->nodeValue;
                if ($DigestValue != $digVal) {
                    $msg = 'Inconsistência! O DigestValue da NFe não combina com o do digVal do protocolo indicado!';
                    throw new NfephpException($msg);
                }
            }
            //cancelamento antigo
            $retCancNFe = $prot->getElementsByTagName("retCancNFe")->item(0);
            if (isset($retCancNFe)) {
                $protver     = trim($retCancNFe->getAttribute("versao"));
                $tpAmb       = $retCancNFe->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                $verAplic    = $retCancNFe->getElementsByTagName("verAplic")->item(0)->nodeValue;
                $chNFe       = $retCancNFe->getElementsByTagName("chNFe")->item(0)->nodeValue;
                $dhRecbto    = $retCancNFe->getElementsByTagName("dhRecbto")->item(0)->nodeValue;
                $nProt       = $retCancNFe->getElementsByTagName("nProt")->item(0)->nodeValue;
                $cStat       = $retCancNFe->getElementsByTagName("cStat")->item(0)->nodeValue;
                $xMotivo     = $retCancNFe->getElementsByTagName("xMotivo")->item(0)->nodeValue;
                $digVal      = $DigestValue;
            }
            //cancelamento por evento NOVO
            $retEvento = $prot->getElementsByTagName("retEvento")->item(0);
            if (isset($retEvento)) {
                $protver     = trim($retEvento->getAttribute("versao"));
                $tpAmb       = $retEvento->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                $verAplic    = $retEvento->getElementsByTagName("verAplic")->item(0)->nodeValue;
                $chNFe       = $retEvento->getElementsByTagName("chNFe")->item(0)->nodeValue;
                $dhRecbto    = $retEvento->getElementsByTagName("dhRegEvento")->item(0)->nodeValue;
                $nProt       = $retEvento->getElementsByTagName("nProt")->item(0)->nodeValue;
                $cStat       = $retEvento->getElementsByTagName("cStat")->item(0)->nodeValue;
                $tpEvento    = $retEvento->getElementsByTagName("tpEvento")->item(0)->nodeValue;
                $xMotivo     = $retEvento->getElementsByTagName("xMotivo")->item(0)->nodeValue;
                $digVal      = $DigestValue;
                if ($tpEvento != '110111') {
                    $msg = 'O arquivo indicado para ser anexado não é um evento de cancelamento! ' . $protfile;
                    throw new NfephpException($msg);
                }
            }
            if (!isset($protNFe) && !isset($retCancNFe) && !isset($retEvento)) {
                $msg = 'O arquivo indicado para ser protocolado a NFe não é um protocolo nem de cancelamento! ' . $protfile;
                throw new NfephpException($msg);
            }
            if ($chNFe != $chave) {
                $msg = 'O protocolo indicado pertence a outra NFe ... os numertos das chaves não combinam !';
                throw new NfephpException($msg);
            }
            //cria a NFe processada com a tag do protocolo
            $procnfe = new DOMDocument('1.0', 'utf-8');
            $procnfe->formatOutput = false;
            $procnfe->preserveWhiteSpace = false;
            //cria a tag nfeProc
            $nfeProc = $procnfe->createElement('nfeProc');
            $procnfe->appendChild($nfeProc);
            //estabele o atributo de versão
            $nfeProc_att1 = $nfeProc->appendChild($procnfe->createAttribute('versao'));
            $nfeProc_att1->appendChild($procnfe->createTextNode($protver));
            //estabelece o atributo xmlns
            $nfeProc_att2 = $nfeProc->appendChild($procnfe->createAttribute('xmlns'));
            $nfeProc_att2->appendChild($procnfe->createTextNode($this->URLnfe));
            //inclui a tag NFe
            $node = $procnfe->importNode($nfe, true);
            $nfeProc->appendChild($node);
            //cria tag protNFe
            $protNFe = $procnfe->createElement('protNFe');
            $nfeProc->appendChild($protNFe);
            //estabele o atributo de versão
            $protNFe_att1 = $protNFe->appendChild($procnfe->createAttribute('versao'));
            $protNFe_att1->appendChild($procnfe->createTextNode($versao));
            //cria tag infProt
            $infProt = $procnfe->createElement('infProt');
            $infProt_att1 = $infProt->appendChild($procnfe->createAttribute('Id'));
            $infProt_att1->appendChild($procnfe->createTextNode('ID'.$nProt));
            $protNFe->appendChild($infProt);
            $infProt->appendChild($procnfe->createElement('tpAmb', $tpAmb));
            $infProt->appendChild($procnfe->createElement('verAplic', $verAplic));
            $infProt->appendChild($procnfe->createElement('chNFe', $chNFe));
            $infProt->appendChild($procnfe->createElement('dhRecbto', $dhRecbto));
            $infProt->appendChild($procnfe->createElement('nProt', $nProt));
            $infProt->appendChild($procnfe->createElement('digVal', $digVal));
            $infProt->appendChild($procnfe->createElement('cStat', $cStat));
            $infProt->appendChild($procnfe->createElement('xMotivo', $xMotivo));
            //salva o xml como string em uma variável
            $procXML = $procnfe->saveXML();
            //remove as informações indesejadas
            $procXML = str_replace('default:', '', $procXML);
            $procXML = str_replace(':default', '', $procXML);
            $procXML = str_replace("\n", '', $procXML);
            $procXML = str_replace("\r", '', $procXML);
            $procXML = str_replace("\s", '', $procXML);
            $procXML = str_replace('NFe xmlns="http://www.portalfiscal.inf.br/nfe" xmlns="http://www.w3.org/2000/09/xmldsig#"', 'NFe xmlns="http://www.portalfiscal.inf.br/nfe"', $procXML);
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return $procXML;
    } //fim addProt

    /**
     * addB2B
     * Adiciona o xml referente a comunicação B2B à NFe, conforme padrão ANFAVEA+GS1
     *
     * @param string $nfefile path para o arquivo com a nfe protocolada e autorizada
     * @param string $b2bfile path para o arquivo xml padrão ANFAVEA+GS1 e NT2013_002
     * @param string $tagB2B Tag principar do xml B2B pode ser NFeB2B ou NFeB2BFin
     * @return mixed FALSE se houve erro ou xml com a nfe+b2b
     */
    public function addB2B($nfefile = '', $b2bfile = '', $tagB2B = '')
    {
        try {
            if ($nfefile == '' || $b2bfile == '') {
                $msg = 'Para adicionar o arquivo B2B, ambos os caminhos devem ser passados. Para a nota e para o B2B!';
                throw new NfephpException($msg);
            }
            if (!is_file($nfefile) || !is_file($b2bfile)) {
                $msg = 'Algum dos arquivos não foi localizado no caminho indicado ! ' . $nfefile. ' ou ' .$b2bfile;
                throw new NfephpException($msg);
            }
            if ($tagB2B == '') {
                $tagB2B = 'NFeB2BFin'; //padrão anfavea
            }
            //carrega o arquivo na variável
            $docnfe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $docnfe->formatOutput = false;
            $docnfe->preserveWhiteSpace = false;
            $xmlnfe = file_get_contents($nfefile);
            if (!$docnfe->loadXML($xmlnfe, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $msg = 'O arquivo indicado como NFe não é um XML! ' . $nfefile;
                throw new NfephpException($msg);
            }
            $nfeProc = $docnfe->getElementsByTagName("nfeProc")->item(0);
            if (!isset($nfeProc)) {
                $msg = 'O arquivo indicado como NFe não é um xml de NFe ou não contêm o protocolo! ' . $nfefile;
                throw new NfephpException($msg);
            }
            $infNFe = $docnfe->getElementsByTagName("infNFe")->item(0);
            $versao = trim($infNFe->getAttribute("versao"));
            $id = trim($infNFe->getAttribute("Id"));
            $chave = preg_replace('/[^0-9]/', '', $id);
            //carrega o arquivo B2B e seus dados
            //protocolo do lote enviado
            $b2b = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $b2b->formatOutput = false;
            $b2b->preserveWhiteSpace = false;
            $xmlb2b = file_get_contents($b2bfile);
            if (!$b2b->loadXML($xmlb2b, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
                $msg = 'O arquivo indicado como Protocolo não é um XML! ' . $protfile;
                throw new NfephpException($msg);
            }
            $NFeB2BFin = $b2b->getElementsByTagName($tagB2B)->item(0);
            if (!isset($NFeB2BFin)) {
                $msg = 'O arquivo indicado como B2B não é um XML de B2B! ' . $b2bfile;
                throw new NfephpException($msg);
            }
            //cria a NFe processada com a tag do protocolo
            $procb2b = new DOMDocument('1.0', 'utf-8');
            $procb2b->formatOutput = false;
            $procb2b->preserveWhiteSpace = false;
            //cria a tag nfeProc
            $nfeProcB2B = $procb2b->createElement('nfeProcB2B');
            $procb2b->appendChild($nfeProcB2B);
            //inclui a tag NFe
            $node = $procb2b->importNode($nfeProc, true);
            $nfeProcB2B->appendChild($node);
            //inclui a tag NFeB2BFin
            $node = $procb2b->importNode($NFeB2BFin, true);
            $nfeProcB2B->appendChild($node);
            //salva o xml como string em uma variável
            $nfeb2bXML = $procb2b->saveXML();
            //remove as informações indesejadas
            $nfeb2bXML = str_replace("\n", '', $nfeb2bXML);
            $nfeb2bXML = str_replace("\r", '', $nfeb2bXML);
            $nfeb2bXML = str_replace("\s", '', $nfeb2bXML);
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return $nfeb2bXML;
    }//fim addB2B

    /**
     * soapStatusService
     * Verifica o status do servico da SEFAZ
     *
     * $this->cStat = 107 OK
     *        cStat = 108 sitema paralizado momentaneamente, aguardar retorno
     *        cStat = 109 sistema parado sem previsao de retorno, verificar status SCAN
     *        cStat = 113 SCAN operando mas irá parar use o serviço Normal
     *        cStat = 114 SCAN dasativado pela SEFAZ de origem
     * se SCAN estiver ativado usar, caso contrario aguardar pacientemente.
     * @name soapStatusService
     * @param	string $UF sigla da unidade da Federação
     * @param   integer $tpAmb tipo de ambiente 1-produção e 2-homologação
     * @param   integer 1 usa o nfeSOAP e 2 usa o curlSOAP
     * @param  array $aRetorno parametro passado por referencia irá conter a resposta da consulta em um array
     * @return	mixed false ou array ['bStat'=>boolean,'cStat'=>107,'tMed'=>1,'dhRecbto'=>'12/12/2009','xMotivo'=>'Serviço em operação','xObs'=>'']
     */
    public function soapStatusService($UF = '', $tpAmb = '', $modSOAP = '2', &$aRetorno = '')
    {
        try {
            //retorno da funçao
            $aRetorno = array('bStat'=>false,'tpAmb'=>'','verAplic'=>'','cUF'=>'','cStat'=>'','tMed'=>'','dhRetorno'=>'','dhRecbto'=>'','xMotivo'=>'','xObs'=>'');
            // caso o parametro tpAmb seja vazio
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            // caso a sigla do estado esteja vazia
            if ($UF =='') {
                $UF = $this->UF;
            }
            //busca o cUF
            $cUF = $this->cUFlist[$UF];
            //verifica se o SCAN esta habilitado
            if (!$this->enableSCAN) {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile, $tpAmb, $UF);
            } else {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile, $tpAmb, 'SCAN');
            }
            //identificação do serviço
            $servico = 'NfeStatusServico';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
            //montagem do cabeçalho da comunicação SOAP
            $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
            //montagem dos dados da mensagem SOAP
            $dados = '<nfeDadosMsg xmlns="'. $namespace . '"><consStatServ xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><tpAmb>'.$tpAmb.'</tpAmb><cUF>'.$cUF.'</cUF><xServ>STATUS</xServ></consStatServ></nfeDadosMsg>';
            if ($modSOAP == '2') {
                $oSoap = new \library\Soap\CurlSoap($this->priKEY, $this->pubKEY, $this->timeout);
                $retorno = $oSoap->send($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $oSoap = new \library\Soap\NatSoap($this->pubKEY, $this->priKEY, $this->certKEY, $pathWsdl, $thid->timeout);
                $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $UF);
            }
            //verifica o retorno do SOAP
            if ($retorno) {
                //tratar dados de retorno
                $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
                $doc->formatOutput = false;
                $doc->preserveWhiteSpace = false;
                $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                if ($cStat == '') {
                    $msg = "Não houve retorno Soap verifique a mensagem de erro e o debug!!";
                    throw new NfephpException($msg);
                } else {
                    if ($cStat == '107') {
                        $aRetorno['bStat'] = true;
                    }
                }
                // tipo de ambiente
                $aRetorno['tpAmb'] = $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
                // versão do aplicativo
                $aRetorno['verAplic'] = $doc->getElementsByTagName('verAplic')->item(0)->nodeValue;
                // Código da UF que atendeu a solicitação
                $aRetorno['cUF'] = $doc->getElementsByTagName('cUF')->item(0)->nodeValue;
                // status do serviço
                $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
                // tempo medio de resposta
                $aRetorno['tMed'] = $doc->getElementsByTagName('tMed')->item(0)->nodeValue;
                 // data e hora do retorno a operação (opcional)
                $aRetorno['dhRetorno'] = !empty($doc->getElementsByTagName('dhRetorno')->item(0)->nodeValue) ? date("d/m/Y H:i:s",$this->convertTime($doc->getElementsByTagName('dhRetorno')->item(0)->nodeValue)) : '';
                // data e hora da mensagem (opcional)
                $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i:s",$this->convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
                // motivo da resposta (opcional)
                $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                // obervaçoes (opcional)
                $aRetorno['xObs'] = !empty($doc->getElementsByTagName('xObs')->item(0)->nodeValue) ? $doc->getElementsByTagName('xObs')->item(0)->nodeValue : '';
            } else {
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return $aRetorno; //irá mudar para o xml passado pelo sefaz
    } //fim statusServico

    /**
     * soapConsCad
     * Solicita dados de situaçao de Cadastro, somente funciona para
     * cadastros de empresas localizadas no mesmo estado do solicitante e os dados
     * retornados podem ser bastante incompletos. Não é recomendado seu uso.
     *
     * @name soapConsCad
     * @param	string  $UF sigla da unidade da federação
     * @param   string  $IE opcional numero da inscrição estadual
     * @param   string  $CNPJ opcional numero do cnpj
     * @param   string  $CPF opcional numero do cpf
     * @param   string  $tpAmb tipo de ambiente se não informado será usado o ambiente default
     * @param   integer $modSOAP    1 usa nfeSOAP e 2 usa curlSOAP
     * @return	mixed false se falha ou array se retornada informação
     */
    public function soapConsCad($UF, $CNPJ = '', $IE = '', $CPF = '', $tpAmb = '', $modSOAP = '2')
    {
        //variavel de retorno do metodo
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dados'=>array());
        $flagIE = false;
        $flagCNPJ = false;
        $flagCPF = false;
        $marca = '';
        //selecionar o criterio de filtragem CNPJ ou IE ou CPF
        if ($CNPJ != '') {
            $flagCNPJ = true;
            $marca = 'CNPJ-'.$CNPJ;
            $filtro = "<CNPJ>".$CNPJ."</CNPJ>";
            $CPF = '';
            $IE = '';
        }
        if ($IE != '') {
            $flagIE = true;
            $marca = 'IE-'.$IE;
            $filtro = "<IE>".$IE."</IE>";
            $CNPJ = '';
            $CPF = '';
        }
        if ($CPF != '') {
            $flagCPF = true;
            $filtro = "<CPF>".$CPF."</CPF>";
            $marca = 'CPF-'.$CPF;
            $CNPJ = '';
            $IE = '';
        }
        //se nenhum critério é satisfeito
        if (!($flagIE || $flagCNPJ || $flagCPF)) {
            //erro nao foi passado parametro de filtragem
            $msg = "Pelo menos uma e somente uma opção deve ser indicada CNPJ, CPF ou IE !!!";
            $this->setError($msg);
            if ($this->exceptions) {
                throw new NfephpException($msg);
            }
            return false;
        }
        if ($tpAmb == '') {
            $tpAmb = $this->tpAmb;
        }
        //carrega as URLs
        $aURL = $this->aURL;
        // caso a sigla do estado seja diferente do emitente ou o ambiente seja diferente
        if ($UF != $this->UF || $tpAmb != $this->tpAmb) {
            //recarrega as url referentes aos dados passados como parametros para a função
            $aURL = $this->loadSEFAZ($this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile, $tpAmb, $UF);
        }
        //busca o cUF
        $cUF = $this->cUFlist[$UF];
        //identificação do serviço
        $servico = 'CadConsultaCadastro';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
        if ($urlservico=='') {
            $msg = "Este serviço não está disponível para a SEFAZ $UF!!!";
            $this->setError($msg);
            throw new NfephpException($msg);
            return false;
        }
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem dos dados da mensagem SOAP
        $dados = '<nfeDadosMsg xmlns="'. $namespace . '"><ConsCad xmlns="'.$this->URLnfe.'" versao="'.$versao.'"><infCons><xServ>CONS-CAD</xServ><UF>'.$UF.'</UF>'.$filtro.'</infCons></ConsCad></nfeDadosMsg>';
        //envia a solicitação via SOAP
        if ($modSOAP == 2) {
            $retorno = $this->curlSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
        } else {
            $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $UF);
        }
        //verifica o retorno
        if (!$retorno) {
            //não houve retorno
            $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
            $this->setError($msg);
            throw new NfephpException($msg);
            return false;
        }
        //tratar dados de retorno
        $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $infCons = $doc->getElementsByTagName('infCons')->item(0);
        $cStat = !empty($infCons->getElementsByTagName('cStat')->item(0)->nodeValue) ? $infCons->getElementsByTagName('cStat')->item(0)->nodeValue : '';
        $xMotivo = !empty($infCons->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $infCons->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
        $infCad = $infCons->getElementsByTagName('infCad');
        if ($cStat == '') {
            //houve erro
            $msg = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
            $this->setError($msg);
            if ($this->exceptions) {
                throw new NfephpException($msg);
            }
            return false;
        }
        //tratar erro 239 Versão do arquivo XML não suportada
        if ($cStat == '239') {
            $this->solveVersionErr($retorno, $this->UF, $tpAmb, $servico, $versao);
            $msg = "Versão do arquivo XML não suportada!!";
            $this->setError($msg);
            if ($this->exceptions) {
                throw new NfephpException($msg);
            }
            return false;
        }
        if ($cStat <> '111') {
            $msg = "Retorno de ERRO: $cStat - $xMotivo";
            $this->setError($msg);
            if ($this->exceptions) {
                throw new NfephpException($msg);
            }
            return false;
        }

        if (isset($infCad)) {
            $aRetorno['bStat'] = true;
            //existem dados do cadastro e podem ser multiplos
            $i =0;
            foreach ($infCad as $dCad) {
                $ender = $dCad->getElementsByTagName('ender')->item(0);
                $aCad[$i]['CNPJ'] = !empty($dCad->getElementsByTagName('CNPJ')->item(0)->nodeValue) ? $dCad->getElementsByTagName('CNPJ')->item(0)->nodeValue : '';
                $aCad[$i]['IE'] = !empty($dCad->getElementsByTagName('IE')->item(0)->nodeValue) ? $dCad->getElementsByTagName('IE')->item(0)->nodeValue : '';
                $aCad[$i]['UF'] = !empty($dCad->getElementsByTagName('UF')->item(0)->nodeValue) ? $dCad->getElementsByTagName('UF')->item(0)->nodeValue : '';
                $aCad[$i]['cSit'] = !empty($dCad->getElementsByTagName('cSit')->item(0)->nodeValue) ? $dCad->getElementsByTagName('cSit')->item(0)->nodeValue : '';
                $aCad[$i]['indCredNFe'] = !empty($dCad->getElementsByTagName('indCredNFe')->item(0)->nodeValue) ? $dCad->getElementsByTagName('indCredNFe')->item(0)->nodeValue : '';
                $aCad[$i]['indCredCTe'] = !empty($dCad->getElementsByTagName('indCredCTe')->item(0)->nodeValue) ? $dCad->getElementsByTagName('indCredCTe')->item(0)->nodeValue : '';
                $aCad[$i]['xNome'] = !empty($dCad->getElementsByTagName('xNome')->item(0)->nodeValue) ? $dCad->getElementsByTagName('xNome')->item(0)->nodeValue : '';
                $aCad[$i]['xRegApur'] = !empty($dCad->getElementsByTagName('xRegApur')->item(0)->nodeValue) ? $dCad->getElementsByTagName('xRegApur')->item(0)->nodeValue : '';
                $aCad[$i]['CNAE'] = !empty($dCad->getElementsByTagName('CNAE')->item($i)->nodeValue) ? $dCad->getElementsByTagName('CNAE')->item($i)->nodeValue : '';
                $aCad[$i]['dIniAtiv'] = !empty($dCad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue) ? $dCad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue : '';
                $aCad[$i]['dUltSit'] = !empty($dCad->getElementsByTagName('dUltSit')->item(0)->nodeValue) ? $dCad->getElementsByTagName('dUltSit')->item(0)->nodeValue : '';
                if (isset($ender)) {
                    $aCad[$i]['xLgr'] = !empty($ender->getElementsByTagName('xLgr')->item(0)->nodeValue) ? $ender->getElementsByTagName('xLgr')->item(0)->nodeValue : '';
                    $aCad[$i]['nro'] = !empty($ender->getElementsByTagName('nro')->item(0)->nodeValue) ? $ender->getElementsByTagName('nro')->item(0)->nodeValue : '';
                    $aCad[$i]['xCpl'] = !empty($ender->getElementsByTagName('xCpl')->item(0)->nodeValue) ? $ender->getElementsByTagName('xCpl')->item(0)->nodeValue : '';
                    $aCad[$i]['xBairro'] = !empty($ender->getElementsByTagName('xBairro')->item(0)->nodeValue) ? $ender->getElementsByTagName('xBairro')->item(0)->nodeValue : '';
                    $aCad[$i]['cMun'] = !empty($ender->getElementsByTagName('cMun')->item(0)->nodeValue) ? $ender->getElementsByTagName('cMun')->item(0)->nodeValue : '';
                    $aCad[$i]['xMun'] = !empty($ender->getElementsByTagName('xMun')->item(0)->nodeValue) ? $ender->getElementsByTagName('xMun')->item(0)->nodeValue : '';
                    $aCad[$i]['CEP'] = !empty($ender->getElementsByTagName('CEP')->item(0)->nodeValue) ? $ender->getElementsByTagName('CEP')->item(0)->nodeValue : '';
                }
                $i++;
            } //fim foreach
        }
        $aRetorno['cStat'] = $cStat;
        $aRetorno['xMotivo'] = $xMotivo;
        $aRetorno['dados'] = $aCad;
        return $aRetorno;
    } //fim consultaCadastro

    /**
     * soapEnvLot
     * Envia lote de Notas Fiscais para a SEFAZ.
     * Este método pode enviar uma ou mais NFe para o SEFAZ, desde que,
     * o tamanho do arquivo de envio não ultrapasse 500kBytes
     * Este processo enviará somente até 50 NFe em cada Lote
     *
     * @name sendLot
     * @param	mixed    $mNFe string com uma nota fiscal em xml ou um array com as NFe em xml, uma em cada campo do array unidimensional MAX 50
     * @param   integer $idLote     id do lote e um numero que deve ser gerado pelo sistema
     *                          a cada envio mesmo que seja de apenas uma NFe
     * @param   integer $modSOAP 1 usa sendSOP e 2 usa curlSOAP
     * @return	mixed	false ou array ['bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'','tMed'=>'','tpAmb'=>'','verAplic'=>'','cUF'=>'']
     * @todo Incluir regra de validação para ambiente de homologação/produção vide NT2011.002
     */
    public function soapEnvLot($mNFe, $idLote, $modSOAP = '2')
    {
        //variavel de retorno do metodo
        $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','dhRecbto'=>'','nRec'=>'','tMed'=>'','tpAmb'=>'','verAplic'=>'','cUF'=>'');
        //verifica se o SCAN esta habilitado
        if (!$this->enableSCAN) {
            $aURL = $this->aURL;
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile, $this->tpAmb, 'SCAN');
        }
        //identificação do serviço
        $servico = 'NfeRecepcao';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
        // limpa a variavel
        $sNFe = '';
        if (is_array($mNFe)) {
            // verificar se foram passadas até 50 NFe
            if (count($mNFe) > 50) {
                $msg = "No maximo 50 NFe devem compor um lote de envio!!";
                $this->setError($msg);
                throw new NfephpException($msg);
                return false;
            }
            // monta string com todas as NFe enviadas no array
            $sNFe = implode('', $mNFe);
        } else {
            $sNFe = $mNFe;
        }
        //remover <?xml version="1.0" encoding=... das NFe pois somente uma dessas tags pode exitir na mensagem
        $sNFe = str_replace(array('<?xml version="1.0" encoding="utf-8"?>','<?xml version="1.0" encoding="UTF-8"?>'), '', $sNFe);
        $sNFe = str_replace(array("\r","\n","\s"), '', $sNFe);
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'.$namespace.'"><cUF>'.$this->cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem dos dados da mensagem SOAP
        $dados = '<nfeDadosMsg xmlns="'.$namespace.'"><enviNFe xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><idLote>'.$idLote.'</idLote>'.$sNFe.'</enviNFe></nfeDadosMsg>';
        //envia dados via SOAP
        if ($modSOAP == '2') {
            $retorno = $this->curlSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb, $this->UF);
        }
        //verifica o retorno
        if ($retorno) {
            //tratar dados de retorno
            $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            if ($cStat == '') {
                //houve erro
                $msg = "O retorno não contêm cStat verifique o debug do soap !!";
                $this->setError($msg);
                throw new NfephpException($msg);
                return false;
            } else {
                if ($cStat == '103') {
                    $aRetorno['bStat'] = true;
                }
            }
            // status do serviço
            $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
            // motivo da resposta (opcional)
            $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            // data e hora da mensagem (opcional)
            $aRetorno['dhRecbto'] = !empty($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue) ? date("d/m/Y H:i:s",$this->convertTime($doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue)) : '';
            // numero do recibo do lote enviado (opcional)
            $aRetorno['nRec'] = !empty($doc->getElementsByTagName('nRec')->item(0)->nodeValue) ? $doc->getElementsByTagName('nRec')->item(0)->nodeValue : '';
            //outras informações
            $aRetorno['tMed'] = !empty($doc->getElementsByTagName('tMed')->item(0)->nodeValue) ? $doc->getElementsByTagName('tMed')->item(0)->nodeValue : '';
            $aRetorno['tpAmb'] = !empty($doc->getElementsByTagName('tpAmb')->item(0)->nodeValue) ? $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue : '';
            $aRetorno['verAplic'] = !empty($doc->getElementsByTagName('verAplic')->item(0)->nodeValue) ? $doc->getElementsByTagName('verAplic')->item(0)->nodeValue : '';
            $aRetorno['cUF'] = !empty($doc->getElementsByTagName('cUF')->item(0)->nodeValue) ? $doc->getElementsByTagName('cUF')->item(0)->nodeValue : '';
            //gravar o retorno na pasta temp
            $nome = $this->temDir.$idLote.'-rec.xml';
            $nome = $doc->save($nome);
        } else {
            $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
            $this->setError($msg);
            throw new NfephpException($msg);
            $aRetorno = false;
        }
        return $aRetorno;
    }// fim sendLot


    /**
     * getProtocol
     * Solicita resposta do lote de Notas Fiscais ou o protocolo de
     * autorização da NFe
     * Caso $this->cStat == 105 Tentar novamente mais tarde
     *
     * @name getProtocol
     * @param	string   $recibo numero do recibo do envio do lote
     * @param	string   $chave  numero da chave da NFe de 44 digitos
     * @param   string   $tpAmb  numero do ambiente 1-producao e 2-homologação
     * @param   integer  $modSOAP 1 usa nfeSOAP e 2 usa curlSOAP
     * @param   array    $aRetorno Array com os dados do protocolo
     * @return	mixed    false ou xml
     */
    public function soapGetProtocol($recibo = '', $chave = '', $tpAmb = '', $modSOAP = '2', &$aRetorno = '')
    {
        try {
            //carrega defaults
            $i = 0;
            $aRetorno = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','aProt'=>'','aCanc'=>'');
            $cUF = $this->cUF;
            $UF = $this->UF;
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if ($tpAmb != '1' && $tpAmb != '2') {
                $tpAmb = '2';
            }
            $aURL = $this->aURL;
            $ctpEmissao = '';
            //verifica se a chave foi passada
            if ($chave != '') {
                //se sim extrair o cUF da chave
                $cUF = substr($chave, 0, 2);
                $ctpEmissao = substr($chave, 34, 1);
                //testar para ver se é o mesmo do emitente
                if ($cUF != $this->cUF || $tpAmb != $this->tpAmb) {
                    //se não for o mesmo carregar a sigla
                    $UF = $this->UFList[$cUF];
                    //recarrega as url referentes aos dados passados como parametros para a função
                    $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$UF);
                }
            }
            //verifica se o SCAN esta habilitado
            if ($this->enableSCAN || $ctpEmissao == '3') {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'SCAN');
            }
            if ($recibo == '' && $chave == '') {
                $msg = "ERRO. Favor indicar o numero do recibo ou a chave de acesso da NFe!!";
                throw new NfephpException($msg);
            }
            if ($recibo != '' && $chave != '') {
                $msg = "ERRO. Favor indicar somente um dos dois dados ou o numero do recibo ou a chave de acesso da NFe!!";
                throw new NfephpException($msg);
            }
            //consulta pelo recibo
            if ($recibo != '' && $chave == '') {
                //buscar os protocolos pelo numero do recibo do lote
                //identificação do serviço
                $servico = 'NfeRetRecepcao';
                //recuperação da versão
                $versao = $aURL[$servico]['version'];
                //recuperação da url do serviço
                $urlservico = $aURL[$servico]['URL'];
                //recuperação do método
                $metodo = $aURL[$servico]['method'];
                //montagem do namespace do serviço
                $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
                //montagem do cabeçalho da comunicação SOAP
                $cabec = '<nfeCabecMsg xmlns="'.$namespace.'"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
                //montagem dos dados da mensagem SOAP
                $dados = '<nfeDadosMsg xmlns="'.$namespace.'"><consReciNFe xmlns="'.$this->URLPortal.'" versao="'. $versao.'"><tpAmb>'. $tpAmb.'</tpAmb><nRec>'.$recibo .'</nRec></consReciNFe></nfeDadosMsg>';
                //nome do arquivo
                $nomeArq = $recibo.'-protrec.xml';
            }
            //consulta pela chave
            if ($recibo == '' &&  $chave != '') {
                //buscar o protocolo pelo numero da chave de acesso
                //identificação do serviço
                $servico = 'NfeConsulta';
                //recuperação da versão
                $versao = $aURL[$servico]['version'];
                //recuperação da url do serviço
                $urlservico = $aURL[$servico]['URL'];
                //recuperação do método
                $metodo = $aURL[$servico]['method'];
                //montagem do namespace do serviço
                $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
                //montagem do cabeçalho da comunicação SOAP
                $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
                //montagem dos dados da mensagem SOAP
                $dados = '<nfeDadosMsg xmlns="'.$namespace.'"><consSitNFe xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><tpAmb>'.$tpAmb.'</tpAmb><xServ>CONSULTAR</xServ><chNFe>'.$chave .'</chNFe></consSitNFe></nfeDadosMsg>';
            }
            //envia a solicitação via SOAP
            if ($modSOAP == 2) {
                $retorno = $this->curlSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $UF);
            }
            //verifica o retorno
            if ($retorno) {
                //tratar dados de retorno
                $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
                $doc->formatOutput = false;
                $doc->preserveWhiteSpace = false;
                $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                if ($cStat == '') {
                    //houve erro
                    $msg = "Erro cStat está vazio.";
                    throw new NfephpException($msg);
                }
                //o retorno vai variar se for buscado o protocolo ou recibo
                //Retorno nda consulta pela Chave da NFe
                //retConsSitNFe 100 aceita 110 denegada 101 cancelada ou outro recusada
                // cStat xMotivo cUF chNFe protNFe retCancNFe
                if ($chave != '') {
                    $aRetorno['bStat'] = true;
                    $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
                    $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                    $infProt = $doc->getElementsByTagName('infProt')->item(0);
                    $infCanc = $doc->getElementsByTagName('infCanc')->item(0);
                    $procEventoNFe = $doc->getElementsByTagName('procEventoNFe');
                    $aProt = '';
                    if (isset($infProt)) {
                        foreach ($infProt->childNodes as $t) {
                            $aProt[$t->nodeName] = $t->nodeValue;
                        }
                        $aProt['dhRecbto'] = !empty($aProt['dhRecbto']) ? date("d/m/Y H:i:s",$this->convertTime($aProt['dhRecbto'])) : '';
                    }
                    $aCanc = '';
                    if (isset($infCanc)) {
                        foreach ($infCanc->childNodes as $t) {
                            $aCanc[$t->nodeName] = $t->nodeValue;
                        }
                        $aCanc['dhRecbto'] = !empty($aCanc['dhRecbto']) ? date("d/m/Y H:i:s",$this->convertTime($aCanc['dhRecbto'])) : '';
                    }
                    $aEventos = '';
                    if (!empty( $procEventoNFe )) {
                        foreach ($procEventoNFe as $i => $evento) {
                            $infEvento = $evento->getElementsByTagName('infEvento')->item(0);
                            foreach ($infEvento->childNodes as $t) {
                                if ('detEvento' == $t->nodeName) {
                                    foreach ($t->childNodes as $t2) {
                                        $aEventos[$i][$t->nodeName][$t2->nodeName] = $t2->nodeValue;
                                    }
                                    continue;
                                }
                                $aEventos[$i][$t->nodeName] = $t->nodeValue;
                            }
                            $aEventos[$i]['id'] = $infEvento->getAttribute('Id');
                        }
                    }
                    $aRetorno['aProt'] = $aProt;
                    $aRetorno['aCanc'] = $aCanc;
                    $aRetorno['aEventos'] = $aEventos;
                    //gravar o retorno na pasta temp apenas se a nota foi aprovada ou denegada
                    if ( $aRetorno['cStat'] == 100 || $aRetorno['cStat'] == 101 || $aRetorno['cStat'] == 110 || $aRetorno['cStat'] == 301 || $aRetorno['cStat'] == 302 ){
                        //nome do arquivo
                        $nomeArq = $chave.'-prot.xml';
                        $nome = $this->temDir.$nomeArq;
                        $nome = $doc->save($nome);
                    }
                }
                //Retorno da consulta pelo recibo
                //NFeRetRecepcao 104 tem retornos
                //nRec cStat xMotivo cUF cMsg xMsg protNfe* infProt chNFe dhRecbto nProt cStat xMotivo
                if ($recibo != '') {
                    $aRetorno['bStat'] = true;
                    // status do serviço
                    $aRetorno['cStat'] = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
                    // motivo da resposta (opcional)
                    $aRetorno['xMotivo'] = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                    // numero do recibo consultado
                    $aRetorno['nRec'] = !empty($doc->getElementsByTagName('nRec')->item(0)->nodeValue) ? $doc->getElementsByTagName('nRec')->item(0)->nodeValue : '';
                    // tipo de ambiente
                    $aRetorno['tpAmb'] = !empty($doc->getElementsByTagName('tpAmb')->item(0)->nodeValue) ? $doc->getElementsByTagName('tpAmb')->item(0)->nodeValue : '';
                    // versao do aplicativo que recebeu a consulta
                    $aRetorno['verAplic'] = !empty($doc->getElementsByTagName('verAplic')->item(0)->nodeValue) ? $doc->getElementsByTagName('verAplic')->item(0)->nodeValue : '';
                    // codigo da UF que atendeu a solicitacao
                    $aRetorno['cUF'] = !empty($doc->getElementsByTagName('cUF')->item(0)->nodeValue) ? $doc->getElementsByTagName('cUF')->item(0)->nodeValue : '';
                    // codigo da mensagem da SEFAZ para o emissor (opcional)
                    $aRetorno['cMsg'] = !empty($doc->getElementsByTagName('cMsg')->item(0)->nodeValue) ? $doc->getElementsByTagName('cMsg')->item(0)->nodeValue : '';
                    // texto da mensagem da SEFAZ para o emissor (opcional)
                    $aRetorno['xMsg'] = !empty($doc->getElementsByTagName('xMsg')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMsg')->item(0)->nodeValue : '';
                    if ($cStat == '104') {
                        //aqui podem ter varios retornos dependendo do numero de NFe enviadas no Lote e já processadas
                        $protNfe = $doc->getElementsByTagName('protNFe');
                        foreach ($protNfe as $d) {
                            $infProt = $d->getElementsByTagName('infProt')->item(0);
                            $protcStat = $infProt->getElementsByTagName('cStat')->item(0)->nodeValue;//cStat
                            //pegar os dados do protolo para retornar
                            foreach ($infProt->childNodes as $t) {
                                $aProt[$i][$t->nodeName] = $t->nodeValue;
                            }
                            $i++; //incluido increment para controlador de indice do array
                            //salvar o protocolo somente se a nota estiver approvada ou denegada
                            if ($protcStat == 100 || $protcStat == 110 || $protcStat == 301 || $protcStat == 302) {
                                $nomeprot = $this->temDir.$infProt->getElementsByTagName('chNFe')->item(0)->nodeValue.'-prot.xml';//id da nfe
                                //salvar o protocolo em arquivo
                                $novoprot = new DOMDocument('1.0', 'UTF-8');
                                $novoprot->formatOutput = true;
                                $novoprot->preserveWhiteSpace = false;
                                $pNFe = $novoprot->createElement("protNFe");
                                $pNFe->setAttribute("versao", "2.00");
                                // Importa o node e todo o seu conteudo
                                $node = $novoprot->importNode($infProt, true);
                                // acrescenta ao node principal
                                $pNFe->appendChild($node);
                                $novoprot->appendChild($pNFe);
                                $xml = $novoprot->saveXML();
                                $xml = str_replace('<?xml version="1.0" encoding="UTF-8  standalone="no"?>', '<?xml version="1.0" encoding="UTF-8"?>', $xml);
                                $xml = str_replace(array("default:",":default"), "", $xml);
                                $xml = str_replace("\n", "", $xml);
                                $xml = str_replace("  ", " ", $xml);
                                $xml = str_replace("  ", " ", $xml);
                                $xml = str_replace("  ", " ", $xml);
                                $xml = str_replace("  ", " ", $xml);
                                $xml = str_replace("  ", " ", $xml);
                                $xml = str_replace("> <", "><", $xml);
                                file_put_contents($nomeprot, $xml);
                            } //fim protcSat
                        } //fim foreach
                    }//fim cStat
                    //converter o horário do recebimento retornado pela SEFAZ em formato padrão
                    if (isset($aProt)) {
                        foreach ($aProt as &$p) {
                            $p['dhRecbto'] = !empty($p['dhRecbto']) ? date("d/m/Y H:i:s", $this->convertTime($p['dhRecbto'])) : '';
                        }
                    } else {
                        $aProt = array();
                    }
                    $aRetorno['aProt'] = $aProt; //passa o valor de $aProt para o array de retorno
                    $nomeArq = $recibo.'-recprot.xml';
                    $nome = $this->temDir.$nomeArq;
                    $nome = $doc->save($nome);
                } //fim recibo
            } else {
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            } //fim retorno
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }//fim catch
        return $aRetorno; //mudar para $retorno
    } //fim getProtocol

    /**
     * getListNFe
     * Consulta da Relação de Documentos Destinados
     * para um determinado CNPJ de destinatário informado na NF-e.
     *
     * Este serviço não suporta SCAN !!!
     *
     * @name getListNFe
     * @param boolean $AN TRUE - usa ambiente Nacional para buscar a lista de NFe, FALSE usa sua própria SEFAZ
     * @param string $indNFe Indicador de NF-e consultada: 0=Todas as NF-e; 1=Somente as NF-e que ainda não tiveram manifestação do destinatário (Desconhecimento da operação, Operação não Realizada ou Confirmação da Operação); 2=Idem anterior, incluindo as NF-e que também não tiveram a Ciência da Operação
     * @param string $indEmi Indicador do Emissor da NF-e: 0=Todos os Emitentes / Remetentes; 1=Somente as NF-e emitidas por emissores / remetentes que não tenham a mesma raiz do CNPJ do destinatário (para excluir as notas fiscais de transferência entre filiais).
     * @param string $ultNSU Último NSU recebido pela Empresa. Caso seja informado com zero, ou com um NSU muito antigo, a consulta retornará unicamente as notas fiscais que tenham sido recepcionadas nos últimos 15 dias.
     * @param string $tpAmb Tipo de ambiente 1=Produção 2=Homologação
     * @param string $modSOAP
     * @param array $resp Array com os retornos parametro passado por REFRENCIA
     * @return mixed False ou xml com os dados
     */
    public function soapGetListNFe($AN = false, $indNFe = '0', $indEmi = '0', $ultNSU = '', $tpAmb = '', $modSOAP = '2', &$resp = '')
    {
        try {
            $datahora = date('Ymd_His');
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if (!$AN) {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$this->UF);
                $sigla = $this->UF;
            } else {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'AN');
                $sigla = 'AN';
            }
            if ($ultNSU == '') {
                //buscar o ultimo NSU no xml
                $ultNSU = $this->getUltNSU($sigla, $tpAmb);
            }
            if ($indNFe == '') {
                $indNFe = '0';
            }
            if ($indEmi == '') {
                $indEmi = '0';
            }
            //identificação do serviço
            $servico = 'NfeConsultaDest';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico;
            //monta a consulta
            $Ev = '<consNFeDest xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><tpAmb>'.$tpAmb.'</tpAmb><xServ>CONSULTAR NFE DEST</xServ><CNPJ>'.$this->cnpj.'</CNPJ><indNFe>'.$indNFe.'</indNFe><indEmi>'.$indEmi.'</indEmi><ultNSU>'.$ultNSU.'</ultNSU></consNFeDest>';
            //montagem do cabeçalho da comunicação SOAP
            $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$this->cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
            //montagem dos dados da mensagem SOAP
            $dados = '<nfeDadosMsg xmlns="'.$namespace.'">'.$Ev.'</nfeDadosMsg>';
            //grava solicitação em temp
            if (!file_put_contents($this->temDir."$this->cnpj-$ultNSU-$datahora-LNFe.xml", $Ev)) {
                $msg = "Falha na gravação do arquivo LNFe (Lista de NFe)!!";
                $this->setError($msg);
            }
            //envia dados via SOAP
            if ($modSOAP == '2') {
                $retorno = $this->curlSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $this->UF);
            }
            //verifica o retorno
            if (!$retorno) {
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
            //tratar dados de retorno
            $indCont = 0;
            $xmlLNFe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlLNFe->formatOutput = false;
            $xmlLNFe->preserveWhiteSpace = false;
            $xmlLNFe->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retConsNFeDest = $xmlLNFe->getElementsByTagName("retConsNFeDest")->item(0);
            if (isset($retConsNFeDest)) {
                $cStat = !empty($retConsNFeDest->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retConsNFeDest->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                $xMotivo = !empty($retConsNFeDest->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retConsNFeDest->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                $ultNSU  = !empty($retConsNFeDest->getElementsByTagName('ultNSU')->item(0)->nodeValue) ? $retConsNFeDest->getElementsByTagName('ultNSU')->item(0)->nodeValue : '';
                $indCont = !empty($retConsNFeDest->getElementsByTagName('indCont')->item(0)->nodeValue) ? $retConsNFeDest->getElementsByTagName('indCont')->item(0)->nodeValue : 0;
            } else {
                $cStat = '';
            }
            if ($cStat == '') {
                //houve erro
                $msg = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
            //erro no processamento
            if ($cStat != '137' and $cStat != '138') {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "A requisição foi rejeitada : $cStat - $xMotivo\n";
                throw new NfephpException($msg);
            }
            //podem existir NFe emitidas para este destinatário
            $aNFe = array();
            $aCanc = array();
            $aCCe = array();
            $ret =  $xmlLNFe->getElementsByTagName("ret");
            foreach ($ret as $k => $d) {
                $resNFe = $ret->item($k)->getElementsByTagName('resNFe')->item(0);
                $resCanc = $ret->item($k)->getElementsByTagName('resCanc')->item(0);
                $resCCe = $ret->item($k)->getElementsByTagName('resCCe')->item(0);
                if (isset($resNFe)) {
                    //existem notas emitida para esse cnpj
                    $chNFe = $resNFe->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $CNPJ = $resNFe->getElementsByTagName('CNPJ')->item(0)->nodeValue;
                    $xNome = $resNFe->getElementsByTagName('xNome')->item(0)->nodeValue;
                    $dEmi = $resNFe->getElementsByTagName('dEmi')->item(0)->nodeValue;
                    $dhRecbto= $resNFe->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $tpNF = $resNFe->getElementsByTagName('tpNF')->item(0)->nodeValue;
                    $cSitNFe = $resNFe->getElementsByTagName('cSitNFe')->item(0)->nodeValue;
                    $cSitConf = $resNFe->getElementsByTagName('cSitConf')->item(0)->nodeValue;
                    $aNFe[] = array('chNFe'=>$chNFe,'CNPJ'=>$CNPJ,'xNome'=>$xNome,'dEmi'=>$dEmi,'dhRecbto'=>$dhRecbto,'$tpNF'=>$tpNF,'cSitNFe'=>$cSitNFe,'cSitconf'=>$cSitConf);
                }//fim resNFe
                if (isset($resCanc)) {
                    //existem notas emitida para esse cnpj
                    $chNFe = $resCanc->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $CNPJ = $resCanc->getElementsByTagName('CNPJ')->item(0)->nodeValue;
                    $xNome = $resCanc->getElementsByTagName('xNome')->item(0)->nodeValue;
                    $dEmi = $resCanc->getElementsByTagName('dEmi')->item(0)->nodeValue;
                    $dhRecbto= $resCanc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $tpNF = $resCanc->getElementsByTagName('tpNF')->item(0)->nodeValue;
                    $cSitNFe = $resCanc->getElementsByTagName('cSitNFe')->item(0)->nodeValue;
                    $cSitConf = $resCanc->getElementsByTagName('cSitConf')->item(0)->nodeValue;
                    $aCanc[] = array('chNFe'=>$chNFe,'CNPJ'=>$CNPJ,'xNome'=>$xNome,'dEmi'=>$dEmi,'dhRecbto'=>$dhRecbto,'$tpNF'=>$tpNF,'cSitNFe'=>$cSitNFe,'cSitconf'=>$cSitConf);
                }//fim resCanc
                if (isset($resCCe)) {
                    //existem notas emitida para esse cnpj
                    $chNFe = $resCCe->getElementsByTagName('chNFe')->item(0)->nodeValue;
                    $tpEvento = $resCCe->getElementsByTagName('tpEvento')->item(0)->nodeValue;
                    $nSeqEvento = $resCCe->getElementsByTagName('nSeqEvento')->item(0)->nodeValue;
                    $dhEvento = $resCCe->getElementsByTagName('dhEvento')->item(0)->nodeValue;
                    $dhRecbto= $resCCe->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
                    $descEvento = $resCCe->getElementsByTagName('descEvento')->item(0)->nodeValue;
                    $xCorrecao = $resCCe->getElementsByTagName('xCorrecao')->item(0)->nodeValue;
                    $tpNF = $resCCe->getElementsByTagName('tpNF')->item(0)->nodeValue;
                    $aCCe[] = array('chNFe'=>$chNFe,'tpEvento'=>$tpEvento,'nSeqEvento'=>$nSeqEvento,'dhEvento'=>$dhEvento,'dhRecbto'=>$dhRecbto,'descEvento'=>$descEvento,'xCorrecao'=>$xCorrecao,'tpNF'=>$tpNF);
                }//fim resCCe
            }//fim foreach ret
            //salva o arquivo xml
            if (!file_put_contents($this->temDir."$this->cnpj-$ultNSU-$datahora-resLNFe.xml", $retorno)) {
                $msg = "Falha na gravação do arquivo resLNFe!!";
                $this->setError($msg);
            }
            if ($ultNSU != '' && $indCont == 1) {
                //grava o ultimo NSU informado no arquivo
                $this->putUltNSU($sigla, $tpAmb, $ultNSU);
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }//fim catch
        $resp = array('NFe'=>$aNFe,'Canc'=>$aCanc,'CCe'=>$aCCe);
        return $retorno;
    }//fim getListNFe

    /**
     * getNFe
     * Download da NF-e para uma determinada Chave de Acesso informada,
     * para as NF-e confirmadas pelo destinatário. As NFe baixadas serão salvas
     * na pasta de recebidas
     *
     * ESSE SEVIÇO NÃO ESTÁ TOTALMENTE OPERACIONAL EXISTE APENAS NO SEFAZ DO RS E SVAN
     *
     * Este serviço não suporta SCAN !!
     *
     * @name getNFe
     * @param boolean $AN   true usa ambiente nacional, false usa o SEFAZ do emitente da NF
     * @param string $chNFe chave da NFe
     * @param string $tpAmb tipo de ambiente
     * @param string $modSOAP modo do SOAP
     * @return mixed FALSE ou xml de retorno
     *
     * TODO: quando o serviço estiver funcional extrair o xml da NFe e colocar
     * no diretorio correto
     */
    public function soapGetNFe($AN = true, $chNFe = '', $tpAmb = '', $modSOAP = '2')
    {
        try {
            if ($chNFe == '') {
                $msg = 'Uma chave de NFe deve ser passada como parâmetro da função.';
                throw new NfephpException($msg);
            }
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if ($AN) {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'AN');
            } else {
                //deve se verificado se NFe emitidas em SCAN, com séries começando com 9
                //podem ser obtidas no sefaz do emitente DUVIDA!!!
                //obtem a SEFAZ do emissor
                $cUF = substr($chNFe, 0, 2);
                $UF = $this->UFList[$cUF];
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$UF);
            }
            //identificação do serviço
            $servico = 'NfeDownloadNF';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico;
            if ($urlservico == '') {
                $msg = 'Não existe esse serviço na SEFAZ consultada.';
                throw new NfephpException($msg);
            }
            //montagem do cabeçalho da comunicação SOAP
            $cabec = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$this->cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
            //montagem dos dados da mensagem SOAP
            $dados = '<nfeDadosMsg xmlns="'.$namespace.'"><downloadNFe xmlns="'.$this->URLPortal.'" versao="'.$versao.'"><tpAmb>'.$tpAmb.'</tpAmb><xServ>DOWNLOAD NFE</xServ><CNPJ>'.$this->cnpj.'</CNPJ><chNFe>'.$chNFe.'</chNFe></downloadNFe></nfeDadosMsg>';
            //envia dados via SOAP
            if ($modSOAP == '2') {
                $retorno = $this->curlSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $this->UF);
            }
            //verifica o retorno
            if (!$retorno) {
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
            //salva arquivo de retorno contendo todo o XML da SEFAZ
            $fileName  = $this->temDir."$chNFe-resDWNFe.xml";
            if (!file_put_contents($fileName, $retorno)) {
                $msg = "Falha na gravação do arquivo $fileName!!";
                $this->setError($msg);
            }
            //tratar dados de retorno
            $xmlDNFe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlDNFe->formatOutput = false;
            $xmlDNFe->preserveWhiteSpace = false;
            $xmlDNFe->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retDownloadNFe = $xmlDNFe->getElementsByTagName("retDownloadNFe")->item(0);
            if (isset($retDownloadNFe)) {
                $cStat = !empty($retDownloadNFe->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retDownloadNFe->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                $xMotivo = !empty($retDownloadNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retDownloadNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                $dhResp = !empty($retDownloadNFe->getElementsByTagName('dhResp')->item(0)->nodeValue) ? $retDownloadNFe->getElementsByTagName('dhResp')->item(0)->nodeValue : '';
                //existem 2 cStat, um com nó pai retDownloadNFe ($cStat) e outro no
                //nó filho retNFe($cStatRetorno)
                //para que o download seja efetuado corretamente o $cStat deve vir com valor 139
                //e o $cStatRetorno com valor 140
                $retNFe = $xmlDNFe->getElementsByTagName("retNFe")->item(0);
                if (isset($retNFe)) {
                    $cStatRetorno = !empty($retNFe->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retNFe->getElementsByTagName('cStat')->item(0)->nodeValue : '';
                    $xMotivoRetorno = !empty($retNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
                } else {
                    $cStatRetorno = '';
                    $xMotivoRetorno = '';
                }
            } else {
                $cStat = '';
            }
            //status de retorno nao podem vir vazios
            if (empty($cStat)) {
                //houve erro
                $msg = "cStat está em branco, houve erro na comunicação verifique a mensagem de erro!";
                throw new NfephpException($msg);
            }
            //erro no processamento
            if ($cStat != '139') {
                //se cStat <> 139 ou 140 houve erro e o lote foi rejeitado
                $msg = "A requisição foi rejeitada : $cStat - $xMotivo\n";
                throw new NfephpException($msg);
            }
            if ($cStatRetorno != '140') {
                //pega o motivo do nó retNFe, com a descriçao da rejeiçao
                $msg = "Não houve o download da NF : $cStatRetorno - $xMotivoRetorno\n";
                throw new NfephpException($msg);
            }
            //grava arquivo XML iniciando com a tag nfeProc, sem o cabeçalho de retorno da SEFAZ
            $content = $xmlDNFe->getElementsByTagName("nfeProc")->item(0);
            $xml =  $content->ownerDocument->saveXML($content);
            $fileName = $this->recDir."$chNFe-procNFe.xml";
            if (!file_put_contents($fileName, $xml)) {
                $msg = "Falha na gravação do arquivo NFe $fileName!!";
                $this->setError($msg);
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }//fim catch
        return $retorno;
    }//fim getNFe

    /**
     * Solicita inutilizaçao de uma serie de numeros de NF
     * - o processo de inutilização será gravado na pasta Inutilizadas
     *
     * @name inutNF
     * @param	string  $nAno       ano com 2 digitos
     * @param   string  $nSerie     serie da NF 1 até 3 digitos
     * @param   integer $nIni       numero inicial 1 até 9 digitos zero a esq
     * @param   integer $nFin       numero Final 1 até 9 digitos zero a esq
     * @param   string  $xJust      justificativa 15 até 255 digitos
     * @param   string  $tpAmb      Tipo de ambiente 1-produção ou 2 homologação
     * @param   integer $modSOAP    1 usa nfeSOAP e 2 usa curlSOAP
     * @return	mixed false ou string com o xml do processo de inutilização
     */
    public function soapInutNF($nAno = '', $nSerie = '1', $nIni = '', $nFin = '', $xJust = '', $tpAmb = '', $modSOAP = '2')
    {
        //valida dos dados de entrada
        if ($nAno == '' || $nIni == '' || $nFin == '' || $xJust == '') {
            $msg = "Não foi passado algum dos parametos necessários ANO=$nAno inicio=$nIni fim=$nFin justificativa=$xJust.\n";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        //valida justificativa
        if (strlen($xJust) < 15) {
            $msg = "A justificativa deve ter pelo menos 15 digitos!!";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        if (strlen($xJust) > 255) {
            $msg = "A justificativa deve ter no máximo 255 digitos!!";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        //remove acentos e outros caracteres da justificativa
        $xJust = $this->cleanString($xJust);
        // valida o campo ano
        if (strlen($nAno) > 2) {
            $msg = "O ano tem mais de 2 digitos. Corrija e refaça o processo!!";
            $this->setError($msg);
            throw new NfephpException($msg);
        } else {
            if (strlen($nAno) < 2) {
                $msg = "O ano tem menos de 2 digitos. Corrija e refaça o processo!!";
                $this->setError($msg);
                throw new NfephpException($msg);
            }
        }
        //valida o campo serie
        if (strlen($nSerie) == 0 || strlen($nSerie) > 3) {
            $msg = "O campo serie está errado: $nSerie. Corrija e refaça o processo!!";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        //valida o campo numero inicial
        if (strlen($nIni) < 1 || strlen($nIni) > 9) {
            $msg = "O campo numero inicial está errado: $nIni. Corrija e refaça o processo!!";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        //valida o campo numero final
        if (strlen($nFin) < 1 || strlen($nFin) > 9) {
            $msg = "O campo numero final está errado: $nFin. Corrija e refaça o processo!!";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        //valida tipo de ambiente
        if ($tpAmb == '') {
            $tpAmb = $this->tpAmb;
        }
        //verifica se o SCAN esta habilitado
        if (!$this->enableSCAN) {
            if ($tpAmb == $this->tpAmb) {
                $aURL = $this->aURL;
            } else {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$this->UF);
            }
        } else {
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$this->tpAmb,'SCAN');
        }
        //identificação do serviço
        $servico = 'NfeInutilizacao';
        //recuperação da versão
        $versao = $aURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $aURL[$servico]['URL'];
        //recuperação do método
        $metodo = $aURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = $this->URLPortal.'/wsdl/'.$servico.'2';
        //Identificador da TAG a ser assinada formada com Código da UF +
        //Ano (2 posições) + CNPJ + modelo + série + nro inicial e nro final
        //precedida do literal “ID”
        // 43 posições
        //     2      4       6       20      22    25       34      43
        //     2      2       2       14       2     3        9       9
        $id = 'ID'.$this->cUF.$nAno.$this->cnpj.'55'.str_pad($nSerie,3,'0',STR_PAD_LEFT).str_pad($nIni,9,'0',STR_PAD_LEFT).str_pad($nFin,9,'0',STR_PAD_LEFT);
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<nfeCabecMsg xmlns="'.$namespace.'"><cUF>'.$this->cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';
        //montagem do corpo da mensagem
        $dXML = '<inutNFe xmlns="'.$this->URLnfe.'" versao="'.$versao.'">';
        $dXML .= '<infInut Id="'.$id.'">';
        $dXML .= '<tpAmb>'.$tpAmb.'</tpAmb>';
        $dXML .= '<xServ>INUTILIZAR</xServ>';
        $dXML .= '<cUF>'.$this->cUF.'</cUF>';
        $dXML .= '<ano>'.$nAno.'</ano>';
        $dXML .= '<CNPJ>'.$this->cnpj.'</CNPJ>';
        $dXML .= '<mod>55</mod>';
        $dXML .= '<serie>'.$nSerie.'</serie>';
        $dXML .= '<nNFIni>'.$nIni.'</nNFIni>';
        $dXML .= '<nNFFin>'.$nFin.'</nNFFin>';
        $dXML .= '<xJust>'.$xJust.'</xJust>';
        $dXML .= '</infInut>';
        $dXML .= '</inutNFe>';
        //assina a lsolicitação de inutilização
        $dXML = $this->signXML($dXML, 'infInut');
        $dados = '<nfeDadosMsg xmlns="'.$namespace.'">'.$dXML.'</nfeDadosMsg>';
        //remove as tags xml que porventura tenham sido inclusas
        $dados = str_replace('<?xml version="1.0"?>', '', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $dados);
        $dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $dados);
        $dados = str_replace(array("\r","\n","\s"), "", $dados);
        //grava a solicitação de inutilização
        if (!file_put_contents($this->temDir.$id.'-pedInut.xml', $dXML)) {
            $msg = "Falha na gravação do pedido de inutilização!!";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        //envia a solicitação via SOAP
        if ($modSOAP == '2') {
            $retorno = $this->curlSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb);
        } else {
            $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $this->tpAmb, $this->UF);
        }
        //verifica o retorno
        if (!$retorno) {
            $msg = "Nao houve retorno Soap verifique o debug!!";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        //tratar dados de retorno
        $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
        $xMotivo = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
        if ($cStat == '') {
            //houve erro
            $msg = "Nao houve retorno Soap verifique o debug!!";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        //verificar o status da solicitação
        if ($cStat != '102') {
            //houve erro
            $msg = "Rejeição : $cStat - $xMotivo";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        //gravar o retorno na pasta temp
        $nome = $this->temDir.$id.'-retInut.xml';
        $nome = $doc->save($nome);
        $retInutNFe = $doc->getElementsByTagName("retInutNFe")->item(0);
        //preparar o processo de inutilização
        $inut = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $inut->formatOutput = false;
        $inut->preserveWhiteSpace = false;
        $inut->loadXML($dXML, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        $inutNFe = $inut->getElementsByTagName("inutNFe")->item(0);
        //Processo completo solicitação + protocolo
        $procInut = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
        $procInut->formatOutput = false;
        $procInut->preserveWhiteSpace = false;
        //cria a tag procInutNFe
        $procInutNFe = $procInut->createElement('procInutNFe');
        $procInut->appendChild($procInutNFe);
        //estabele o atributo de versão
        $inutProc_att1 = $procInutNFe->appendChild($procInut->createAttribute('versao'));
        $inutProc_att1->appendChild($procInut->createTextNode($versao));
        //estabelece o atributo xmlns
        $inutProc_att2 = $procInutNFe->appendChild($procInut->createAttribute('xmlns'));
        $inutProc_att2->appendChild($procInut->createTextNode($this->URLPortal));
        //carrega o node cancNFe
        $node1 = $procInut->importNode($inutNFe, true);
        $procInutNFe->appendChild($node1);
        //carrega o node retEvento
        $node2 = $procInut->importNode($retInutNFe, true);
        $procInutNFe->appendChild($node2);
        //salva o xml como string em uma variável
        $procXML = $procInut->saveXML();
        //remove as informações indesejadas
        $procXML = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"", '', $procXML);
        $procXML = str_replace('default:', '', $procXML);
        $procXML = str_replace(':default', '', $procXML);
        $procXML = str_replace("\n", '', $procXML);
        $procXML = str_replace("\r", '', $procXML);
        $procXML = str_replace("\s", '', $procXML);
        //salva o arquivo xml
        if (!file_put_contents($this->inuDir."$id-procInut.xml", $procXML)) {
            $msg = "Falha na gravação da procInut!!\n";
            $this->setError($msg);
            throw new NfephpException($msg);
        }
        return $procXML;
    } //fim inutNFe

    /**
     * cancelEvent
     * Solicita o cancelamento de NFe autorizada
     * - O xml do evento de cancelamento será salvo na pasta Canceladas
     *
     * @name cancelEvent
     * @param string $chNFe
     * @param string $nProt
     * @param string $xJust
     * @param number $tpAmb
     * @param number $modSOAP
     */
    public function soapCancelEvent($chNFe = '', $nProt = '', $xJust = '', $tpAmb = '', $modSOAP = '2')
    {
        try {
            //validação dos dados de entrada
            if ($chNFe == '' || $nProt == '' || $xJust == '') {
                $msg = "Não foi passado algum dos parâmetros necessários ID=$chNFe ou protocolo=$nProt ou justificativa=$xJust.";
                throw new NfephpException($msg);
            }
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if (strlen($xJust) < 15) {
                $msg = "A justificativa deve ter pelo menos 15 digitos!!";
                throw new NfephpException($msg);
            }
            if (strlen($xJust) > 255) {
                $msg = "A justificativa deve ter no máximo 255 digitos!!";
                throw new NfephpException($msg);
            }
            if (strlen($chNFe) != 44) {
                $msg = "Uma chave de NFe válida não foi passada como parâmetro $chNFe.";
                throw new NfephpException($msg);
            }
            //estabelece o codigo do tipo de evento CANCELAMENTO
            $tpEvento = '110111';
            $descEvento = 'Cancelamento';
            //para cancelamento o numero sequencia do evento sempre será 1
            $nSeqEvento = '1';
            //remove qualquer caracter especial
            $xJust = $this->cleanString($xJust);
            //decompor a chNFe e pegar o tipo de emissão
            $tpEmiss = substr($chNFe, 34, 1);
            //verifica se o SCAN esta habilitado
            if (!$this->enableSCAN) {
                $aURL = $this->aURL;
            } else {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'SCAN');
            }
            $numLote = substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, 15);
            //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
            $dhEvento = date('Y-m-d').'T'.date('H:i:s').$this->timeZone;
            //se o envio for para svan mudar o numero no orgão para 91
            if ($this->enableSVAN) {
                $cOrgao='90';
            } else {
                $cOrgao=$this->cUF;
            }
            //montagem do namespace do serviço
            $servico = 'RecepcaoEvento';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico;
            //de acordo com o manual versão 5 de março de 2012
            // 2   +    6     +    44         +   2  = 54 digitos
            //“ID” + tpEvento + chave da NF-e + nSeqEvento
            //garantir que existam 2 digitos em nSeqEvento para montar o ID com 54 digitos
            if (strlen(trim($nSeqEvento))==1) {
                $zenSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
            } else {
                $zenSeqEvento = trim($nSeqEvento);
            }
            $id = "ID".$tpEvento.$chNFe.$zenSeqEvento;
            //monta mensagem
            $Ev='';
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$id\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$versao\">";
            $Ev .= "<descEvento>$descEvento</descEvento>";
            $Ev .= "<nProt>$nProt</nProt>";
            $Ev .= "<xJust>$xJust</xJust>";
            $Ev .= "</detEvento></infEvento></evento>";
            //assinatura dos dados
            $tagid = 'infEvento';
            $Ev = $this->signXML($Ev, $tagid);
            $Ev = str_replace('<?xml version="1.0"?>', '', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $Ev);
            $Ev = str_replace(array("\r","\n","\s"), '', $Ev);
            //carrega uma matriz temporária com os eventos assinados
            //montagem dos dados
            $dados = '';
            $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dados .= "<idLote>$numLote</idLote>";
            $dados .= $Ev;
            $dados .= "</envEvento>";
            //montagem da mensagem
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF><versaoDados>$versao</versaoDados></nfeCabecMsg>";
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
            //grava solicitação em temp
            $arqName = $this->temDir."$chNFe-$nSeqEvento-eventCanc.xml";
            if (!file_put_contents($arqName, $Ev)) {
                $msg = "Falha na gravação do arquivo $arqName";
                $this->setError($msg);
            }
            //envia dados via SOAP
            if ($modSOAP == '2') {
                $retorno = $this->curlSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $this->UF);
            }
            //verifica o retorno
            if (!$retorno) {
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
            //tratar dados de retorno
            $xmlretEvent = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlretEvent->formatOutput = false;
            $xmlretEvent->preserveWhiteSpace = false;
            $xmlretEvent->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retEvento = $xmlretEvent->getElementsByTagName("retEvento")->item(0);
            $cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat == '') {
                //houve erro
                $msg = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
            //tratar erro de versão do XML
            if ($cStat == '238' || $cStat == '239') {
                $this->solveVersionErr($retorno, $this->UF, $tpAmb, $servico, $versao);
                $msg = "Versão do arquivo XML não suportada no webservice!!";
                throw new NfephpException($msg);
            }
            //erro no processamento cStat <> 135
            if ($cStat != 135) {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "Retorno de ERRO: $cStat - $xMotivo";
                throw new NfephpException($msg);
            }
            //o evento foi aceito cStat == 135
            //carregar o evento
            $xmlenvEvento = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlenvEvento->formatOutput = false;
            $xmlenvEvento->preserveWhiteSpace = false;
            $xmlenvEvento->loadXML($Ev, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $evento = $xmlenvEvento->getElementsByTagName("evento")->item(0);
            //Processo completo solicitação + protocolo
            $xmlprocEvento = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlprocEvento->formatOutput = false;
            $xmlprocEvento->preserveWhiteSpace = false;
            //cria a tag procEventoNFe
            $procEventoNFe = $xmlprocEvento->createElement('procEventoNFe');
            $xmlprocEvento->appendChild($procEventoNFe);
            //estabele o atributo de versão
            $eventProc_att1 = $procEventoNFe->appendChild($xmlprocEvento->createAttribute('versao'));
            $eventProc_att1->appendChild($xmlprocEvento->createTextNode($versao));
            //estabelece o atributo xmlns
            $eventProc_att2 = $procEventoNFe->appendChild($xmlprocEvento->createAttribute('xmlns'));
            $eventProc_att2->appendChild($xmlprocEvento->createTextNode($this->URLPortal));
            //carrega o node evento
            $node1 = $xmlprocEvento->importNode($evento, true);
            $procEventoNFe->appendChild($node1);
            //carrega o node retEvento
            $node2 = $xmlprocEvento->importNode($retEvento, true);
            $procEventoNFe->appendChild($node2);
            //salva o xml como string em uma variável
            $procXML = $xmlprocEvento->saveXML();
            //remove as informações indesejadas
            $procXML = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"", '', $procXML);
            $procXML = str_replace('default:', '', $procXML);
            $procXML = str_replace(':default', '', $procXML);
            $procXML = str_replace("\n", '', $procXML);
            $procXML = str_replace("\r", '', $procXML);
            $procXML = str_replace("\s", '', $procXML);
            //salva o arquivo xml
            $arqName = $this->canDir."$chNFe-$nSeqEvento-procCanc.xml";
            if (!file_put_contents($arqName, $procXML)) {
                $msg = "Falha na gravação do arquivo $arqName";
                $this->setError($msg);
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return $procXML;
    } //fim cancEvent

    /**
     * envCCe
     * Envia carta de correção da Nota Fiscal para a SEFAZ.
     *
     * @name envCCe
     * @param   string $chNFe Chave da NFe
     * @param   string $xCorrecao Descrição da Correção entre 15 e 1000 caracteres
     * @param   string $nSeqEvento numero sequencial da correção d 1 até 20
     *                             isso deve ser mantido na base de dados e
     *                             as correções consolidadas, isto é a cada nova correção
     *                             devem ser inclusas as anteriores no texto.
     *                             O Web Service não permite a duplicidade de numeração
     *                             e nem controla a ordem crescente
     * @param   integer $tpAmb Tipo de ambiente
     * @param   integer $modSOAP 1 usa sendSOP e 2 usa curlSOAP
     * @return	mixed false ou xml com a CCe
     */
    public function soapEnvCCe($chNFe = '', $xCorrecao = '', $nSeqEvento = '1', $tpAmb = '', $modSOAP = '2')
    {
        try {
            //testa se os dados da carta de correção foram passados
            if ($chNFe == '' || $xCorrecao == '') {
                $msg = "Dados para a carta de correção não podem ser vazios.";
                throw new NfephpException($msg);
            }
            if (strlen($chNFe) != 44) {
                $msg = "Uma chave de NFe válida não foi passada como parâmetro $chNFe.";
                throw new NfephpException($msg);
            }
            //se o numero sequencial do evento não foi informado ou se for maior que 1 digito
            if ($nSeqEvento == '' || strlen($nSeqEvento) > 2 || !is_numeric($nSeqEvento)) {
                $msg .= "Número sequencial da correção não encontrado ou é maior que 99 ou contêm caracteres não numéricos [$nSeqEvento]";
                throw new NfephpException($msg);
            }
            if (strlen($xCorrecao) < 15 || strlen($xCorrecao) > 1000) {
                $msg .= "O texto da correção deve ter entre 15 e 1000 caracteres!";
                throw new NfephpException($msg);
            }
            //limpa o texto de correção para evitar surpresas
            $xCorrecao = $this->cleanString($xCorrecao);
            //ajusta ambiente
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            //decompor a chNFe e pegar o tipo de emissão
            $tpEmiss = substr($chNFe, 34, 1);
            //verifica se o SCAN esta habilitado
            if (!$this->enableSCAN) {
                $aURL = $this->aURL;
            } else {
                $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'SCAN');
            }
            $numLote = substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, 15);
            //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
            $dhEvento = date('Y-m-d').'T'.date('H:i:s').$this->timeZone;
            //se o envio for para svan mudar o numero no orgão para 91
            if ($this->enableSVAN) {
                $cOrgao='91';
            } else {
                $cOrgao=$this->cUF;
            }
            //montagem do namespace do serviço
            $servico = 'RecepcaoEvento';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico;
            //estabelece o codigo do tipo de evento
            $tpEvento = '110110';
            //de acordo com o manual versão 5 de março de 2012
            // 2   +    6     +    44         +   2  = 54 digitos
            //“ID” + tpEvento + chave da NF-e + nSeqEvento
            //garantir que existam 2 digitos em nSeqEvento para montar o ID com 54 digitos
            if (strlen(trim($nSeqEvento))==1) {
                $zenSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
            } else {
                $zenSeqEvento = trim($nSeqEvento);
            }
            $id = "ID".$tpEvento.$chNFe.$zenSeqEvento;
            $descEvento = 'Carta de Correcao';
            $xCondUso = 'A Carta de Correcao e disciplinada pelo paragrafo 1o-A do art. 7o do Convenio S/N, de 15 de dezembro de 1970 e pode ser utilizada para regularizacao de erro ocorrido na emissao de documento fiscal, desde que o erro nao esteja relacionado com: I - as variaveis que determinam o valor do imposto tais como: base de calculo, aliquota, diferenca de preco, quantidade, valor da operacao ou da prestacao; II - a correcao de dados cadastrais que implique mudanca do remetente ou do destinatario; III - a data de emissao ou de saida.';
            //monta mensagem
            $Ev='';
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$id\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$versao\">";
            $Ev .= "<descEvento>$descEvento</descEvento>";
            $Ev .= "<xCorrecao>$xCorrecao</xCorrecao>";
            $Ev .= "<xCondUso>$xCondUso</xCondUso>";
            $Ev .= "</detEvento></infEvento></evento>";
            //assinatura dos dados
            $tagid = 'infEvento';
            $Ev = $this->signXML($Ev, $tagid);
            $Ev = str_replace('<?xml version="1.0"?>', '', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $Ev);
            $Ev = str_replace(array("\r","\n","\s"), '', $Ev);
            //carrega uma matriz temporária com os eventos assinados
            //montagem dos dados
            $dados = '';
            $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dados .= "<idLote>$numLote</idLote>";
            $dados .= $Ev;
            $dados .= "</envEvento>";
            //montagem da mensagem
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF><versaoDados>$versao</versaoDados></nfeCabecMsg>";
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
            //grava solicitação em temp
            if (!file_put_contents($this->temDir."$chNFe-$nSeqEvento-envCCe.xml", $Ev)) {
                $msg = "Falha na gravação do arquivo envCCe!!";
                throw new NfephpException($msg);
            }
            //envia dados via SOAP
            if ($modSOAP == '2') {
                $retorno = $this->curlSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $this->UF);
            }
            //verifica o retorno
            if (!$retorno) {
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
            //tratar dados de retorno
            $xmlretCCe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlretCCe->formatOutput = false;
            $xmlretCCe->preserveWhiteSpace = false;
            $xmlretCCe->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retEvento = $xmlretCCe->getElementsByTagName("retEvento")->item(0);
            $cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat == '') {
                //houve erro
                $msg = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
            //erro no processamento cStat <> 128
            if ($cStat != 135) {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "Retorno de ERRO: $cStat - $xMotivo";
                throw new NfephpException($msg);
            }
            //a correção foi aceita cStat == 135
            //carregar a CCe
            $xmlenvCCe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlenvCCe->formatOutput = false;
            $xmlenvCCe->preserveWhiteSpace = false;
            $xmlenvCCe->loadXML($Ev, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $evento = $xmlenvCCe->getElementsByTagName("evento")->item(0);
            //Processo completo solicitação + protocolo
            $xmlprocCCe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlprocCCe->formatOutput = false;
            $xmlprocCCe->preserveWhiteSpace = false;
            //cria a tag procEventoNFe
            $procEventoNFe = $xmlprocCCe->createElement('procEventoNFe');
            $xmlprocCCe->appendChild($procEventoNFe);
            //estabele o atributo de versão
            $eventProc_att1 = $procEventoNFe->appendChild($xmlprocCCe->createAttribute('versao'));
            $eventProc_att1->appendChild($xmlprocCCe->createTextNode($versao));
            //estabelece o atributo xmlns
            $eventProc_att2 = $procEventoNFe->appendChild($xmlprocCCe->createAttribute('xmlns'));
            $eventProc_att2->appendChild($xmlprocCCe->createTextNode($this->URLPortal));
            //carrega o node evento
            $node1 = $xmlprocCCe->importNode($evento, true);
            $procEventoNFe->appendChild($node1);
            //carrega o node retEvento
            $node2 = $xmlprocCCe->importNode($retEvento, true);
            $procEventoNFe->appendChild($node2);
            //salva o xml como string em uma variável
            $procXML = $xmlprocCCe->saveXML();
            //remove as informações indesejadas
            $procXML = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"", '', $procXML);
            $procXML = str_replace('default:', '', $procXML);
            $procXML = str_replace(':default', '', $procXML);
            $procXML = str_replace("\n", '', $procXML);
            $procXML = str_replace("\r", '', $procXML);
            $procXML = str_replace("\s", '', $procXML);
            //salva o arquivo xml
            if (!file_put_contents($this->cccDir."$chNFe-$nSeqEvento-procCCe.xml", $procXML)) {
                $msg = "Falha na gravação da procCCe!!";
                $this->setError($msg);
                throw new NfephpException($msg);
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return $procXML;
    }//fim envCCe

    /**
     * manifDest
     * Manifestação do detinatário NT2012-002.
     *     210200 – Confirmação da Operação
     *     210210 – Ciência da Operação
     *     210220 – Desconhecimento da Operação
     *     210240 – Operação não Realizada
     *
     * @name manifDest
     * @param   string $chNFe Chave da NFe
     * @param   string $tpEvento Tipo do evento pode conter 2 ou 6 digitos ex. 00 ou 210200
     * @param   string $xJust Justificativa quando tpEvento = 40 ou 210240
     * @param   integer $tpAmb Tipo de ambiente
     * @param   integer $modSOAP 1 usa sendSOP e 2 usa curlSOAP
     * @param   mixed  $resp variável passada como referencia e irá conter o retorno da função em um array
     * @return	mixed false
     *
     * TODO : terminar o código não funcional e não testado
     */
    public function soapManifDest($chNFe = '', $tpEvento = '', $xJust = '', $tpAmb = '', $modSOAP = '2', &$resp = '')
    {
        try {
            if ($chNFe == '') {
                $msg = "A chave da NFe recebida é obrigatória.";
                throw new NfephpException($msg);
            }
            if ($tpEvento == '') {
                $msg = "O tipo de evento não pode ser vazio.";
                throw new NfephpException($msg);
            }
            if (strlen($tpEvento) == 2) {
                $tpEvento = "2102$tpEvento";
            }
            if (strlen($tpEvento) != 6) {
                $msg = "O comprimento do código do tipo de evento está errado.";
                throw new NfephpException($msg);
            }
            switch ($tpEvento) {
                case '210200':
                    $descEvento = 'Confirmacao da Operacao'; //confirma a operação e o recebimento da mercadoria (para as operações com circulação de mercadoria)
                    //Após a Confirmação da Operação pelo destinatário, a empresa emitente fica automaticamente impedida de cancelar a NF-e
                    break;
                case '210210':
                    $descEvento = 'Ciencia da Operacao'; //encrenca !!! Não usar
                    //O evento de “Ciência da Operação” é um evento opcional e pode ser evitado
                    //Após um período determinado, todas as operações com “Ciência da Operação” deverão
                    //obrigatoriamente ter a manifestação final do destinatário declarada em um dos eventos de
                    //Confirmação da Operação, Desconhecimento ou Operação não Realizada
                    break;
                case '210220':
                    $descEvento = 'Desconhecimento da Operacao';
                    //Uma empresa pode ficar sabendo das operações destinadas a um determinado CNPJ
                    //consultando o “Serviço de Consulta da Relação de Documentos Destinados” ao seu CNPJ.
                    //O evento de “Desconhecimento da Operação” permite ao destinatário informar o seu
                    //desconhecimento de uma determinada operação que conste nesta relação, por exemplo
                    break;
                case '210240':
                    $descEvento = 'Operacao nao Realizada'; //não aceitação no recebimento que antes se fazia com apenas um carimbo na NF
                    //operação não foi realizada (com Recusa de Recebimento da mercadoria e outros motivos),
                    //não cabendo neste caso a emissão de uma Nota Fiscal de devolução.
                    break;
                default:
                    $msg = "O código do tipo de evento informado não corresponde a nenhum evento de manifestação de destinatário.";
                    throw new NfephpException($msg);
            }
            $resp = array('bStat'=>false,'cStat'=>'','xMotivo'=>'','arquivo'=>'');
            if ($tpEvento == '210240' && $xJust == '') {
                    $msg = "Uma Justificativa é obrigatória para o evento de Operação não Realizada.";
                    throw new NfephpException($msg);
            }
            //limpa o texto de correção para evitar surpresas
            $xJust = $this->cleanString($xJust);
            //ajusta ambiente
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            //utilizar AN para enviar o manifesto
            $sigla = 'AN';
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,$sigla);
            $cOrgao='91';
            $numLote = substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, 15);
            //Data e hora do evento no formato AAAA-MM-DDTHH:MM:SSTZD (UTC)
            $dhEvento = date('Y-m-d').'T'.date('H:i:s').$this->timeZone;
            //montagem do namespace do serviço
            $servico = 'RecepcaoEvento';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico;
            // 2   +    6     +    44         +   2  = 54 digitos
            //“ID” + tpEvento + chave da NF-e + nSeqEvento
            $nSeqEvento = '1';
            $id = "ID".$tpEvento.$chNFe.'0'.$nSeqEvento;
            //monta mensagem
            $Ev='';
            $Ev .= "<evento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $Ev .= "<infEvento Id=\"$id\">";
            $Ev .= "<cOrgao>$cOrgao</cOrgao>";
            $Ev .= "<tpAmb>$tpAmb</tpAmb>";
            $Ev .= "<CNPJ>$this->cnpj</CNPJ>";
            $Ev .= "<chNFe>$chNFe</chNFe>";
            $Ev .= "<dhEvento>$dhEvento</dhEvento>";
            $Ev .= "<tpEvento>$tpEvento</tpEvento>";
            $Ev .= "<nSeqEvento>$nSeqEvento</nSeqEvento>";
            $Ev .= "<verEvento>$versao</verEvento>";
            $Ev .= "<detEvento versao=\"$versao\">";
            $Ev .= "<descEvento>$descEvento</descEvento>";
            if ($xJust != '') {
                $Ev .= "<xJust>$xJust</xJust>";
            }
            $Ev .= "</detEvento></infEvento></evento>";
            //assinatura dos dados
            $tagid = 'infEvento';
            $Ev = $this->signXML($Ev, $tagid);
            $Ev = str_replace('<?xml version="1.0"?>', '', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $Ev);
            $Ev = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $Ev);
            $Ev = str_replace(array("\r","\n","\s"), '', $Ev);
            //montagem dos dados
            $dados = '';
            $dados .= "<envEvento xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dados .= "<idLote>$numLote</idLote>";
            $dados .= $Ev;
            $dados .= "</envEvento>";
            //montagem da mensagem
            $cabec = "<nfeCabecMsg xmlns=\"$namespace\"><cUF>$this->cUF</cUF><versaoDados>$versao</versaoDados></nfeCabecMsg>";
            $dados = "<nfeDadosMsg xmlns=\"$namespace\">$dados</nfeDadosMsg>";
            //grava solicitação em temp
            if (!file_put_contents($this->temDir."$chNFe-$nSeqEvento-envMDe.xml", $Ev)) {
                $msg = "Falha na gravação do aruqivo envMDe!!";
                throw new NfephpException($msg);
            }
            //envia dados via SOAP
            if ($modSOAP == '2') {
                $retorno = $this->curlSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb);
            } else {
                $retorno = $this->nfeSOAP($urlservico, $namespace, $cabec, $dados, $metodo, $tpAmb, $this->UF);
            }
            //verifica o retorno
            if (!$retorno) {
                //não houve retorno
                $msg = "Nao houve retorno Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
            //tratar dados de retorno
            $xmlMDe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlMDe->formatOutput = false;
            $xmlMDe->preserveWhiteSpace = false;
            $xmlMDe->loadXML($retorno, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retEvento = $xmlMDe->getElementsByTagName("retEvento")->item(0);
            $infEvento = $xmlMDe->getElementsByTagName("infEvento")->item(0);
            $cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
            if ($cStat == '') {
                //houve erro
                $msg = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
                throw new NfephpException($msg);
            }
            //tratar erro de versão do XML
            if ($cStat == '238' || $cStat == '239') {
                $this->solveVersionErr($retorno, $sigla, $tpAmb, $servico, $versao);
                $msg = "Versão do arquivo XML não suportada no webservice!!";
                throw new NfephpException($msg);
            }
            //erro no processamento
            if ($cStat != '135' && $cStat != '136') {
                //se cStat <> 135 houve erro e o lote foi rejeitado
                $msg = "O Lote foi rejeitado : $cStat - $xMotivo\n";
                throw new NfephpException($msg);
            }
            if ($cStat == '136') {
                $msg = "O Evento foi registrado mas a NFe não foi localizada : $cStat - $xMotivo\n";
                throw new NfephpException($msg);
            }
            //o evento foi aceito
            $xmlenvMDe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlenvMDe->formatOutput = false;
            $xmlenvMDe->preserveWhiteSpace = false;
            $xmlenvMDe->loadXML($Ev, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $evento = $xmlenvMDe->getElementsByTagName("evento")->item(0);
            //Processo completo solicitação + protocolo
            $xmlprocMDe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $xmlprocMDe->formatOutput = false;
            $xmlprocMDe->preserveWhiteSpace = false;
            //cria a tag procEventoNFe
            $procEventoNFe = $xmlprocMDe->createElement('procEventoNFe');
            $xmlprocMDe->appendChild($procEventoNFe);
            //estabele o atributo de versão
            $eventProc_att1 = $procEventoNFe->appendChild($xmlprocMDe->createAttribute('versao'));
            $eventProc_att1->appendChild($xmlprocMDe->createTextNode($versao));
            //estabelece o atributo xmlns
            $eventProc_att2 = $procEventoNFe->appendChild($xmlprocMDe->createAttribute('xmlns'));
            $eventProc_att2->appendChild($xmlprocMDe->createTextNode($this->URLPortal));
            //carrega o node evento
            $node1 = $xmlprocMDe->importNode($evento, true);
            $procEventoNFe->appendChild($node1);
            //carrega o node retEvento
            $node2 = $xmlprocMDe->importNode($retEvento, true);
            $procEventoNFe->appendChild($node2);
            //salva o xml como string em uma variável
            $procXML = $xmlprocMDe->saveXML();
            //remove as informações indesejadas
            $procXML = str_replace("xmlns:default=\"http://www.w3.org/2000/09/xmldsig#\"", '', $procXML);
            $procXML = str_replace('default:', '', $procXML);
            $procXML = str_replace(':default', '', $procXML);
            $procXML = str_replace("\n", '', $procXML);
            $procXML = str_replace("\r", '', $procXML);
            $procXML = str_replace("\s", '', $procXML);
            $filename = $this->evtDir."$chNFe-$tpEvento-$nSeqEvento-procMDe.xml";
            $resp = array('bStat'=>true,'cStat'=>$cStat,'xMotivo'=>$xMotivo,'arquivo'=>$filename);
            //salva o arquivo xml
            if (!file_put_contents($filename, $procXML)) {
                $msg = "Falha na gravação do arquivo procMDe!!";
                throw new NfephpException($msg);
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            $resp = array('bStat'=>false,'cStat'=>$cStat,'xMotivo'=>$xMotivo,'arquivo'=>'');
            return false;
        }
        return $retorno;
    } //fim manifDest

    /**
     * envDPEC
     * Apenas para teste não funcional
     *
     */
    public function soapEnvDPEC($aNFe = '', $tpAmb = '', $modSOAP = '2')
    {
        // Habilita a manipulaçao de erros da libxml
        libxml_use_internal_errors(true);
        try {
            if ($aNFe == '') {
                $msg = "Pelo menos uma NFe deve ser passada como parâmetro!!";
                throw new NfephpException($msg);
            }
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if (is_array($aNFe)) {
                $matriz = $aNFe;
            } else {
                $matriz[]=$aNFe;
            }
            $i = 0;

            foreach ($matriz as $n) {
                $errors = null;
                $dom = null;
                $dom = new DomDocument; //cria objeto DOM
                if (is_file($n)) {
                    $dom->load($n, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                } else {
                    $dom->loadXML($n, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                }
                $errors = libxml_get_errors();
                if (!empty($errors)) {
                    //o dado passado como $docXml não é um xml
                    $msg = "O dado informado não é um XML. $n " . implode('; ', $erros);
                    throw new NfephpException($msg);
                } else {
                    //pegar os dados necessários para DPEC
                    $infNFe = $dom->getElementsByTagName("infNFe")->item(0);
                    $ide = $dom->getElementsByTagName("ide")->item(0);
                    $xtpAmb = $ide->getElementsByTagName("tpAmb")->item(0)->nodeValue;
                    $tpEmis = $ide->getElementsByTagName("tpEmis")->item(0)->nodeValue;
                    $dhCont = !empty($dom->getElementsByTagName("dhCont")->item(0)->nodeValue) ? $dom->getElementsByTagName("dhCont")->item(0)->nodeValue : '';
                    $xJust = !empty($dom->getElementsByTagName("xJust")->item(0)->nodeValue) ? $dom->getElementsByTagName("xJust")->item(0)->nodeValue : '';
                    $verProc = !empty($dom->getElementsByTagName("verProc")->item(0)->nodeValue) ? $dom->getElementsByTagName("verProc")->item(0)->nodeValue : '';
                    $chNFe = preg_replace('/[^0-9]/', '', trim($infNFe->getAttribute("Id")));
                    if ($tpEmis != '4') {
                        $msg = "O tipo de emissão deve ser igual a 4 e não $tpEmiss!!";
                        throw new NfephpException($msg);
                    }
                    if ($xJust == '' || strlen($xJust) < 15 || strlen($xJust > 256)) {
                        $msg = "A NFe deve conter uma justificativa com 15 até 256 dígitos. Sua justificativa está com " . strlen($xJust). " dígitos";
                        throw new NfephpException($msg);
                    }
                    if ($xtpAmb != $tpAmb) {
                        $msg = "O tipo de ambiente indicado na NFe deve ser o mesmo da chamada do método e estão diferentes!!";
                        throw new NfephpException($msg);
                    }
                    if ($verProc == '') {
                        $msg = "O campo verProc não pode estar vazio !!";
                        throw new NfephpException($msg);
                    }
                    $dest = $dom->getElementsByTagName("dest")->item(0);
                    $destCNPJ = !empty($dest->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $dest->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                    $destCPF  = !empty($dest->getElementsByTagName("CPF")->item(0)->nodeValue) ? $dest->getElementsByTagName("CPF")->item(0)->nodeValue : '';
                    $destUF = !empty($dest->getElementsByTagName("UF")->item(0)->nodeValue) ? $dest->getElementsByTagName("UF")->item(0)->nodeValue : '';
                    $ICMSTot = $dom->getElementsByTagName("ICMSTot")->item(0);
                    $vNF = !empty($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue : '';
                    $vICMS = !empty($ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue : '';
                    $vST = !empty($ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue : '';
                    $aD[$i]['tpAmb'] = $xtpAmb;
                    $aD[$i]['tpEmiss'] = $tpEmiss;
                    $aD[$i]['dhCont'] = $dhCont;
                    $aD[$i]['xJust'] = $xJust;
                    $aD[$i]['chNFe'] = $chNFe;
                    $aD[$i]['CNPJ'] = $destCNPJ;
                    $aD[$i]['CPF'] = $destCPF;
                    $aD[$i]['UF'] = $destUF;
                    $aD[$i]['vNF'] = $vNF;
                    $aD[$i]['vICMS'] = $vICMS;
                    $aD[$i]['vST'] = $vST;
                    $i++;
                } //fim errors
            }//fim foreach
            //com a matriz de dados montada criar o arquivo DPEC para as NFe que atendem os critérios
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile,$tpAmb,'DPEC');
            //identificação do serviço
            $servico = 'SCERecepcaoRFB';
            //recuperação da versão
            $versao = $aURL[$servico]['version'];
            //recuperação da url do serviço
            $urlservico = $aURL[$servico]['URL'];
            //recuperação do método
            $metodo = $aURL[$servico]['method'];
            //montagem do namespace do serviço
            $namespace = $this->URLPortal.'/wsdl/'.$servico.'';
            $dpec = '';
            $dpec .= "<envDPEC xmlns=\"$this->URLPortal\" versao=\"$versao\">";
            $dpec .= "<infDPEC><id>DPEC$this->CNPJ</id>";
            $dpec .= "<ideDec><cUF>$this->cUF</cUF><tpAmb>$tpAmb</tpAmb><verProc>$verProc</verProc><CNPJ>$this->CNPJ</CNPJ><IE>$this->IE</IE></ideDec>";
            foreach ($aD as $d) {
                if ($d['CPF'] != '') {
                    $cnpj = "<CPF>".$d['CPF']."</CPF>";
                } else {
                    $cnpj = "<CNPJ>".$d['CNPJ']."</CNPJ>";
                }
                $dpec .= "<resNFe><chNFe>".$d['chNFe']."</chNFe>$cnpj<UF>".$d['UF']."</UF><vNF>".$d['vNF']."</vNF><vICMS>".$d['vICMS']."</vICMS><vST>".$d['vST']."</vST></resNFe>";
            }
            $dpec .= "</infDPEC></envDPEC>";
            //assinar a mensagem
            $dpec = $this->signXML($dpec, 'infDPEC');
            //montagem do cabeçalho da comunicação SOAP
            $cabec = '<sceCabecMsg xmlns="'. $namespace . '"><versaoDados>'.$versao.'</versaoDados></sceCabecMsg>';
            //montagem dos dados da cumunicação SOAP
            $dados = '<sceDadosMsg xmlns="'. $namespace . '">'.$dpec.'</sceDadosMsg>';
            //remove as tags xml que porventura tenham sido inclusas ou quebas de linhas
            $dados = str_replace('<?xml version="1.0"?>', '', $dados);
            $dados = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $dados);
            $dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $dados);
            $dados = str_replace(array("\r","\n","\s"), '', $dados);
            //grava a solicitação na pasta depec
            if( !file_put_contents($this->dpcDir.$this->CNPJ.'-depc.xml', '<?xml version="1.0" encoding="utf-8"?>'.$dpec)){
                $msg = "Falha na gravação do pedido contingencia DPEC.";
                throw new NfephpException($msg);
            }
            //..... continua ainda falta bastante coisa
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return $dados;
    }//fim envDPEC

    /**
     * verifyNFe
     * Verifica a validade da NFe recebida de terceiros
     *
     * @param string $file Path completo para o arquivo xml a ser verificado
     * @return boolean false se não confere e true se confere
     */
    public function verifyNFe($file)
    {
        try {
            //verifica se o arquivo existe
            if (!file_exists($file)) {
                $msg = "Arquivo não localizado!!";
                throw new NfephpException($msg);
            }
            //carrega a NFe
            $xml = file_get_contents($file);
            //testa a assinatura
            if (!$this->verifySignatureXML($xml, 'infNFe', $err)) {
                $msg = "Assinatura não confere!! ".$err;
                throw new NfephpException($msg);
            }
            //como a ssinatura confere, consultar o SEFAZ para verificar se a NF não foi cancelada ou é FALSA
            //carrega o documento no DOM
            $xmldoc = new DOMDocument('1.0', 'utf-8');
            $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
            $xmldoc->formatOutput = false;
            $xmldoc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $root = $xmldoc->documentElement;
            $infNFe = $xmldoc->getElementsByTagName('infNFe')->item(0);
            //extrair a tag com os dados a serem assinados
            $id = trim($infNFe->getAttribute("Id"));
            $chave = preg_replace('/[^0-9]/', '', $id);
            $digest = $xmldoc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
            //ambiente da NFe sendo consultada
            $tpAmb = $infNFe->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            //verifica se existe o protocolo
            $protNFe = $xmldoc->getElementsByTagName('protNFe')->item(0);
            if (isset($protNFe)) {
                $nProt = $xmldoc->getElementsByTagName('nProt')->item(0)->nodeValue;
            } else {
                $nProt = '';
            }
            //busca o status da NFe na SEFAZ do estado do emitente
            $resp = $this->soapGetProtocol('', $chave, $tpAmb);
            if ($resp['cStat']!='100') {
                $msg = "NF não aprovada no SEFAZ!! cStat =" . $resp['cStat'] .' - '.$resp['xMotivo'] ."";
                throw new NfephpException($msg);
            }
            if (!is_array($resp['aProt'])) {
                $msg = "Falha no retorno dos dados, retornado sem o protocolo !!";
                throw new NfephpException($msg);
            }
            $nProtSefaz = $resp['aProt']['nProt'];
            $digestSefaz = $resp['aProt']['digVal'];
            //verificar numero do protocolo
            if ($nProt == '') {
                $msg = "A NFe enviada não contêm o protocolo de aceitação !!";
                throw new NfephpException($msg);
            }
            if ($nProtSefaz != $nProt) {
                $msg = "Os numeros dos protocolos não combinam!! nProtNF = " . $nProt . " <> nProtSefaz = " . $nProtSefaz."";
                throw new NfephpException($msg);
            }
            //verifica o digest
            if ($digestSefaz != $digest) {
                $msg = "Os numeros digest não combinam!! digValSEFAZ = " . $digestSefaz . " <> DigestValue = " . $digest."";
                throw new NfephpException($msg);
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return true;
    } // fim verifyNFe


    /**
    * loadSEFAZ
    * Função para extrair o URL, nome do serviço e versão dos webservices das SEFAZ de
    * todos os Estados da Federação do arquivo urlWebServicesNFe.xml
    *
    * O arquivo xml é estruturado da seguinte forma :
    * <WS>
    *   <UF>
    *      <sigla>AC</sigla>
    *          <homologacao>
    *              <Recepcao service='nfeRecepcao' versao='1.10'>http:// .....
    *              ....
    *          </homologacao>
    *          <producao>
    *              <Recepcao service='nfeRecepcao' versao='1.10'>http:// ....
    *              ....
    *          </producao>
    *   </UF>
    *   <UF>
    *      ....
    * </WS>
    *
    * @name loadSEFAZ
    * @param  string $spathXML  Caminho completo para o arquivo xml
    * @param  string $tpAmb  Pode ser "2-homologacao" ou "1-producao"
    * @param  string $sUF       Sigla da Unidade da Federação (ex. SP, RS, etc..)
    * @return mixed             false se houve erro ou array com os dado do URLs das SEFAZ
    */
    public function loadSEFAZ($spathXML, $tpAmb = '', $sUF = '')
    {
        try {
            //verifica se o arquivo xml pode ser encontrado no caminho indicado
            if (file_exists($spathXML)) {
                //carrega o xml
                $xml = simplexml_load_file($spathXML);
            } else {
                //sai caso não possa localizar o xml
                $msg = "O arquivo xml não pode ser encontrado no caminho indicado $spathXML.";
                throw new NfephpException($msg);
            }
            $aUrl = null;
            //testa parametro tpAmb
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if ($tpAmb == '1') {
                $sAmbiente = 'producao';
            } else {
                //força homologação em qualquer outra situação
                $tpAmb = '2';
                $sAmbiente = 'homologacao';
            }
            //extrai a variável cUF do lista
            $alias = $this->aliaslist[$sUF];
            if ($alias == 'SVAN') {
                $this->enableSVAN = true;
            } else {
                $this->enableSVAN = false;
            }
            //estabelece a expressão xpath de busca
            $xpathExpression = "/WS/UF[sigla='" . $alias . "']/$sAmbiente";
            //para cada "nó" no xml que atenda aos critérios estabelecidos
            foreach ($xml->xpath($xpathExpression) as $gUF) {
                //para cada "nó filho" retonado
                foreach ($gUF->children() as $child) {
                    $u = (string) $child[0];
                    $aUrl[$child->getName()]['URL'] = $u;
                    // em cada um desses nós pode haver atributos como a identificação
                    // do nome do webservice e a sua versão
                    foreach ($child->attributes() as $a => $b) {
                        $aUrl[$child->getName()][$a] = (string) $b;
                    }
                }
            }
            //verifica se existem outros serviços exclusivos para esse estado
            if ($alias == 'SVAN' || $alias == 'SVRS') {
                $xpathExpression = "/WS/UF[sigla='" . $sUF . "']/$sAmbiente";
                //para cada "nó" no xml que atenda aos critérios estabelecidos
                foreach ($xml->xpath($xpathExpression) as $gUF) {
                    //para cada "nó filho" retonado
                    foreach ($gUF->children() as $child) {
                        $u = (string) $child[0];
                        $aUrl[$child->getName()]['URL'] = $u;
                        // em cada um desses nós pode haver atributos como a identificação
                        // do nome do webservice e a sua versão
                        foreach ($child->attributes() as $a => $b) {
                            $aUrl[$child->getName()][$a] = (string) $b;
                        }
                    }
                }
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return $aUrl;
    } //fim loadSEFAZ

    /**
     * getNumLot
     * Obtêm o numero do último lote de envio
     *
     * @name getNumLot
     * @return numeric Numero do Lote
     */
    protected function getNumLot()
    {
        $lotfile = $this->raizDir . 'config/numloteenvio.xml';
        $domLot = new DomDocument;
        $domLot->load($lotfile);
        $num = $domLot->getElementsByTagName('num')->item(0)->nodeValue;
        if (is_numeric($num)) {
            return $num;
        } else {
            //arquivo não existe, então suponho que o numero seja 1
            return 1;
        }
    }//fim getNumLot

    /**
     * putNumLot
     * Grava o numero do lote de envio usado
     *
     * @name putNumLot
     * @param numeric $num Inteiro com o numero do lote enviado
     * @return boolean true sucesso ou FALSO erro
     */
    protected function putNumLot($num)
    {
        if (is_numeric($num)) {
            $lotfile = $this->raizDir . 'config/numloteenvio.xml';
            $numLot = '<?xml version="1.0" encoding="UTF-8"?><root><num>' . $num . '</num></root>';
            if (!file_put_contents($lotfile, $numLot)) {
                //em caso de falha retorna falso
                $msg = "Falha ao tentar gravar o arquivo numloteenvio.xml.";
                $this->setError($msg);
                return false;
            }
        }
        return true;
    } //fim putNumLot

    /**
     * getUltNSU
     * Pega o ultimo numero NSU gravado no arquivo numNSU.xml
     *
     * @name getUltNSU
     * @param type $sigla sigla do estado (UF)
     * @param type $tpAmb tipo de ambiente 1-produção ou 2 homologação
     * @return mixed o numero encontrado no arquivo ou false em qualquer outro caso
     */
    private function getUltNSU($sigla = '', $tpAmb = '')
    {
        try {
            if ($sigla=='' || $tpAmb=='') {
                $msg = "Tanto a sigla do estado como o ambiente devem ser informados.";
                throw new NfephpException($msg);
            }
            $nsufile = $this->raizDir . 'config/numNSU.xml';
            if (!is_file($nsufile)) {
                $msg = "O arquivo numNSU.xml não está na pasta config/.";
                throw new NfephpException($msg);
            }
            //buscar o ultimo NSU no xml
            $xml = new SimpleXMLElement($nsufile, null, true);
            $searchString = '/NSU/UF[@sigla="'.$sigla.'" and @tpAmb="'.$tpAmb.'"]';
            $ufn = $xml->xpath($searchString);
            $ultNSU = (string) $ufn[0]->ultNSU[0];
            if ($ultNSU == '') {
                $ultNSU = '0';
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return $ultNSU;
    }//fim getUltNSU

    /**
     * putUltNSU
     * Grava o ultNSU fornecido pela SEFAZ
     *
     * @name putUltNSU
     * @param type $sigla sigla do estado (UF)
     * @param type $tpAmb tipo de ambiente
     * @param type $ultNSU Valor retornado da consulta a SEFAZ
     * @return boolean true gravado ou false falha
     */
    private function putUltNSU($sigla, $tpAmb, $ultNSU = '')
    {
        try {
            if ($sigla=='' || $tpAmb=='' || $ultNSU=='') {
                $msg = "A sigla do estado, o tipo de ambiente e o numero do ultimo NSU são obrigatórios.";
                throw new NfephpException($msg);
            }
            $nsufile = $this->raizDir . 'config/numNSU.xml';
            if (!is_file($nsufile)) {
                $msg = "O arquivo numNSU.xml não está na pasta config/.";
                throw new NfephpException($msg);
            }
            //buscar o ultimo NSU no xml
            $xml = new SimpleXMLElement($nsufile, null, true);
            $searchString = '/NSU/UF[@sigla="'.$sigla.'" and @tpAmb="'.$tpAmb.'"]';
            $ufn = $xml->xpath($searchString);
            if ($ufn[0]->ultNSU[0] != '') {
                $ufn[0]->ultNSU[0] = $ultNSU;
            }
            if (!file_put_contents($nsufile, $xml->asXML())) {
                $msg = "O arquivo não pode ser gravado na pasta config/.";
                throw new NfephpException($msg);
            }
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return true;
    }//fim putUltNSU

    /**
     * solveVersionErr
     * Esta função corrige automaticamente todas as versões dos
     * webservices sempre que ocorrer o erro 238 ou 239
     * no retorno de qualquer requisição aos webservices
     *
     * @name solveVersionErr
     * @param string $xml xml retornado da SEFAZ
     * @param string $UF sigla do estado
     * @param numeric $tpAmb tipo do ambiente
     * @param string $metodo método
     */
    private function solveVersionErr($xml = '', $UF = '', $tpAmb = '', $servico = '', $versaodefault = '')
    {
        //quando ocorre esse erro o que está errado é a versão indicada no arquivo nfe_ws2.xml
        // para esse método, então nos resta ler o retorno pegar o numero correto da versão,
        // comparar com o default e caso sejam diferentes corrigir o arquivo nfe_ws2.xml
        try {
            if ($tpAmb == '') {
                $tpAmb = $this->tpAmb;
            }
            if ($tpAmb == '1') {
                $sAmbiente = 'producao';
            } else {
                //força homologação em qualquer outra situação
                $sAmbiente = 'homologacao';
            }
            if ($this->enableSCAN) {
                $UF = 'SCAN';
            }
            //habilita verificação de erros
            libxml_use_internal_errors(true);
            //limpar erros anteriores que possam estar em memória
            libxml_clear_errors();
            //carrega o xml de retorno com o erro 239
            $doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            //recupera os erros da libxml
            $errors = libxml_get_errors();
            if (!empty($errors)) {
                //houveram erros no xml ou o arquivo não é um xml
                $msg = "O xml retornado possue erros ou não é um xml.";
                throw new NfephpException($msg, self::STOP_MESSAGE);
            }
            $cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $versao= !empty($doc->getElementsByTagName('versaoDados')->item(0)->nodeValue) ? $doc->getElementsByTagName('versaoDados')->item(0)->nodeValue : '';
            if (($cStat == '239' || $cStat == '238') && $versao != $versaodefault) {
                //realmente as versões estão diferentes => corrigir
                $nfews = $this->raizDir . 'config' . DIRECTORY_SEPARATOR . $this->xmlURLfile;
                if (is_file($nfews)) {
                    //carregar o xml com os webservices
                    $objxml = new SimpleXMLElement($nfews, null, true);
                    foreach ($objxml->UF as $objElem) {
                        //procura dados do UF
                        if ($objElem->sigla == $UF) {
                            //altera o numero da versão
                            $objElem->$sAmbiente->$servico->attributes()->version = "$versao";
                            //grava o xml alterado
                            if (!file_put_contents($nfews, $objxml->asXML())) {
                                $msg = "A versão do serviço $servico de $UF [$sAmbiente] no arquivo $nfews não foi corrigida.";
                                throw new NfephpException($msg, self::STOP_MESSAGE);
                            } else {
                                break;
                            }//fim file_put
                        }//fim elem UF
                    }//fim foreach
                }//fim is file
            }//fim cStat ver=ver
        } catch (NfephpException $e) {
            $this->setError($e->getMessage());
            throw $e;
            return false;
        }
        return true;
    }//fim trata 239

    /**
     *
     */
    public function updateWsdl()
    {
        $wsFile = '../config/nfe_ws2.xml';
        $xml = file_get_contents($wsFile);
        //converte o xml em array
        $ws = XML2Array::createArray($xml);
        //para cada UF
        foreach($ws['WS']['UF'] as $uf) {
            $sigla = $uf['sigla'];
            $ambiente = array('homologacao','producao');
            //para cada ambiente
            foreach($ambiente as $amb) {
                $h = $uf[$amb];
                if (isset($h)) {
                    foreach($h as $k => $j) {
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
