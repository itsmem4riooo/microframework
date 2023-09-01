$('.categories ul').owlCarousel({
    loop:false,
    margin:15,
    responsive:{
        0:{
            items:3,
            margin: 10
        },
        700:{
          items: 4
        },
        800:{
          items:5
        },
        1023:{
          items:8
        },
        1400:{
          items: 9
        }
    }
  
  });
  
  $('.slide_top').owlCarousel({
    items:1,
    loop:true,
    margin:10,
    responsive:{
      0:{
        margin: 0
      },
      1400:{
        items: 2
      }
  }

});