<?php

require_once 'Zend/Validate/Abstract.php';
require_once 'Zend/Uri.php';

/**
 * Validador para Uri
 */
class P2s_Validate_Uri extends Zend_Validate_Abstract
{
    const URI_INVALID  = 'uri_invalid';

    protected $_messageTemplates = array(
        self::URI_INVALID   => "'%value%' não é uma uri válida",
              
    );

    public function isValid($value)
    {
        
       $this->_setValue((string) $value);

       if(Zend_Uri::check($value)){
          return true;
       }else{
          $this->_error(self::URI_INVALID);
          return false;
       }
    }
}
