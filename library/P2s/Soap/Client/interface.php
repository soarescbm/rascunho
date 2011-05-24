<?php

interface P2s_Soap_Client_Interface  {

  public function getAluno($cod_aluno);
  public function getBoletim($matricula);
  public function getAlunosAll($inicio,$limite);
  public function isAuth();
  public function getTotalAlunos();
}
?>
