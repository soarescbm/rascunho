<?php

class MeusDocumentosController extends P2s_Controller_Abstract {

  public function  init(){
      
    $this->setModule($this->getRequest()->getModuleName());
    $this->setController($this->getRequest()->getControllerName());
    $this->setUrlBase(BASE_URL);
    $this->setUrl($this->getUrlBase().'/'.$this->getModule().'/'.$this->getController());
    $this->setSession(new Zend_Session_Namespace(DEFAULT_SESSION));
  }

  public function  indexAction() {
   

    $this->_helper->layout->disableLayout();
    
    
  }
   
}
?>