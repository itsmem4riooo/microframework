
let item;
let flavor = 0; 
let s_flavors = new Array();
let check_flavors = false;
let delivery = $('body').attr('id');
let soma; 

$('.single-item-wrap').click(function () {
    
  $('textarea').val('');
  
  if(!status){
    alert('Não é possível realizar pedidos no momento!');
    return false;  
  }
  
  $('.product-addons').find('.cont').empty();
  $('.sent-to-cart-btn .price').attr('data-price', '0');

  flavor = 0;
  
  item = $(this).attr('data-item');
  
  let min = $(this).attr('data-min');
  let max = $(this).attr('data-max');

  let page = $('.product-details-area');

  page.find('.size').empty();

  page.addClass('active');
  page.find('.product-details-wrap .title').text($(this).attr('data-title'));
  page.find('.sent-to-cart-btn .price').empty().text('R$'+real($(this).attr('data-price'))).attr('data-price', $(this).attr('data-f_price'));
  page.find('.description').text($(this).attr('data-description'));
  page.find('.product-details-wrap img').remove();
  page.find('.product-details-wrap').append($(this).find('.thumb img').clone());
  page.find('.qty').val('1');

  page.find('.scroll').animate({
    scrollTop: 0
  }, 100);

  if(sizes[item] !== undefined) {

    Object.entries(sizes[item]).forEach(([k, v]) => {

      size = document.getElementById('size_' + v.size).cloneNode(true);

      if (size !== null) {

        let option = document.createElement('input');

        option.setAttribute('type', 'radio');
        option.setAttribute('name', 'size_' + item);
        option.setAttribute('required', 'required');
        option.setAttribute('value', v.id);
        option.classList.add('m-2');

        size.querySelector('.title').prepend(option); size.querySelector('.price').append(' R$' + v.price);
        size.setAttribute('data-price', v.f_price);
        page.find('.product-size').removeClass('d-none');
        page.find('.size').append(size);

      }

    });

  }
  
  if($(this).attr('data-flavors') !== ''){
    check_flavors = true;
    let addon = document.querySelector('.product-addons'); addon.classList.remove('d-none');
    let title = addon.querySelector('h6.d-none').cloneNode(true);
    let addons_cont = addon.querySelector('.addons.d-none').cloneNode(true);
    addons_cont.classList.add('flavors');

    title.querySelector('.title').prepend('Sabores');
    title.querySelector('.title').classList.add('flavor_' + item);

    title.classList.remove('d-none');
    addons_cont.classList.remove('d-none');

    title.querySelector('.min').append(min);
    title.querySelector('.max').append(max);

    title.querySelector('.min').classList.add('flavor_' + item);
    title.querySelector('.max').classList.add('flavor_' + item);

    title.querySelector('.min').setAttribute('data-min', min);
    title.querySelector('.max').setAttribute('data-max', max);
    
    let item_flavors = $(this).attr('data-flavors').split(';');
        
    if (flavors[item_flavors[0]].flavor == 0) {
        $('.size .price').prepend('A partir de');
    } else {
        flavor = 1;
    }
    
    item_flavors.forEach(e =>{
        
     if (flavors[e] !== undefined){

          s_flavors.push(flavors[e]); 
         
          let li = addon.querySelector('.addon_option.d-none').cloneNode(true);
          li.querySelector('.addon_qty').remove();
          
          let option = document.createElement('input');
          option.setAttribute('type', 'checkbox');
          option.setAttribute('name', 'flavor_' + item + '[]');
          option.setAttribute('value', flavors[e].id);
          option.classList.add('m-2');

          li.classList.add('flavor');
          li.classList.remove('addon_option');

          if (min > 0) {
            option.setAttribute('required', 'required');
          }

          if(flavors[e].price !== null){
            li.setAttribute('data-price',flavors[e].f_price);
            li.querySelector('.price').append('+R$'+flavors[e].price);
          }

          li.classList.remove('d-none');
          li.setAttribute('id', 'flavor_' + flavors[e].id);
          li.querySelector('.title').prepend(flavors[e].title);          
          li.querySelector('.title').prepend(option);
          li.setAttribute('data-target', 'flavor_' + item);

          addons_cont.append(li);

          addon.querySelector('.cont').append(title); addon.querySelector('.cont').append(addons_cont);

          

      } else {
        flavor = 1;
      }
        
    });
 
  }

    if ($(this).attr('data-addongroup') !== '') {
     
      let addons_list = $(this).attr('data-addons').split(';');      
      let item_addons = $(this).attr('data-addongroup').split(";");
      
      item_addons.forEach(e => { 
        if(addons[e] !== undefined){
            
            let min = addons[e].min; let max = addons[e].max;

            let addon = document.querySelector('.product-addons'); addon.classList.remove('d-none');
            let title = addon.querySelector('h6.d-none').cloneNode(true);
            let addons_cont = addon.querySelector('.addons.d-none').cloneNode(true);

            title.querySelector('.title').prepend(addons[e].title);
            title.querySelector('.title').classList.add(e);

            title.classList.remove('d-none');
            addons_cont.classList.remove('d-none');

            title.querySelector('.min').append(min);
            title.querySelector('.max').append(max);

            title.querySelector('.min').classList.add(e);
            title.querySelector('.max').classList.add(e);

            title.querySelector('.min').setAttribute('data-min', min);
            title.querySelector('.max').setAttribute('data-max', max);

            Object.entries(addons[e]).forEach(([x, y]) => {
              if(x !== 'title' && x !== 'min' && x !== 'max' && addons_list.includes(y.id)){
                
                let li = addon.querySelector('.addon_option.d-none').cloneNode(true);
                let option = document.createElement('input');
                
                option.setAttribute('type', 'checkbox');
                option.setAttribute('name', 'addon_' + item + '['+y.id+']');
                option.setAttribute('value', 0);
                option.classList.add('m-2');

                if (min > 0) {
                  option.setAttribute('required', 'required');
                }

                li.classList.remove('d-none');
                li.setAttribute('data-target', y.addon);
                li.querySelector('.title').append(option);
                li.querySelector('.title').prepend(y.title);
                li.setAttribute('data-price', y.f_price);
                
                li.querySelector('.addon_plus').setAttribute('data-price', y.f_price);
                li.querySelector('.addon_plus').setAttribute('data-option', 'addon_' + item + '['+y.id+']');
                li.querySelector('.addon_plus').setAttribute('data-target', y.addon);
                
                li.querySelector('.addon_minus').setAttribute('data-price', y.f_price);
                li.querySelector('.addon_minus').setAttribute('data-option', 'addon_' + item + '['+y.id+']');
                li.querySelector('.addon_minus').setAttribute('data-target', y.addon);
                
                li.querySelector(".price").append((y.price !== ''  ? '+R$' + y.price : ''));                
                addons_cont.append(li);
                
              }
            });

            addon.querySelector('.cont').append(title); addon.querySelector('.cont').append(addons_cont);
        
        }
        
      });
      
    }

 
    
  soma = parseFloat($('.sent-to-cart-btn .price').attr('data-price'));
  
});

let flavor_price;
            

$(document).on('click', '.size_option', function () {

  let total = parseFloat($('.sent-to-cart-btn .price').attr('data-price'));

  if (flavor == 1) {

    let price = parseFloat($(this).attr('data-price'));
    if ($('.size_option.selected').length > 0) {
      total -= parseFloat($('.size_option.selected').attr('data-price'));
      total += price;
    } else {
      total += price;
    }
  } else {

    if (check_flavors) {
        
      if ($('.size_option.selected').length > 0) {
        total -= flavor_price;
      }
      
      flavor_price = 0.0;

      Object.entries(s_flavors).forEach(([k, v]) => {
 
        let size = sizes[v.id].find(s => s.size == $(this).attr('data-size'));
        let flavor = $('#flavor_' + v.id);

        flavor.find('small').empty();
        flavor.find('.price').empty().append('+R$' + size.price);
        flavor.attr('data-price', size.f_price);

        if (flavor.hasClass('selected')) {

          if (parseFloat(flavor.attr('data-price')) > flavor_price) {
            flavor_price = parseFloat(flavor.attr('data-price'));
          }

        }

      });

      total += flavor_price;
      
      $('.sent-to-cart-btn .price').attr('data-price', total).empty().append('R$' + real(String(total).replace('.', ',')));

    }else{
        if ($('.size_option.selected').length > 0) {
            total -= parseFloat($('.size_option.selected').attr('data-price'));
            total += parseFloat($(this).attr("data-price"));
        }else{
           total += parseFloat($(this).attr("data-price"));
        }
    }

  }



  $('.size_option').removeClass('selected');
  $(this).addClass('selected').find('input').prop('checked', true);
  $('.sent-to-cart-btn .price').attr('data-price', total).empty().append('R$' + real(String(total).replace('.', ',')));
  soma = total;
});

$(document).on('click', '.addons.flavors li', function () {

  let price = 0.00;
  let total = 0.00;
  let target = $(this).attr('data-target');
  let min = $('.' + target + '.min').attr('data-min');
  let max = $('.' + target + '.max').attr('data-max');

  if ($(this).attr('data-price') !== undefined) {

    price = parseFloat($(this).attr('data-price'));

  }

  if ($('.sent-to-cart-btn .price').attr('data-price') !== undefined) {
    total = parseFloat($('.sent-to-cart-btn .price').attr('data-price'));
  }

  if ($(this).hasClass('selected')) {
      
    if (total > 0) {
          
        if (max > 1 && $('.flavor.selected').length > 1) {

          let flavor_price = price;
          let target = $(this).attr('id');
          let max_price = 0;
          let flavors = [];
          let i = 0;

          $('.flavor.selected').each(function () {

            let price = parseFloat($(this).attr('data-price'));

            flavors[i] = { price: price, target: $(this).attr('id') }

            if (flavor_price > price) {
              max_price = flavor_price;
            } else {
              if (price > max_price) {
                target = $(this).attr('id');
                max_price = price;
              }
            }
            i++;
          });

          if ($(this).attr('id') == target) {

            flavors = flavors.filter((e) => { return e.target !== target });

            flavors.sort(function (a, b) {
              if (a.price < b.price) {
                return +1;
              }
              if (a.price > b.price) {
                return -1;
              }
              return 0;
            }
            );

            total -= price;
            total += parseFloat(flavors[0].price);

          }
        } else {
          total -= price;
          flavor_price = 0;
        }

    }

    $(this).removeClass('selected');
    $(this).find('input').prop('checked', false);

  } else {
        
      if ($('[data-target="' + target + '"].selected').length >= max) {
        alert('Número máximo de ' + $('.' + target + '.title').text() + ' atingido!');
        $(this).find('input').prop('checked', false);
        return false;
      }      

      if ($('.flavor.selected').length > 0 && $(this).attr('data-price') !== undefined) {
        
        flavor_price = price;
        
        let target = $(this).attr('id');
        let max_price = 0;
        let flavors = [];
        let i = 1;

        $('.flavor.selected').each(function () {
            
          flavors[i] = { price: $(this).attr('data-price') }

          if (flavor_price > $(this).attr('data-price')) {
            max_price = flavor_price;
          } else {

            if ($(this).attr('data-price') > max_price) {
              target = $(this).attr('id');
              max_price = parseFloat($(this).attr('data-price'));
            }

          }
          i++;
        });
        
        if ($(this).attr('id') == target) {

          flavors.sort(function (a, b) {
            if (a.price < b.price) {
              return +1;
            }
            if (a.price > b.price) {
              return -1;
            }
            return 0;
          }
          );

          total -= parseFloat(flavors[0].price);
          total += flavor_price;
        } else {
          flavor_price = max_price;
        }

      } else {
        flavor_price = price;
        total += price;
      }

       $(this).addClass('selected').find('input').prop('checked', true);


  }

  $('.sent-to-cart-btn .price').attr('data-price', total).empty().append('R$' + real(String(total).replace('.', ',')));

  if ($('[data-target="' + target + '"].selected').length >= min) {
    $('[data-target="' + target + '"] input').removeAttr('required');
  } else {
    $('[data-target="' + target + '"] input').attr('required', 'required');
  }
  
  soma = total;
  
});

$(document).on('click','.addon_option .addon_plus',  function(){
    
    let parent = $(this)[0].parentElement; 
    let qty_txt = parent.querySelector('.qty_txt');
    let addon_minus = parent.querySelector('.addon_minus');
        
    let target = $(this).attr('data-target');
    let count = 0;
    
    let max   = $('.max.'+target).attr('data-max');
    let min   = $('.min.'+target).attr('data-min');
      
    for(i = 0; i < $('.addon_option[data-target="' + target + '"] input:checked').length; i++){
        
      count += parseInt($('.addon_option[data-target="' + target + '"] input:checked')[i].value);
      
    }
    
    if(count >= min ){
      $('li[data-target="' + target + '"] input').removeAttr('required');
    }
    
    if(count >= max){
      alert('Número máximo de ' + $('.' + target + '.title').text() + ' atingido!');
      return false;
    }
    
    let option = $('input[name="'+$(this).attr('data-option')+'"');
   
    if(!option.is(':checked')){
        
      option.prop('checked',true); 
      
        if(count == 0 && min == 1){
          $('.addon_option[data-target="' + target + '"] input').removeAttr('required');  
        } 
        
        addon_minus.classList.remove('d-none');
        qty_txt.classList.remove('d-none');
        qty_txt.innerText = 1;
        

    }
    
    option.val((parseInt(option.val())+1));
    qty_txt.innerText = option.val();
    
    let price = parseFloat($(this).attr('data-price'));
    let total = parseFloat($('.sent-to-cart-btn .price').attr('data-price'));
    
    total += price;

  $('.sent-to-cart-btn .price').attr('data-price', total).empty().append('R$' + real(String(total).replace('.', ',')));
  soma = total;  
});

$(document).on('click','.addon_option .addon_minus',  function(){
    
    let qty_txt = $(this)[0].parentElement.querySelector('.qty_txt');
        
    let target = $(this).attr('data-target');
    let count = 0;
    
    let min   = $('.min.'+target).attr('data-min');
      
    for(i = 0; i < $('.addon_option[data-target="' + target + '"] input:checked').length; i++){
        
      count += parseInt($('.addon_option[data-target="' + target + '"] input:checked')[i].value);
      
    }
    
    if(count < min ){
      $('.addon_option[data-target="' + target + '"] input').attr('required','required');
    }
    
    let option = $('input[name="'+$(this).attr('data-option')+'"');
    
    if(parseInt(option.val()) > 1){
      option.val((parseInt(option.val())-1));
      qty_txt.innerText = option.val();
    }else{
        
      option.val(0); 
      option.prop('checked',false);
      
      $(this).addClass('d-none');
      qty_txt.classList.add('d-none');

      if(min == 1){
        $('.addon_option[data-target="' + target + '"] input').attr('required','required');
      }

    }
 
    let price = parseFloat($(this).attr('data-price'));
    let total = parseFloat($('.sent-to-cart-btn .price').attr('data-price'));

    total -= price;
    $('.sent-to-cart-btn .price').attr('data-price', total).empty().append('R$' + real(String(total).replace('.', ',')));
    soma = total;
});
