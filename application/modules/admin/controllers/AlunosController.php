<?php
/**
 * Controlador de alunos do sistema
 *
 * @author Paulo Soares da Silva
 * @copyright P2S System - Soluções Web
 * @package Sysweb
 * @subpackage Default.Controller
 * @version 1.0
 */
class Admin_AlunosController extends P2s_Controller_Abstract {

   
    /**
     *Inicializa a instancia do objeto
     *@return void
     */
    public function init(){
        parent::init();
        Zend_Loader::loadClass('Alunos');


        $this->setModel( new Alunos());
        $this->setTituloPagina('Alunos do i-Educar');
        $this->setTituloPaginaClass('_config');
        $this->setInfoForm('Dados do Aluno');
        $this->setItemNome('Aluno');
        $this->setHtmlColunasTabela( array(
            'th'=>array('Nome'=>array( 'align' =>'left', 'width'=>'250' ),
                        'Login'=>array( 'align' =>'left', 'width'=>'100' ))
            
                ));
              
    }
    public function inserirAction(){

        $form = $this->getForm();

        if($this->_request->isPost()){

            if ($form->isValid($_POST)){

                   $id = $form->getValue('id');
                   $dados['nome'] = $form->getValue('nome');
                   $dados['nome_usuario'] = $form->getValue('nome_usuario');
                   $dados['email'] = $form->getValue('email');
                   
                   if($form->getValue('alt_senha')==1){

                     $dados['senha'] = $form->getValue('senha');
                   }
                   if(empty($id)){

                            //Inserção
                            $this->getModel()->insert($dados);
                            $this->getSession()->mensagem = $this->getItemNome()." adicionado com sucesso!";
                            $this->_redirect($this->getModule().'/'.$this->getController().'/listar');
                     }
                     else{

                            //Edição
                            $where = $this->getModel()->getAdapter()->quoteInto('id = ?',$id);
                            $this->getModel()->update($dados,$where);
                            $this->getSession()->mensagem = $this->getItemNome()." editado com sucesso!";
                            $this->_redirect($this->getModule().'/'.$this->getController().'/listar');

                    }
                 }

            } else {

                $form->populate($_POST);

            }

        $this->view->form = $form;
        $this->viewAssign();
        //$this->view->headScript()->appendFile("portal-aluno/public/js/view/alunos/inserir.js");
        $this->render('inserir', null, true);

        }

     public function  getForm() {
        $form = parent::getForm();

        //Atributo id
        $id = new Zend_Form_Element_Hidden('id');

        //Atributo nome
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome:')
             ->setRequired(true)
             ->setAttrib('class', 'input')
             ->setAttrib('size', '60')
             ->addFilter('StringTrim')
             ->addFilter('Alpha',array ('allowwhitespace' => true));

         //Atributo nome de usuário
        $nome_user = new Zend_Form_Element_Text('nome_usuario');
        $nome_user->setLabel('Nome de Usuário:')
             ->setRequired(true)
             ->setAttrib('class', 'input')
             ->setAttrib('size', '20')
             ->addFilter('StringTrim');

        //checkbox alterar senha
        $alt_senha = new Zend_Form_Element_Checkbox('alt_senha');
        $alt_senha->setLabel('Alterar Senha:');




         //Atributo nome de usuário
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email:')
             ->setRequired(false)
             ->setAttrib('class', 'input')
             ->setAttrib('size', '40')
             ->addFilter('StringTrim');

        //Atributo senha
        $senha = new Zend_Form_Element_Password('senha');
        $senha->setRequired(false)
              ->setLabel('Senha:')
              ->setAttrib('class', 'input')
              ->setAttrib('disabled', 'disabled')
              ->setAttrib('size', '20')
              ->setAttrib('autocomplete', 'off')
              ->addFilter('StringTrim')
              ->addValidator('StringLength', true, array('6','30'));




        $form->addElements(array($id, $nome,$email,$nome_user,$alt_senha, $senha ));
        $form->setElementDecorators($this->getElementDecorators());
        $id->setDecorators($this->getElementHiddenDecorators());

        return $form;


    }
    
}

