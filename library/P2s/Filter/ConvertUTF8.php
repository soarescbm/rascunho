<?php
/**
 *
 * Filter de conversão para UTF8
 *
 * @filesource
 * @author Paulo Soares da Silva
 * @copyright P2S  Pystem Soluções Web - 2010
 * @package SysWeb
 * @subpackage view.helpers
 * @version 1.0
 */
class P2s_Filter_ConvertUTF8  {
	
            
    public static function getUTF8($texto){
    	  
             $texto = utf8_encode($texto);
             
          return ($texto);
    }
}

