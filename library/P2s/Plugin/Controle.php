<?php
require_once 'P2s/Acl/Acl.php';
require_once 'Zend/Controller/Plugin/Abstract.php';
require_once 'Zend/Controller/Action/Helper/Redirector.php';

   /**
   * Controle de Acesso ao sistema
   *
   * Verifica se o usuário está autenticado e tem permissão para acessar os recursos
   * do sistema.
   *
   * @author Paulo Soares da Silva
   * @copyright P2S System - Soluções Web
   * @package P2S
   * @subpackage P2s.Plugin
   * @version 1.0
   */
class P2s_Plugin_Controle extends Zend_Controller_Plugin_Abstract {

    private $_auth;
    private $_acl;
    private $_urlLogin = array();
    private $_urlNoAcesso = array();
    private $_urlErro = array();
    private $_perfil;
    private $_tempoSessao;
   


    public function __construct(){
	    	
        $this->_auth= Zend_Auth::getInstance();
        //Tempo de Duração da sessão;
        $this->setTempoSessao(TEMPO_SESSAO);

        //Direciona para o login
        $this->_urlLogin['controller'] = 'login';
        $this->_urlLogin['action'] = '';

        //Direciona para página de permissão negada
        $this->_urlNoAcesso['controller'] = 'error';
        $this->_urlNoAcesso['action'] = 'errorpermissao';


        //Direciona para página de erro
        $this->_urlErro['controller'] = 'error';
        $this->_urlErro['action'] = 'errorpagina';

        
        }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
         $module = strtolower($request->module);
         $controller = strtolower($request->controller);
         $action = strtolower($request->action);

         $this->setPerfil();
         $this->setAcl();

         $flag = false;

         //verifica se existe resource
         if($this->getAcl()->has($module.':'.$controller)){

                if (!$this->getAcl()->isAllowed($this->getPerfil(), $module.':'.$controller, $action )) {

                    //Motivo
                     if(!$this->_auth->hasIdentity()){

                            $controller = $this->_urlLogin['controller'];
                            $action  = $this->_urlLogin['action'];
                            $flag = true;
                     }
                     else{
                            $controller = $this->_urlNoAcesso['controller'];
                            $action  = $this->_urlNoAcesso['action'];
                            $flag = true;
                     }
                }

         }else {

                $controller = $this->_urlErro['controller'];
                $action  = $this->_urlErro['action'];
                $flag = true;
          }
          //Redireciona
          if($flag){

                $this->setUrl($request,$module,$controller, $action);
         }
    }
    	

        private function setPerfil()
        {
            if($this->_auth->hasIdentity()){
                
                $sessionAuth = new Zend_Session_Namespace('Zend_Auth');
                $sessionAuth->setExpirationSeconds($this->getTempoSessao());
                $this->_perfil = $sessionAuth->perfil;

                $identidade = $this->_auth->getIdentity();
                Zend_Registry::set('Id',$identidade->id);
                
            }else {
                
                $this->_perfil = 'visitante';
            }
            Zend_Registry::set('Perfil', $this->getPerfil());

        }
        private function getPerfil()
        {

            return $this->_perfil;

        }
        private function setAcl()
        {
            
            $this->_acl = $acl = new P2s_Acl_Acl();
            Zend_Registry::set('Acl', $this->getAcl());
        }
        private function getAcl()
        {

            return $this->_acl;

        }
       
        private function setUrl(&$request,$module,$controller,$action){
              $request->setModuleName($module);
              $request->setControllerName($controller);
              $request->setActionName($action );
        }

       
        private function setTempoSessao($tempo){

            $this->_tempoSessao = $tempo;
        }
        private function getTempoSessao(){

            return $this->_tempoSessao;
        }
}