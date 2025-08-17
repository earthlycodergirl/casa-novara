var markers = [];
var map;

function normalIcon() {
  console.log('normal');
  return {
    url: 'dist/img/map/map-off.png'
  };
}

function highlightedIcon() {
  console.log('highlight');
  return {
    url: 'dist/img/map/map-on.png'
  };
}

function initialize() {


    if($('body').hasClass('is_mobile')){
      $('.owl-carousel').owlCarousel({
          items:2,
          loop:true,
          margin:10,
          merge:true,
          responsive:{
              678:{
                  mergeFit:true
              },
              1000:{
                  mergeFit:false
              }
          }
      });
    }

    if($('.coordinates').data('lat') > 0){
      var lon = $('.coordinates').data('lon');
      var lat = $('.coordinates').data('lat');
      var center = new google.maps.LatLng(lat,lon);
      var showscale = true;
      if($('body').hasClass('is_mobile')){
        showscale = false;
      }

      var map = new google.maps.Map(document.getElementById('list_map'), {
        zoom: 9,
        center: center,
        mapTypeControl: false,
        zoomControl: false,
        scaleControl: showscale,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false,
        mapTypeId: 'roadmap'
      });

      //var markers = [];

      $('.propertyItem').each(function(){
          var mtitle = '';
          var markId = $(this).attr('data-id');
          var lt = $(this).data('lat');
          var lg = $(this).data('lng');
          var mprice = $("#prop"+markId+" .price").text();

          var latLng = new google.maps.LatLng(lt,lg);
          var marker = new google.maps.Marker({position: latLng, map, icon: 'dist/img/map/map-off.png',title:mprice});

          //console.log(lt+' '+lg);
          // Generate the info window to be displayed

          var mfeatured = '';
          var mptype = $("#prop"+markId+" .propertyType").text();
          //var mstype = $("#prop"+markId+" .forSale").text();
          var mimg = $("#prop"+markId+" .map-prop-img").data('val');
          mtitle = $("#prop"+markId+" .mp-title").text();
          var mdesc = $("#prop"+markId+" .pdesc").html();
          var mlink = $(this).data('details-url');
          if($(this).attr('data-featured') === 1){
              mfeatured = '<span class="map-featured"><i class="fa fa-star"></i> <small>featured property</small></span>'
          }
        //  console.log(mptype+' 1'+mimg+'3 '+mtitle+'4 '+mprice+'5 '+mfeatured);

          marker.info = new google.maps.InfoWindow({
            content: '<div class="info-window"><div class="mtitle">' + mtitle + '</div><div class="mwrap"><div class="mimg"><img src="'+mimg+'" alt="'+mtitle+'"></div><div class="mdesc">'+mdesc+'</div><div class="clearfix"></div></div><div class="pricing">'+mprice+'</div><div class="mbtn"><a href="'+mlink+'" class="btn btn-success btn-sm">See property</a></div><div class="clearfix"></div></div>'
          });
        google.maps.event.addListener(marker, 'click', function() {
            marker.info.open(map, marker);
            map.setZoom(15);
            map.setCenter(marker.getPosition());
            console.log(marker.getZIndex());
          });
        markers.push(marker);
      });

       var options = {
          imagePath: 'dist/img/map/m'
      };

      //var markerCluster = new MarkerClusterer(map, markers, options);
    }

}

$(document).ready(function(){
  if($('body').hasClass('no_mobile')){
    var head_height = $(".inner-head").height();
    var doc_height = $(window).height();
    var new_height = (doc_height - head_height);
    $("#listings").css('height',new_height+'px');
    $("#list_map").css('height',new_height+'px');
    //console.log(head_height+' - '+doc_height);
  }


  //google.maps.event.addDomListener(window, 'load', initialize);
  initialize();

  //console.log(markers);

  $('.propertyItem').on('mouseover', function() {
      // first we need to know which <div class="marker"></div> we hovered
      var index = $('.propertyItem').index(this);
      //console.log(index);
      markers[index].setIcon(highlightedIcon());

    });

    $('.propertyItem').on('mouseout', function() {
      // first we need to know which <div class="marker"></div> we hovered
      var index = $('.propertyItem').index(this);
      markers[index].setIcon(normalIcon());
    });

    $(".close-list").on('click',function(){
      //console.log('clicked');
      $('.listings-wrap').slideToggle();
      if($(this).hasClass('hidden')){
        console.log('show it');
        $(this).removeClass('hidden');
        $(this).html('<i class="bi bi-x"></i>');
        $('.mobile-filters').removeClass('mb-4');
        //$('.listings-wrap').slideUp();
      }else{
        //console.log('hide it');
        $(this).addClass('hidden');
        $(this).html('<i class="bi bi-chevron-up"></i>');
        $('.mobile-filters').addClass('mb-4');

      }
    })
});


