<?php
require_once 'Zend/Soap/Client.php';
require_once 'Configuracao.php';


class P2s_Soap_Client implements P2s_Soap_Client_Interface {
  
  const URI_INVALIDA = "uri_invalida";
  const CONEXAO_INVALIDA = "conexao_invalida";
  const AUTH_OK = "auth_valida";
  const AUTH_INVALIDA = "auth_invalida";
  
  private $mensagem = array(
      
      "uri_invalida"=>"Uri não definida ou inválida.",
      "conexao_invalida"=>"Falha na conexão.",
      "auth_valida"=>"Conexão estabelecida com sucesso.",
      "auth_invalida"=>"Falha na autenticação.",
      
  );


  /**
   * Options
   * @var array
   */
  private $_options = array(
    'uri'=>null,
    'location'=>null,
    'login'=>null,
    'password'=>null,
    'encoding'=>'ISO-8859-1'

  );
  /**
   * Wsdl
   * @var Zend_Soap_Wsdl
   */
  private $_wsdl;

  /**
   * Cliente
   * @var Zend_Soap_Client
   */
  private $_cliente = null;

  /**
   * Construtor
   * @param Zend_Soap_Wsdl $wsdl
   * @param array $options
   */
  function __construct($wsdl = null){
    
    if($wsdl != null){
      $this->setWsdl($wsdl);
    }else{
      $this->setConfig();
    }
       
    $this->setCliente();
  
  }
 
  /**
   * Setter Options
   * @param arry $options 
   */
  public function setOptions($options){
    if(array_key_exists('uri', $options)){
      $this->_options['uri'] = $options['uri'];
    }
    if(array_key_exists('location', $options)){
      $this->_options['location'] = $options['location'];
    }else{
      $this->_options['location'] = $options['uri'];
    }
    if(array_key_exists('login', $options)){
      $this->_options['login'] = $options['login'];
    }
    if(array_key_exists('password', $options)){
      $this->_options['password'] = $options['password'];
    }

  }
  /**
   * Getter Options
   * @return array
   */
  public function getOptions(){
    return $this->_options;
  }
  /**
   * Sette Wsdl
   * @param Zend_Soap_Wsdl $wsdl
   */
  public function setWsdl($wsdl){
    $this->_wsdl  = $wsdl;
  }
  /**
   * Getter Wsdl
   * @return Zend_Soap_Wsdl
   */
  public function getWsdl(){
    return $this->_wsdl;
  }

   /**
   * Setter Cliente
   * 
   */
   protected  function setCliente(){
    $dados = $this->getOptions();
    if(!Zend_Uri::check($dados['uri'])){
      return false;
    }
    
    try{
      $cliente = new Zend_Soap_Client($this->getWsdl(), $this->getOptions());
      $this->_cliente  = $cliente;
    }
    catch (Exception $e){
       echo $e->getMessage();
    }
    return true;
  }
  
  /**
   * Getter Cliente
   * @return Zend_Soap_Client
   */
  public function getCliente(){
    return $this->_cliente;
  }
  /**
   * Setter as Configurações do wbservices
   */
  protected function setConfig(){
    
     Zend_Loader::loadClass('Configuracao');
     $tb_config = new Configuracao();
     $dados = $tb_config->fetchRow('id = 1');
      
     $param = array();
     $param['uri'] = $dados->ws_uri;
     $param['login'] = $dados->ws_login;
     $param['password'] = $dados->ws_password;
     
     $this->setOptions($param);
  }
  /**
   * Retorna o status do web services
   * @return string
   */
  public function getStatus(){

      if($this->getCliente() == null){
        return $this->getMensagem(self::URI_INVALIDA);
      }else{
        
        try{
          
          $resultado = $this->isAuth();

        }catch(SoapFault $e){
           
           return $this->getMensagem(self::CONEXAO_INVALIDA);
        }

        if($resultado){
           return $this->getMensagem(self::AUTH_OK);
        }
        else{
           return $this->getMensagem(self::AUTH_INVALIDA);
        }
      }
      
   }
   /**
    * Getter mensagens de status
    * @param const $key
    * @return string 
    */
   public function getMensagem($key){
     
     return $this->mensagem[$key];
   }

  
  /**
   * Getter os dados do cadastro do aluno
   * @param int $cod_aluno
   * @return array
   */
  public function getAluno($cod_aluno){

    $resultado = $this->getCliente()->getDados($cod_aluno);
        
    return $resultado;

  }
  /**
   * Getter os dados dos boletins do aluno
   * @param int $matricula
   * @return array
   */
  public function getBoletim($matricula){

    $resultado = $this->getCliente()->getBoletim($matricula);

    return $resultado;

  }
  /**
   * Getter os dados dos alunos
   * @param int $matricula
   * @return array
   */
  public function getAlunosAll($inicio,$limite){

    $resultado = $this->getCliente()->getAlunosAll($inicio,$limite);

    return $resultado;

  }
  /**
   * Verifica se o cliente foi autenticado
   * @return bool
   */
  public function isAuth(){
    $resultado =  $this->getCliente()->isAuth();
    return $resultado;
  }
  /**
   * Getter o total de alunos cadastrados
   * @return int
   */
  public function getTotalAlunos(){
    $cont = $this->getCliente()->getTotalAlunos();
    return $cont;
  }
}

 