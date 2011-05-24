<?php

/**
 * Controlador de sicronização dos dados dos alunos
 *
 * @author Paulo Soares da Silva
 * @copyright P2S System - Soluções Web
 * @package Sysweb
 * @subpackage Default.Controller
 * @version 1.0
 */
class Admin_SincronizaController extends P2s_Controller_Abstract {
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
        Zend_Loader::loadClass('Alunos');
        //$this->setModel(new Configuracao());
        $this->setTituloPagina('Sincronização das Informações dos Alunos');
        $this->setTituloPaginaClass('_config');
        $this->setClientWs();
               

    }
    
    public function  indexAction() {

      
      
      $this->viewAssign();
    }
    public function ajaxAction(){

       $total_etapa = 20000;
       $total_alunos = $this->getClientWs()->getTotalAlunos();

       $num_etapas = ceil($total_alunos/$total_etapa);

       $inicio = 0;
       $tb_alunos = new Alunos();

       echo "Numero de etapas: ".$num_etapas;
       $db = Zend_Db_Table::getDefaultAdapter();
       
       for ($i = 0; $i<$num_etapas; $i++){

            $resultado = $this->getClientWs()->getAlunosAll($inicio,$total_etapa);
            $inicio = ($i + 1) * $total_etapa;
            
            $sql = "INSERT INTO alunos (id, nome, nome_usuario) VALUES ".$resultado ;
            $db->query($sql);
                     
            echo "<br/>Etapa ".($i+1). "Concluída!";
       }
        
       
       //Desativa o Layout
       $this->_helper->layout->disableLayout();
       //Desativa o View
       $this->_helper->viewRenderer->setNoRender();
    }
    
    
   /**
    * Setter Cliente Soap
    * 
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
}