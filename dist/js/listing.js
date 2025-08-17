const observer = lozad(); // lazy loads elements with default selector as '.lozad'
observer.observe();
(function($){
  $(document).on('contextmenu', 'img', function() {
      return false;
  });
})(jQuery);
$(function () {

   var lat = $('body').data('lat');
   var lon = $('body').data('lon');

   if(lat > 0 && lon < 0){
      mapboxgl.accessToken = 'pk.eyJ1IjoiZ3JmcmVlZG9tMTEiLCJhIjoiY2tqM2VxeTR4MWhoMzMwdGR1OXNjb2xkZCJ9.9qdQeLe2YBkQwGiWCJ7wZg';
      var map = new mapboxgl.Map({
        container: 'map', // container id
        style: 'mapbox://styles/mapbox/light-v10', // style URL
        center: [lon,lat], // starting position [lng, lat]
        zoom: 8 // starting zoom
      });

       var marker = new mapboxgl.Marker({
         color: '#FFCB63',
         scale: 0.6
       }).setLngLat([lon,lat]).addTo(map);
   }
});

$(document).ready(function(){

  $('.toggle-desc').on('click',function(){
    $('.desc').toggleClass('show-desc');
    $(this).toggleClass('shown');
  });


  $("#info_form").on('submit',function(e){
    e.preventDefault();
    var but = $('#send_request').toggleClass('sending').blur();

    $.post('dist/inc/emails/info_request_admin.php',{name:$('#na').val(),email:$("#em").val(),phone:$("#ph").val(),notes:$("#no").val(),prop:$("#pr").val(),req:39},function(data){
      if(data.success == 1){
        // show a success message
        $.post('dist/inc/emails/info_request_client.php',{prop:$("#pr").val(),em:$("#em").val()},function(){

        });
        but.removeClass('sending').blur();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        $("#na").val('');
        $("#em").val('');
        $("#ph").val('');
        $("#no").text('');
        $("#call_form").slideUp('fast',function(){
          $("#success_form").fadeIn('slow').delay(9000).fadeOut('slow');
        });


      }else{
        if(data.errors.length > 0){
          $.each(data.errors, function(key, value) {
              console.log(value);
              $("#"+value[0]).addClass('is-invalid');
              $("#"+value[0]+"_0 .invalid-feedback").show();
          });
          but.removeClass('sending').blur();
        }
      }
    },'json')
  });



  if($('body').hasClass('is_mobile')){
    $('.toggle-tb').on('click',function(){
      $($(this).data('target')).slideToggle();
      $(this).toggleClass('shown');
    })
  }



  var sync1 = $("#property-d-1");
  var sync2 = $("#property-d-1-2");
  sync1.owlCarousel({
    autoPlay: 3000,
    singleItem: true,
    slideSpeed: 1000,
    transitionStyle: "fade",
    navigation: true,
    navigationText: ["<i class='bi bi-chevron-left'></i>", "<i class='bi bi-chevron-right'></i>"],
    pagination: false,
    afterAction: syncPosition,
    responsiveRefreshRate: 200,
  });
  sync2.owlCarousel({
	  autoPlay: true,
    items: 8,
    itemsDesktop: [1199, 10],
    itemsDesktopSmall: [979, 10],
    itemsTablet: [768, 8],
    itemsMobile: [479, 6],
    pagination: false,
    responsiveRefreshRate: 100,
    afterInit: function(el) {
      el.find(".owl-item").eq(0).addClass("synced");
    }
  });

  function syncPosition(el) {
    var current = this.currentItem;
    $("#property-d-1-2").find(".owl-item").removeClass("synced").eq(current).addClass("synced")
    if ($("#property-d-1-2").data("owlCarousel") !== undefined) {
      center(current)
    }
  }
  $("#property-d-1-2").on("click", ".owl-item", function(e) {
    e.preventDefault();
    var number = $(this).data("owlItem");
    sync1.trigger("owl.goTo", number);
  });

  function center(number) {
    var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
    var num = number;
    var found = false;
    for (var i in sync2visible) {
      if (num === sync2visible[i]) {
        var found = true;
      }
    }
    if (found === false) {
      if (num > sync2visible[sync2visible.length - 1]) {
        sync2.trigger("owl.goTo", num - sync2visible.length + 2)
      } else {
        if (num - 1 === -1) {
          num = 0;
        }
        sync2.trigger("owl.goTo", num);
      }
    } else if (num === sync2visible[sync2visible.length - 1]) {
      sync2.trigger("owl.goTo", sync2visible[1])
    } else if (num === sync2visible[0]) {
      sync2.trigger("owl.goTo", num - 1)
    }
  }


});
