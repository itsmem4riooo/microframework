  let subTotal = 0;
  let sent = 0;

  $('.main-footer-bottom').click(()=>{
    
    subTotal = 0;

    $('.cart-page-area').addClass('active');


    let items    = cart_list.getElementsByClassName('cart-product-wrap');

    for(let i = 1; i < items.length; i++){
      subTotal = (parseFloat(items[i].getAttribute('data-total')) + subTotal);
    }

    $('.cart-page-area').find('.order-cart .total span').text('R$'+real(String(subTotal)));

  });

$('#cart_list').submit(()=>{

    let form = new FormData($('#cart_list')[0]);
  
    $.ajax({
        url: 'ajax/request/'+$('body').attr('id'),
        type: 'post',
        dataType: 'html',
        cache: false,
        processData: false,
        contentType: false,
        timeout: 8000,
        data: form,
        success: e => {

          res = JSON.parse(e);

          if(res.class == 'danger'){

            let alert = $('.cart-page-area .alerts .alert-danger');

            alert.fadeIn();
            alert.find('.msg').text(res.msg);

            alert.find('.btn-close').click(()=>{
              alert.fadeOut();
            });

          }

          if(res.class == 'success'){
            $('.cart-page-area').removeClass('active');
            $('.payments').addClass('active');
            $('.payment-wrap .order-cart .subtotal').empty().append('Subtotal: R$'+real(String(subTotal)));
            $('.payment-wrap .order-cart .total').empty().append('Total: R$'+real(String(subTotal)));
            $('#send_whatsapp').attr('href','https://obafood.app/request/'+res.id);
          }

        }
    });
  
    return false;
  
  });

  $('#finishRequest').submit(()=>{

    let form = new FormData($('#finishRequest')[0]);
  
    $.ajax({
        url: 'ajax/finishRequest/'+$('body').attr('id'),
        type: 'post',
        dataType: 'html',
        cache: false,
        processData: false,
        contentType: false,
        timeout: 8000,
        data: form,
        success: e => {
          res = JSON.parse(e);

          if(res.class == 'danger'){

            let alert = $('.payments .alerts .alert-danger');

            alert.fadeIn();
            alert.find('.msg').text(res.msg);

            alert.find('.btn-close').click(()=>{
              alert.fadeOut();
            });

          }

          if(res.class == 'success'){

            $('.status').addClass('active');
            $('.payments').removeClass('active');

            let timer = setInterval(function(){
            $.ajax('ajax/checkRequest/'+res.id).done(function(e){

              if(e == 1){
                $('.status').find('h6').fadeOut();
                $('.status .loading_request').fadeOut(function(){
                  $('.status .confirm_request').fadeIn();
                  $('.status').find('h6').text('Pedido confirmado!!').fadeIn();
                });
                clearInterval(timer);
              }
              if(e == 5){
                $('.status').find('h6').fadeOut();
                $('.status .loading_request').fadeOut(function(){
                  $('.status .recused_request').fadeIn();
                  $('.status').find('h6').text('Seu pedido foi recusado!').fadeIn();
                });
                clearInterval(timer);
              }
            });
          },1000);

          }
        }
    });
  
    return false;
  
  });

  $('.phone').keyup(function(){
    
    if($(this).val().length >= '14'){ 
     $(this).mask('(00) 00000-0000');
    }else{
    
    $(this).mask('(00) 0000-00000');
    } 
    
});

$('.payment_option').click(function(){

  $('.payment_option').removeClass('selected');

  if($(this).find('input').val() == 1){
    $('.cashback').fadeIn();
  }else{
    $('.cashback').fadeOut();
  }

  if(!$(this).find('input').is(':checked')){
      $('.payment_option input').removeAttr('required');
      $(this).find('input').prop('checked',true);
      $(this).addClass('selected');
      $(this).find('input').attr('required','required');
  }else{
    $(this).addClass('selected');
  } 

});

$(document).on('click','.address',function(){

  $('.address').removeClass('selected');

  if(!$(this).find('input').is(':checked')){

    $(this).find('input').prop('checked',true);
    $(this).addClass('selected');

    if($(this).find('input').val() == 1){

      $('.districts').fadeOut();
      $('.districts select').removeAttr('required');

      if($('.districts select').val() != undefined){
        $('.payment-wrap .order-cart .total').text('Total R$'+real(String((subTotal+sent)-sent)));
        $('.payment-wrap .order-cart .send').text('Entrega R$0,00');
        sent = 0; $('.districts select').val([]);
      }

    }else{
      $('.districts').fadeIn();
      $('.districts select').attr('required','required');
    }

  }

});

$('.districts select').change(function(){


  if($('.address.selected input').val() != undefined && $('.address.selected input').val() != 1){
    sent = parseFloat($(this).find('option:selected').attr('data-price'));
    $('.payment-wrap .order-cart .total').text('Total R$'+real(String(sent+subTotal)));
    $('.payment-wrap .order-cart .send').text('Entrega: R$'+real(String(sent)));
  }else{
    alert('Por favor selecione um endereço!');
  }

});

$(document).on('click','.cart-product-wrap button',function(){
   
    subTotal -= parseFloat($(this)[0].parentElement.parentElement.getAttribute('data-total'));
    $('.cart-page-area').find('.order-cart .total span').text('R$'+real(String(subTotal)));
    

    
    $(this)[0].parentElement.parentElement.remove();
    
    if( $('.cart-product-wrap').length == 1){
        $('.main-footer-bottom').addClass('d-none');
        $('.cart-page-area').removeClass('active');
    }
    
});

$('.cashback input').maskMoney({prefix:'R$', allowNegative: false, thousands:'.', decimal:',', affixesStay: false});
