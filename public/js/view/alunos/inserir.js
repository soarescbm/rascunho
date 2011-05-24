$('#alt_senha').click(function(){
  valor = $('input:checkbox[name=alt_senha]:checked').val();

  if(valor == 1){
    $('input:password[name=senha]').attr('disabled', '')
  }else{
    $('input:password[name=senha]').attr('disabled', 'disabled')
   }
});

if($('input:checkbox[name=alt_senha]:checked').val() ==1){
  $('input:password[name=senha]').attr('disabled', '');
}

