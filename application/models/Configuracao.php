<?php 
/**
 * Modelo da tabela ações do sistema
 *
 * @filesource
 * @author Paulo Soares da Silva
 * @copyright P2S System - Soluções Web
 * @package SysWeb
 * @subpackage Default.Model
 * @version 1.0
 */
class Configuracao extends P2s_Db_Table_Abstract {
	
    /**
    * Nome da tabela no banco de dados
    * @var String
    */
    protected $_name = 'configuracao';
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
    }
   
}
