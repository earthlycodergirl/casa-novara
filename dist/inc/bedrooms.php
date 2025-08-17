<?php
if($prop->Bedrooms >= 1){ ?>
 <!-- detail bedrooms -->
 <div class="prop-det d-flex">
    <div class="icon">
       <svg xmlns="http://www.w3.org/2000/svg" width="20.4" height="13.6" viewBox="0 0 20.4 13.6">
         <path id="Icon_ionic-md-bed" data-name="Icon ionic-md-bed" d="M10.062,16.252a2.721,2.721,0,1,0-2.784-2.72A2.75,2.75,0,0,0,10.062,16.252Zm11.13-5.44H13.77V17.16H6.354V9H4.5V22.6H6.354V19.88H23.046V22.6H24.9V14.44A3.668,3.668,0,0,0,21.192,10.812Z" transform="translate(-4.5 -9)" fill="#fff"/>
       </svg>
    </div>
    <div class="val flex-fill "><?= $prop->Bedrooms ?> <?php echo $prop->Bedrooms == 1 ? $lan['prop']['beds'][1] : $lan['prop']['beds'][0]; ?></div>
 </div>
 <!-- detail bedrooms end -->

<?php }elseif($prop->RoomType != 'bed'){ ?>
 <!-- detail bedrooms -->
 <div class="prop-det d-flex">
    <div class="icon">
       <svg xmlns="http://www.w3.org/2000/svg" width="20.4" height="13.6" viewBox="0 0 20.4 13.6">
         <path id="Icon_ionic-md-bed" data-name="Icon ionic-md-bed" d="M10.062,16.252a2.721,2.721,0,1,0-2.784-2.72A2.75,2.75,0,0,0,10.062,16.252Zm11.13-5.44H13.77V17.16H6.354V9H4.5V22.6H6.354V19.88H23.046V22.6H24.9V14.44A3.668,3.668,0,0,0,21.192,10.812Z" transform="translate(-4.5 -9)" fill="#fff"/>
       </svg>
    </div>
    <div class="val flex-fill "><?= ucfirst($prop->RoomType) ?></div>
 </div>
 <!-- detail bedrooms end -->
<?php }
if($prop->TotalBaths >= 1){ ?>
  <!-- detail bathrooms -->
  <div class="prop-det d-flex">
     <div class="icon">
       <svg xmlns="http://www.w3.org/2000/svg" width="21.429" height="18.75" viewBox="0 0 21.429 18.75">
        <path id="Icon_awesome-bath" data-name="Icon awesome-bath" d="M20.424,11.625H3.348V5.6a1.339,1.339,0,0,1,2.47-.717A2.844,2.844,0,0,0,6.1,8.357a.5.5,0,0,0,.021.688l.474.474a.5.5,0,0,0,.71,0l3.977-3.977a.5.5,0,0,0,0-.71l-.474-.474a.5.5,0,0,0-.688-.021,2.84,2.84,0,0,0-2.686-.643,3.347,3.347,0,0,0-6.1,1.9v6.027H1a1,1,0,0,0-1,1v.67a1,1,0,0,0,1,1h.335v1.339a4.007,4.007,0,0,0,1.339,2.994V20a1,1,0,0,0,1,1h.67a1,1,0,0,0,1-1v-.335H16.071V20a1,1,0,0,0,1,1h.67a1,1,0,0,0,1-1V18.637a4.007,4.007,0,0,0,1.339-2.994V14.3h.335a1,1,0,0,0,1-1v-.67A1,1,0,0,0,20.424,11.625Z" transform="translate(0 -2.25)" fill="#fff"/>
       </svg>

     </div>
     <div class="val flex-fill "><?= $prop->TotalBaths ?> <?php echo $prop->TotalBaths == 1 ? $lan['prop']['baths'][1] : $lan['prop']['baths'][0]; ?></div>
  </div>
  <!-- detail bathrooms end -->
<?php } if($prop->Size->Ft > 0){ ?>
 <!-- detail square footage -->
 <div class="prop-det d-flex">
    <div class="icon">
      <i class="bi bi-rulers"></i>

    </div>
    <div class="val flex-fill sizes"><?= number_format((int)$prop->Size->Ft).' ft&sup2;'.' / '.number_format((int)$prop->Size->Mt).' mt&sup2; <small>('.$lan['prop']['left_size'].')</small>'; ?></div>
 </div>
 <!-- detail square footage end -->
<?php } ?>