let count = document.querySelectorAll('.cart-product-wrap').length;
let fields = ['size', 'addon', 'flavor'];

$('#productDetail').submit(function () {

  let form = $('#cart_list .items');
  let cart_item = form.find('.cart-product-wrap.d-none').clone();
  let total = parseFloat($('.sent-to-cart-btn .price').attr('data-price'));

  cart_item.removeClass('d-none');
  cart_item.find('.title').text($(this).find('.product-details-wrap .title').text());
  cart_item.find('.price').text($('.sent-to-cart-btn .price').text());
  cart_item.attr('data-total',total);

  let input = document.createElement('input');
  input.setAttribute('name', 'item_' + count + '[id]');
  input.setAttribute('value', item);
  input.setAttribute('type', 'hidden');

  cart_item.attr('data-items','id:'+item);
  cart_item.append(input);

  fields.forEach(e => {

    let field = $('input[name^="' + e + '_' + item + '"]:checked');

    if (field !== undefined) {

      cart_item.attr('data-items',cart_item.attr('data-items')+';'+e+':');

      for (i = 0; i < field.length; i++) {
          
        let id = field[i].value;  
        input = document.createElement('input');

        if(e == 'size'){
          input.setAttribute('name', 'item_' + count + '[' + e + ']');
        }else{
          input.setAttribute('name', 'item_' + count + '[' + e + '][' + i + ']');
        }
        
        if(e == 'addon'){
          regex = /.*\[(.*)\].*/; id = regex.exec(field[i].name)[1];
          input.setAttribute('value', id+','+field[i].value);
        }else{
           input.setAttribute('value', field[i].value);
        }

        input.setAttribute('type', 'hidden');

        if(i+1 == field.length){
          cart_item.attr('data-items',cart_item.attr('data-items')+id);
        }else{
          cart_item.attr('data-items',cart_item.attr('data-items')+id+',');
        }

        cart_item.append(input);
      }

    }

  });

  input = document.createElement('input');
  input.setAttribute('name', 'item_' + count + '[obs]');
  input.setAttribute('value', $(this).find('textarea').val());
  input.setAttribute('type', 'hidden');

  cart_item.attr('data-items',cart_item.attr('data-items')+';obs:'+input.value);
  cart_item.append(input);

  input = document.createElement('input');
  input.setAttribute('name', 'item_' + count + '[qty]');
  input.setAttribute('value', $(this).find('.qty').val());
  input.setAttribute('type', 'hidden');
  input.classList.add('qty');

  cart_item.find('.qty').text('x' + $(this).find('.qty').val());
  cart_item.append(input);

  if($('[data-items="'+cart_item.attr('data-items')+'"]').length > 0){
    let qty = $('[data-items="'+cart_item.attr('data-items')+'"]').find('input.qty');
    qty.val((parseInt(qty.val())+parseInt($(this).find('.qty').val())));
    $('[data-items="'+cart_item.attr('data-items')+'"]').find('.qty').text('x' + qty.val());
    $('[data-items="'+cart_item.attr('data-items')+'"]').find('.price').text('R$'+String(total*parseFloat(qty.val())));
  }else{
    form.append(cart_item);
    count++;
  }

  $('.product-details-area .back-page-btn').click();
  $('.main-footer-bottom').removeClass('d-none');

});

$(document).on('click','#productDetail li', function(){

  let total;

  let qty   = parseFloat($('#productDetail .qty').val());
  total = (qty*soma);
  
  $('.sent-to-cart-btn .price').empty().append('R$' + real(String(total).replace('.', ',')));

});

$('#productDetail .qty').change(function(){

  let total;

  let qty   = parseFloat($(this).val());
  total = (qty*soma);

  $('.sent-to-cart-btn .price').empty().append('R$' + real(String(total).replace('.', ',')));
  $('.sent-to-cart-btn .price').attr('data-price',total);
  
});