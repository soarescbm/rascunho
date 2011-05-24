<?php

/**
 * 
 * Controlador de autenticão
 * 
 * @author Paulo Soares da Silva
 * @copyright P2S System - Soluções Web
 * @package Sysweb
 * @subpackage Default.Controller
 * @version 1.0
 */
class LoginController extends Zend_Controller_Action
{ 
   
   public function init()
    {
       Zend_Loader::loadClass('LogacessoAlunos');
                            
    }

   public function indexAction() 
    {    
      $auth = Zend_Auth::getInstance();
      if($auth->hasIdentity()){
          $this->_redirect('/');
      }

      if($this->_request->isPost()){

          $post = Zend_Registry::get('post');
    	    $user= $post->login;
    	    $pass= $post->senha;
    		
    	     if(empty($user)){

    	     	$this->view->erro_login = 'Informe o nome de usuário';
    	     	
    	     }
    	     
    	     else{
    	     	
    	    	$db = Zend_Db_Table::getDefaultAdapter();
    	     	$autent= new Zend_Auth_Adapter_DbTable($db,'alunos','nome_usuario','senha');
    	     	  
    	     	$autent->setIdentity($user)
    	             ->setCredential($pass);
    	     	$resultado = $autent->authenticate();
    	     	
    	     	if($resultado->isValid()){
    	     		
    	     		$auth = Zend_Auth::getInstance();
    	     		$dados = $autent->getResultRowObject(NULL,'senha');
                        $auth->getStorage()->write($dados);
              //grava sessões
              $sessionAuth = new Zend_Session_Namespace('Zend_Auth');
              $sessionAuth->perfil = 'aluno';
              $sessionAuth->sistema = SISTEMA;
                           	     	                   
              //grava dados do acesso
              $this->_gravaLogin();
                       
                                               
              $this->_redirect('/');

    	     	       	     	    
    	     	}
    	     	else {
    	     		$this->view->erro_login = 'Usuário ou senha inválidos.';
    	     	}
    	     	
    	     	
    	     }
    	
       		
    	}
    
    	
    }
    
    public function logoffAction(){
    	
    	$auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
    	$this->_redirect('/');
    }
   
   
    

    private function _gravaLogin(){

        $tb_log_acesso = new LogacessoAlunos();

        $auth =  Zend_Auth::getInstance();
        $identidade = $auth->getIdentity();

        $dados['alunos_id'] = $identidade->id;
        $dados['ip'] = $ip = getenv('REMOTE_ADDR');

        $tb_log_acesso->insert($dados);

    }
 
   
}
