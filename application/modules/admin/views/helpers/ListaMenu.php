<?php/** * * Helper de geração de menu * * @filesource * @author Paulo Soares da Silva * @copyright P2S  Pystem Soluções Web - 2010 * @package SysWeb * @subpackage view.helpers * @version 1. */class Zend_View_Helper_ListaMenu {               public function listaMenu(){                 $dados_usuario = Zend_Auth::getInstance()->getIdentity();         $db = Zend_Db_Table::getDefaultAdapter();                  $sql = 'SELECT * FROM menu WHERE ativo = 1 ORDER BY indice ASC';         $rows = $db->fetchAll($sql,null,  Zend_Db::FETCH_OBJ);         foreach ($rows as $row) {            $menuItens[$row->sub_menu_id][$row->id] = array( 'link' => $row->url,'name' => $row->nome,'param' => $row->param , 'resource' => $row->aplicacao);        }                   $this->imprimeMenu($menuItens);    }    private function imprimeMenu( array $menuTotal , $idPai = 0, $nivel = 0 ){	// abrimos a ul do menu principal	if ($nivel == 0){            echo '<ul class ="sf-menu" >';        }else {            echo str_repeat( "\t" , $nivel ),'<ul>',PHP_EOL;        }        $acl = Zend_Registry::get('Acl');        $perfil = Zend_Registry::get('Perfil');	// itera o array de acordo com o idPai passado como parâmetro na função	foreach( $menuTotal[$idPai] as $idMenu => $menuItem)	{		                $sub = 0;                if($acl->has(strtolower($menuItem['resource']))){                                       if($acl->isAllowed($perfil,strtolower($menuItem['resource']),'index')){                        echo str_repeat( "\t" , $nivel + 1 ).'<li><a href="'.BASE_URL.'/'.strtolower($menuItem['link']). strtolower($menuItem['param']).'">',$menuItem['name'].'</a>'.PHP_EOL;                        $sub = 1;                    }                }                if($sub == 1){                       // se o menu desta iteração tiver submenus, chama novamente a função                        if( isset( $menuTotal[$idMenu] ) ) $this->imprimeMenu( $menuTotal , $idMenu , $nivel + 2);                        // fecha o li do item do menu                        echo str_repeat( "\t" , $nivel + 1 ),'</li>',PHP_EOL;                }					}	// fecha o ul do menu principal	echo str_repeat( "\t" , $nivel ),'</ul>',PHP_EOL;}    }?>