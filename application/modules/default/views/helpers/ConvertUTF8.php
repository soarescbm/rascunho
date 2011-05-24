<?php
/**
 *
 * Helper de informação de data
 *
 * @filesource
 * @author Paulo Soares da Silva
 * @copyright P2S  Pystem Soluções Web - 2010
 * @package SysWeb
 * @subpackage view.helpers
 * @version 1.0
 */
class Zend_View_Helper_ConvertUTF8  {
	
    
    
    
    public function ConvertUTF8($texto){
    	  
             $texto = utf8_encode($texto);
             
          return ($texto);
    }
}

