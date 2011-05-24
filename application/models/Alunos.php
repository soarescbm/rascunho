<?php 
/**
 * Modelo da tabela alunos
 *
 * @filesource
 * @author Paulo Soares da Silva
 * @copyright P2S System - Soluções Web
 * @package SysWeb
 * @subpackage Default.Model
 * @version 1.0
 */
class Alunos extends P2s_Db_Table_Abstract {
	
    /**
    * Nome da tabela no banco de dados
    * @var String
    */
    protected $_name = 'alunos';
    /**
     * Coluna da chave primária da tabela
     * @var String | Array
     */
    protected $_primary = 'id';
    

    /**
    * Contrutor do modelo
    */
    public function  __construct() {
        parent::__construct();
        $this->_fieldKey = 'id';
        $this->_orderField = 'id';
        $this->_fieldSearch = array('field'=>'nome','label'=>'Aluno:');
        $this->_fieldLabel = array(
            'id' => 'Id',
            'nome' => 'Nome',
            'nome_usuario'=>'Login'
            );
    }
   
}
