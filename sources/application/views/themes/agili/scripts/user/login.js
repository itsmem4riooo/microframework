const login_form =  $('#loginform');

login_form.submit(function(){

    let data = new FormData(login_form[0]);

    $.ajax({
        url: login_form.attr('action'),
        type: 'post',
        dataType: 'html',
        cache: false,
        processData: false,
        contentType: false,
        timeout: 8000,
        data: data,
        success: e => {
            
          let user = JSON.parse(e);
  
          let alerts = $('#loginModal .error');
          let alert =  alerts.find('.alert');
  
          if(user.class == 'success'){
            
            window.location.href = "https://obafood.app";
  
          }else{
            alert.removeClass('d-none');
            alert.empty().append(user.msg);
          }

        }
    });

    return false;
});

const register_form =  $('#registerform');

register_form.submit(function(){

    let data = new FormData(register_form[0]);

    $.ajax({
        url: register_form.attr('action'),
        type: 'post',
        dataType: 'html',
        cache: false,
        processData: false,
        contentType: false,
        timeout: 8000,
        data: data,
        success: e => {
            
          let user = JSON.parse(e);
  
          let alerts = $('#registerModal .error');
          let alert =  alerts.find('.alert');
  
          if(user.class == 'success'){
            window.location.href = "https://obafood.app";
          }else{
            alert.removeClass('d-none');
            alert.empty().append(user.msg);
          }

        }
    });

    return false;
});