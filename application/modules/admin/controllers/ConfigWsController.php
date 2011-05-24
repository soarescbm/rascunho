<?php

/**
 * Controlador das acões ao sistema
 *
 * @author Paulo Soares da Silva
 * @copyright P2S System - Soluções Web
 * @package Sysweb
 * @subpackage Default.Controller
 * @version 1.0
 */
class Admin_ConfigWsController extends P2s_Controller_Abstract {
    /**
     *
     * @var P2s_Soap_Client
     */
     private $_client;
    /**
     * Inicializa a instancia do controlador
     */
    public function init(){
        parent::init();
        
        Zend_Loader::loadClass('Configuracao');
        $this->setModel(new Configuracao());
        $this->setTituloPagina('Configurações do Webservice');
        $this->setTituloPaginaClass('_config');
        $this->setClientWs();
            

    }
    
    public function  indexAction() {

      $form = $this->getForm();
      if($this->_request->isPost()){

         if($form->isValid($_POST)) {
         $this->getModel()->update($form->getValues(), 'id = 1');
         $this->getSession()->mensagem = 'Configurações atualizadas com sucesso!';

         $dados = $this->getModel()->fetchRow('id = 1')->toArray();
         $form->populate($dados);
               
         }else{
            $form->populate($_POST); 
         }

      }else{
        $dados = $this->getModel()->fetchRow('id = 1')->toArray();
        $form->populate($dados);
        
      }


      $this->view->form = $form;

      if($this->getSession()->__isset('mensagem')){
        $this->view->mensagem = $this->getSession()->mensagem;
        $this->getSession()->unsetAll();
      }
      
      $this->viewAssign();



    }
    public function ajaxAction(){
       //Desativa o Layout
       $this->_helper->layout->disableLayout(); 
       try{
          $this->view->resposta = $this->getClientWs()->getStatus();
       }catch (SoapFault $e){
          $this->view->resposta = $e->getMessage(); 
      
       }
    }
    /**
     * Descreve o formulário de cadastro
     * @return Zend_Form
     */
    public function  getForm() {
        $form = parent::getForm();
        $form->setAction($this->getUrl())
             ->setMethod('post');
        
                
        //Atributo uri
        $uri = new Zend_Form_Element_Text('ws_uri');
        $uri->setLabel('Uri:')
            ->setRequired(true)
            ->setAttrib('class', 'input')
            ->setAttrib('size', '50')
            ->addFilter('StringTrim')
            ->addPrefixPath('P2s_Validate', 'P2s/Validate', 'validate')
            ->addValidator('Uri');

       //Atributo user
        $login = new Zend_Form_Element_Text('ws_login');
        $login->setLabel('Login:')
              ->setRequired(true)
              ->setAttrib('class', 'input')
              ->setAttrib('size', '20')
              ->addFilter('StringTrim')
        ->addValidator('StringLength', true, array('4','128'));

        //Atributo senha
        $password = new Zend_Form_Element_Text('ws_password');
        $password->setLabel('Password:')
              ->setRequired(true)
              ->setAttrib('class', 'input')
              ->setAttrib('size', '20')
              ->addFilter('StringTrim')
        ->addValidator('StringLength', true, array('4','128'));

                    
        $form->addElements(array($uri,$login,$password));
        $form->setDecorators($this->getFormNoTableDecorators());
        $form->setElementDecorators($this->getElementDecorators());

        $form->addDisplayGroup(array('ws_uri','ws_login','ws_password'),'ws_config',
                array('legend'=>'Configurações do Webservice'));
        $form->getDisplayGroup('ws_config')->setDecorators($this->getDisplayGroupDecorators());
       

        return $form;
    }
   /**
    * Set Cliente Soap
    * @return bool
    */
    private function setClientWs(){

      $this->_client = new P2s_Soap_Client();

    }
    /**
     * Getter
     * @return P2s_Soap_Client
     */
    private function getClientWs(){

      return $this->_client;
    }
    /**
     * Retorna o status do web services
     * @return string
     */
   
}