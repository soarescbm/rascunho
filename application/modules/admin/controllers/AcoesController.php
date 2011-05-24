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
class Admin_AcoesController extends P2s_Controller_Abstract {

   
    /**
     * Inicializa a instancia do controlador
     */
    public function init(){
        parent::init();
        Zend_Loader::loadClass('Acoes');
        $this->setModel(new Acoes());
        $this->setTituloPagina('Ações');
        $this->setTituloPaginaClass('_config');
        $this->setInfoForm('Dados da ação');
        $this->setItemNome('Ação');
        $this->setHtmlColunasTabela(array(
            'th'=>array('Nome'=>array( 'align' =>'left', 'width'=>'100' ))));
        
        $this->setMensagens(
                array(
                    'editado'=>' editada com sucesso!',
                    'adicionado'=>' adicionada com sucesso!',
                    'excluido'=>' excluida com sucesso!',
                    'confirma_exclusao'=>'Tem certeza que deseja excluir a ',
                    'nao_encontrado'=>' não encontrada!'
                )
         );

    }
    
    
    /**
     * Descreve o formulário de cadastro
     * @return Zend_Form
     */
    public function  getForm() {
        $form = parent::getForm();
        
        //Atributo id
        $id = new Zend_Form_Element_Hidden('id');
        

        //Atributo nome
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome:');
        $nome->setRequired(true);
        $nome->setAttrib('class', 'input');
        $nome->setAttrib('size', '50');
        $nome->addFilter('StringTrim');
        
        $nome->addValidator('StringLength', true, array('4','30'));
       
        $existeNome = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' =>'acoes',
                    'field' => 'nome',
                    'exclude' => array(
                        'field'=>'id',
                        'value'=> $this->_request->getParam('id')
                    )
                  )
        );
        $nome->addValidator($existeNome);

       
       
       
        $form->addElements(array($id, $nome));
        $form->setElementDecorators($this->getElementDecorators());
        $id->setDecorators($this->getElementHiddenDecorators());

        return $form;
    }
   
}

