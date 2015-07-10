$(document).ready(function(){

//Slider--------------------------------------->
  var timeSlide = 10000;
  var sliderCount = 4;
  var timeSpeed = 1500;
  var i = 1;
  var show, hide;
  setInterval(changeSlide,timeSlide);

  function changeSlide(){
    
    hide = i;
    i = i + 1;
    show = i;  

    $('#item-slider-'+hide).fadeOut(timeSpeed); //hide 
    if (i == sliderCount) {show = 1; i = 1;} 
    $('#item-slider-'+show).fadeIn(timeSpeed); //show    
    
  }
//--------------------------------------->Slider 

  $('.toogle').click(function(){
    if($('.menu').is(":hidden")){
      $('.menu').slideDown();
    } else {
      $('.menu').slideUp();
    }  
  }) 

})

function ajaxAddBlogCategories(bid, cid){
  $('#img_load').show();
  $.ajax(
    {    
    type: 'POST',
    url: 'automatic.co.ua/mvc/controller/ajaxcontroller.php',
    data: '&bid='+bid+'&cid='+cid,
    async: false,
    error: function() 
    { 
        $('#img_load').hide();
        alert("Error Ajax");
    },
      success: function(data)
    {
        $('#img_load').hide();  
        $('.ajax-categories').text(data);     
    }
    
  });
}