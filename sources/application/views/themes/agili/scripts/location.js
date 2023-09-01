$(document).ready(()=>{

    let state = 'select[name="state"]';
  
    $(state).change((e)=>{

        let option = $(state+' option:selected').val();
        
        $.ajax('ajax/location&state='+option).done((e)=>{

           let city =  $('select[name="city"]');
           city.empty().append(e);

        });

    });

});