<?php

class BoletimController extends P2s_Controller_Abstract {

  public function  init(){
      
    $this->setModule($this->getRequest()->getModuleName());
    $this->setController($this->getRequest()->getControllerName());
    $this->setUrlBase(BASE_URL);
    $this->setUrl($this->getUrlBase().'/'.$this->getModule().'/'.$this->getController());
    $this->setSession(new Zend_Session_Namespace(DEFAULT_SESSION));
  }

  public function  indexAction() {

    $dados = $this->getSession()->dados_aluno;

    foreach ($dados['matriculas'] as $matricula){
       if($matricula['ultima_matricula']==1){
         $cod_matricula = $matricula['cod_matricula'];
         break;
       }
    }

    try{

      $cliente_ws = new P2s_Soap_Client();
      $boletim = $cliente_ws->getBoletim(33482);

    }catch (SoapFault $e){

      echo $e->getMessage();
    }



    $this->_helper->layout->disableLayout();
    $this->view->baseUrl = $this->getUrlBase();
    $this->view->dados_aluno = $dados;
    $this->view->boletim = $boletim;
    $this->view->tabela = $this->gerarBoletim($boletim);
    
  }

  protected function gerarBoletim($boletim){

    $tag = new P2s_Html_Tag();
    $dados = $boletim;
    $labels = array();
    $atributos = array('align'=>'center');
    
    $modulos = range(1, $dados['modulos']);

    //Labels da tabela
    $labels[] = array('data'=>'Disciplinas','attributes'=>array('width'=>'20%'));
    //falta por componente
    $atributos['colspan'] =  $dados['faltaporcomponente']? 2:1;
    foreach($modulos as  $modulo){
      $labels[] = array('data'=>'Módulo - '.$modulo,'attributes'=>$atributos);
    }
    unset($atributos['colspan']);

    if($dados['recuperacao']){
      $labels[] = array('data'=>'Exame','attributes'=>$atributos);
    }
    if(!$dados['semnota']){
      $labels[] = array('data'=>'Média','attributes'=>$atributos);
    }
    $labels[] = array('data'=>'Presença (%)','attributes'=>$atributos);
    $labels[] = array('data'=>'Situação','attributes'=>$atributos);

    //Sublabels caso as faltas sejam por componente
    if($dados['faltaporcomponente']){
      $sublabels = array();
      $sublabels[] = array('data'=>'','attributes'=>$atributos);
      
      foreach($modulos as  $modulo){
        if(!$dados['semnota']){
          $sublabels[] = array('data'=>'Nota','attributes'=>$atributos);
        }
        $sublabels[] = array('data'=>'Faltas','attributes'=>$atributos);
      }
      if($dados['recuperacao'] && !$dados['semnota']){
        $atributos['colspan'] = 4;
      }elseif(!$dados['semnota']){
        $atributos['colspan'] = 3;
      }elseif($dados['recuperacao'] && $dados['semnota'] ){
        $atributos['colspan'] = 3;
      }else{
        $atributos['colspan'] = 2;
      }
       
       $sublabels[] = array('data'=>'','attributes'=>$atributos);
       unset($atributos['colspan']);
    }

    //dados da tabela
    $datas = array();
    $linha = "";
    $i = 0;
    foreach($dados['componentes'] as $id => $componente){
      $datas[$i][] = array('data'=> P2s_Filter_ConvertUTF8::getUTF8($componente), 'attributes'=>$atributos);

      foreach($modulos as $modulo){
        if(!$dados['semnota']){
            $datas[$i][] = array('data'=>$dados['notas'][$id][$modulo],'attributes'=>$atributos);
          }
         else{
           $datas[$i][] = array('data'=>'','attributes'=>$atributos);
         }

        if($dados['faltaporcomponente']){
           $datas[$i][] = array('data'=>$dados['faltas'][$id][$modulo],'attributes'=>$atributos);
        }
      }
      if($dados['recuperacao']){
        $datas[$i][] = array('data'=>$dados['nota_recuperacao'][$id],'attributes'=>$atributos);
      }
      if(!$dados['semnota']){
        $datas[$i][] = array('data'=>$dados['medias'][$id],'attributes'=>$atributos);
      }
      $datas[$i][] =array('data'=>$dados['presenca_por_componente'][$id],'attributes'=>$atributos);
      $datas[$i][] =array('data'=>P2s_Filter_ConvertUTF8::getUTF8($dados['situacao_por_componente'][$id]),'attributes'=>$atributos);

      $i++;
    }


    foreach ($labels as $label){
      $linha .= $tag->getTag('th', $label['attributes'],$label['data']);
    }
    $linhas = $tag->getTag('tr', array(),$linha);
     
    $linha = "";
    if($dados['faltaporcomponente']){
      foreach ($sublabels as $label){
        $linha .= $tag->getTag('th', $label['attributes'],$label['data']);
      }
    }
    $linhas .= $tag->getTag('tr', array(),$linha);

    $linha = "";

    foreach($datas as $data){
       $linha = "";
       foreach($data as $d){
         $linha .= $tag->getTag('td', $d['attributes'],$d['data']);
       }
       $linhas .= $tag->getTag('tr', array(),$linha);
   }

    $html = $tag->getTag('table', array('width'=>'100%', 'border'=>'0', 'cellspacing'=>'0', 'cellpadding'=>'0'),$linhas);
    
    return $html;
  }
}
?>