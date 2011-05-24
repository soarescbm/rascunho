<?php


class IndexController extends P2s_Controller_Abstract {

    public function  init(){
      $this->setModule($this->getRequest()->getModuleName());
      $this->setController($this->getRequest()->getControllerName());
      $this->setUrlBase(BASE_URL);
      $this->setUrl($this->getUrlBase().'/'.$this->getModule().'/'.$this->getController());
      $this->setSession(new Zend_Session_Namespace(DEFAULT_SESSION));
        
    }

    public function  indexAction() {

         $this->view->baseUrl = $this->getUrlBase();
    }
    public function  ajaxAction() {

    $cliente_ws = new P2s_Soap_Client();
    $cod_aluno = Zend_Registry::get('Id');

    try{

      $cliente_ws = new P2s_Soap_Client();
      $dados = $cliente_ws->getAluno($cod_aluno);

    }catch (SoapFault $e){

      echo $e->getMessage();
    }

    $this->getSession()->dados_aluno = $dados;
    
    $this->_helper->layout->disableLayout();
    $this->view->baseUrl = $this->getUrlBase();

  }

   
}
?>
