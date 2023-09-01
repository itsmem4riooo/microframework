$('.home-category-slider').owlCarousel({
    loop: false,
    nav: false,
    dots: false,
    margin: 0,
    items: 3,
    smartSpeed: 1500,
    autoWidth: true,
  });
  
  $('.home-category-slider .selectCat').click(function () {
  
    let target = $($(this).attr('data-target'));
    let offset = target.offset().top;
  
    $('html, body').animate({
      scrollTop: offset
    }, 100)
  
  });
  