function real(e){
    let price = '0,00';

    if(e.includes('.')){
      e = e.replace('.',',');
    }

    if(e.includes(',')){
      price = e;
      let split = e.split(',');
      if(split[1].length < 2){
        price = e+'0';
      }
      if(split[1].length > 2){
        price = split[0]+','+split[1][0]+split[1][1];
      }
     
    }else{
      if(e.length > 0 ){
        price = e+',00';
      }
    }
    return price;
  }