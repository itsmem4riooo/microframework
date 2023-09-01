var add_address =  $('#add_address');

add_address.submit(()=>{

  let form = new FormData(add_address[0]);

  $.ajax({
      url: add_address.attr('action'),
      type: 'post',
      dataType: 'html',
      cache: false,
      processData: false,
      contentType: false,
      timeout: 8000,
      data: form,
      success: function(e){
        
        let address = JSON.parse(e);
        let alerts = $('#address .alerts');
        let alert =  alerts.find('.alert');

        let cont = $('.address.d-none').clone();
        
        alerts.fadeOut();

        alert.removeClass('alert-danger').removeClass('alert-success');

        alert.addClass('alert-'+address.class);
        alert.find('.msg').empty().append(address.msg)

        alert.find('.btn-close').click(()=>{
          alerts.fadeOut();
        });

        alerts.fadeIn();
        alert.addClass('alert-'+address.class).find('.msg').text(address.msg);

        if(address.class == 'success'){

          let identification  = add_address.find('input[name="identification"]').val();
          let street          = add_address.find('input[name="street"]').val();
          let number          = add_address.find('input[name="number"]').val();
          let district        = add_address.find('input[name="district"]').val();
          let complement      = add_address.find('input[name="complement"]').val();
          cont.find('input[name="address"]').val(address.id);

          cont.removeClass('d-none');
          cont.find('label').empty().text(identification);
          cont.find('.street').prepend('Rua '+street+' n°'+number+' Bairro '+district);
          cont.find('.complement').text(complement);

          $('.delivery_options >div').append(cont);

            add_address.each(function(){
               this.reset();
            });

        setTimeout(function(){
                $('#address .close').click()
        },1000);

        }

       

      }
  });

  return false;

});