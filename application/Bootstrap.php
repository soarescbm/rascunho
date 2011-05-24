<?php
/**
 * Bootstrap customizado do sistema
 *
 * @author Paulo Soares da Silva
 * @copyright P2S System - Soluções Web
 * @package Sysweb
 * @subpackage Aplication
 * @version 1.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Inicaliza a configuração da data no timezone
     */
    protected function _initConfig(){
        
         date_default_timezone_set('America/Sao_Paulo');
         define('TEMPO_SESSAO', 3600);
         define('DEFAULT_SESSION', 'Zend_Session');
         define('SISTEMA','portal_aluno');
      
    }
     /**
     * Customiza a configuração da View
     */
    protected function _initViewLayout() {
        $this->bootstrap ( 'view' );
        $view = $this->getResource ( 'view' );
        $view->headTitle('::Portal do Aluno::');
        $view->setEncoding('utf-8');
        $view->placeholder('baseUrl')->set('/portal_aluno');
    	  $view->placeholder('baseUrlPath')->set('/portal_aluno/public');
        $info = "Portal do Aluno - Secretaria de Educação - ".date('Y')."<br/>
            Prefeitura Municipal de Arapiraca-AL<br/>";
        $view->placeholder('copyright')->set($info);
        $view->placeholder('titulo_sistema')->set('::Portal do Aluno::');
        
        //Configuração do template
        //Admin
        $view->placeholder('template')->set('portal-aluno-admin');
        $view->placeholder('template-site')->set('portal-aluno-default');
         
    }
     /**
     * Configura o AutoLoder
     */
    protected function _initAutoLoader() {
        $autoloader = Zend_Loader_Autoloader::getInstance ();
        $autoloader->registerNamespace ('P2s');
        $autoloader->registerNamespace ('ZendX');
      

       
    }
     /**
     * Configura o Translate
     */
    protected function _initTranslate()
    {
        //Tradução da mensagem do validador para o português
        $translate = new Zend_Translate(
                        'Array',
                        APPLICATION_PATH . '/../languages/pt_BR/Zend_Validate.php'
                );
        Zend_Validate_Abstract::setDefaultTranslator($translate);
        
    }
    /**
     * Configura envio de dados atrabés do método post
     */
    protected function _initPost()
	{
		
		$filterInput = new Zend_Filter_Input(null,null,$_POST);
                Zend_Registry::set('post', $filterInput);
	}
    /**
     * Retistro de Plugins
     */
    protected function _initPlugins() {
		$bootstrap = $this->getApplication ();
		if ($bootstrap instanceof Zend_Application) {
			$bootstrap = $this;
		}
		$bootstrap->bootstrap ( 'FrontController' );
		$front = $bootstrap->getResource ( 'FrontController' );

		$plugin_layout = new P2s_Plugin_Layout();
		$front->registerPlugin ( $plugin_layout );

	}

   


}