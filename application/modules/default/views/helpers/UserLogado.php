<?php
/**
 *
 * Helper de infomação de autenticação do usuário
 *
 * @filesource
 * @author Paulo Soares da Silva
 * @copyright P2S  Pystem Soluções Web - 2010
 * @package SysWeb
 * @subpackage view.helpers
 * @version 1.0
 */
class Zend_View_Helper_UserLogado  {
        
    
    public function userLogado(){

      $auth = Zend_Auth::getInstance();
      if($auth->hasIdentity()){
        
         Zend_Loader::loadClass('Logacesso');
         $tb_log_acesso = new Logacesso();
         
         $dados_user = $auth->getIdentity();
         $user = $dados_user->nome;
         
         $where = "alunos_id = '".$dados_user->id."'";                   
         $resultado = $tb_log_acesso->fetchAll($where, 'data_hora DESC',2,0)->toArray();
        
         //texto apresentado
         $texto = 'Olá, <strong>'. strtoupper( $user).'</strong>.<br/>';
                    
         if(isset($resultado[1]['data_hora'])){
            
            $dh = explode(' ',$resultado[1]['data_hora']);
          
            $data = explode('-',$dh['0']);
            $hora = explode(':',$dh['1']);
          
            $data_br = $data['2'].'/'.$data['1'].'/'.$data['0'];
            $hora_br = $hora['0'].':'.$hora['1'];
          
            $texto .= 'Seu último acesso foi em '.$data_br.', às '.$hora_br.'.';
                                   
          }
          
          //retorno
           return ($texto);
      }
      return "";
    
    }
}

