<form method="get" action="<?= $link_properties[$lang] ?>" id="basic_search">
<input type="hidden" name="search_type" value="basic">
<input type="hidden" name="page" value="1">
<!-- <input type="hidden" name="price_min" value="0" id="in_price_min"> -->
<!-- <input type="hidden" name="price_max" value="0" id="in_price_max"> -->
<style>@media screen and (max-width: 500px){
   .hide-on-mobile{
      display: none;
   }
   .development__level{
      height: auto;
      padding: 5px;
   }
}
</style>
	<div class="search-box">
		<div class="sel-box">
			<select name="location" id="location" class="form-control">
				<option selected value="0"><?= $lan['bas']['where'][0] ?></option>
                <option value="0"><?= $lan['bas']['where'][1] ?></option>
                <?php if(!empty($listing_cities)){ foreach($listing_cities as $kk=>$vv){ ?>
                <option value="<?= $kk ?>"><?= $vv['loc'].', Mexico ('.$vv['cnt'].')' ?></option>
                <?php } } ?>
			</select>
		</div>

		<div class="sel-ptype">
			<select name="property_type" id="propertyType" class="form-control">
				<option value="0" selected><?= $lan['bas']['ptypes'][0] ?></option>
				<option value="0"><?= $lan['bas']['ptypes'][1] ?></option>
				<?php foreach($property_types as $pp=>$tt){ ?>
				<option value="<?= $pp.'-'.$tt['desc_up'] ?>"><?= $tt['desc'] ?></option>
				<?php } ?>
			</select>
		</div>

		<div class="vis-box">
			<div class="price-selection">
				<div class="min-pr">
					<div class="dropdown-up">
                        <div class="form-group">
                            <select class="form-select" id="min_price" name="price_min">
                                <option value="0" selected>Min Price</option>
                                <option value="50000">50,000</option>
                                <option value="100000">100,000</option>
                                <option value="200000">200,000</option>
                                <option value="300000">300,000</option>
                                <option value="400000">400,000</option>
                                <option value="500000">500,000</option>
                                <option value="600000">600,000</option>
                                <option value="700000">700,000</option>
                                <option value="800000">800,000</option>
                                <option value="900000">900,000</option>
                                <option value="1000000">1,000,000</option>
                                <option value="1250000">1,250,000</option>
                                <option value="1500000">1,500,000</option>
                                <option value="2000000">2,000,000</option>
                                <option value="5000000">5,000,000+</option>
                            </select>
                        </div>
						<!-- <button class="btn dropdown-toggle" type="button" id="mindrop" data-bs-toggle="dropdown" aria-expanded="false">
							Min Price <span class="min-val"></span>
						</button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" data-bs-auto-close="outside">
						<!-- <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mindrop">
							<li class="min-li"> -->
								<!-- <label for="minRange" class="form-label"><span class="min">$1,000</span><span class="max max-txt">$5,000,000</span></label>
								<input type="range" class="form-range" min="1000" max="5000000" id="minRange" data-rel="min-val">
								<output class="min-output">$0</output>
								<button type="button" class="btn-reset" data-rel="min-val">reset</button> --
							
                                <div class="form-group">
                                    <label class="form-label">Min Price</label>
                                    <select class="form-select" id="min_price" name="price_min">
                                    <option value="50000">50,000</option>
                                    <option value="100000">100,000</option>
                                    <option value="200000">200,000</option>
                                    <option value="300000">300,000</option>
                                    <option value="400000">400,000</option>
                                    <option value="500000">500,000</option>
                                    <option value="600000">600,000</option>
                                    <option value="700000">700,000</option>
                                    <option value="800000">800,000</option>
                                    <option value="900000">900,000</option>
                                    <option value="1000000">1,000,000</option>
                                    <option value="1250000">1,250,000</option>
                                    <option value="1500000">1,500,000</option>
                                    <option value="2000000">2,000,000</option>
                                    <option value="5000000">5,000,000+</option>
                                    
                                    </select>
                                </div>
                            <!-- </li>
                 
                        </ul> --
                        </div> -->
                    </div>
				</div>
				<div class="max-pr">
					<div class="dropdown-up">
						<!-- <button class="btn dropdown-toggle" type="button" id="dropMax" data-bs-toggle="dropdown" aria-expanded="false">
							<span class="labell">Max Price</span>
							<span class="max-val"></span>
						</button> -->
						<!-- <ul class="dropdown-menu" aria-labelledby="dropMax">
							<li>
								<label for="maxRange" class="form-label"><span class="min min-txt">$1,000</span><span class="max">$5,000,000</span></label>
								<input type="range" class="form-range" min="1000" max="5000000" id="maxRange" data-rel="max-val">
								<output class="max-output">$0</output>
								<button type="button" class="btn-reset" data-rel="max-val">reset</button>
							</li>
						</ul> -->
                        <!-- <ul class="dropdown-menu dropdown-menu-end max-drop">
                            <li><a class="dropdown-item" href="#">50,000</a></li>
                            <li><a class="dropdown-item" href="#">100,000</a></li>
                            <li><a class="dropdown-item" href="#">200,000</a></li>
                            <li><a class="dropdown-item" href="#">300,000</a></li>
                            <li><a class="dropdown-item" href="#">400,000</a></li>
                            <li><a class="dropdown-item" href="#">500,000</a></li>
                            <li><a class="dropdown-item" href="#">600,000</a></li>
                            <li><a class="dropdown-item" href="#">700,000</a></li>
                            <li><a class="dropdown-item" href="#">800,000</a></li>
                            <li><a class="dropdown-item" href="#">900,000</a></li>
                            <li><a class="dropdown-item" href="#">1,000,000</a></li>
                            <li><a class="dropdown-item" href="#">1,250,000</a></li>
                            <li><a class="dropdown-item" href="#">1,500,000</a></li>
                            <li><a class="dropdown-item" href="#">2,000,000</a></li>
                            <li><a class="dropdown-item" href="#">5,000,000</a></li>
                            <li><a class="dropdown-item other" href="#">Other</a></li>
                        </ul> -->
                        <div class="form-group">
                            <select class="form-select" id="min_price" name="price_max">
                                <option value="0" selected>Max Price</option>
                                <option value="50000">50,000</option>
                                <option value="100000">100,000</option>
                                <option value="200000">200,000</option>
                                <option value="300000">300,000</option>
                                <option value="400000">400,000</option>
                                <option value="500000">500,000</option>
                                <option value="600000">600,000</option>
                                <option value="700000">700,000</option>
                                <option value="800000">800,000</option>
                                <option value="900000">900,000</option>
                                <option value="1000000">1,000,000</option>
                                <option value="1250000">1,250,000</option>
                                <option value="1500000">1,500,000</option>
                                <option value="2000000">2,000,000</option>
                                <option value="5000000">5,000,000+</option>
                            </select>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<!-- visbox ends -->
		<div class="search-btn mobile-hide">
			<button type="submit" class="search_btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"></path>
					<path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"></path>
				</svg>
			</button>
		</div>
	</div>

	<div class="advanced-drop">
		<div class="hidden-boxes">

			<div class="advanced-search-all">
				<div class="adv-wrap">
					<div class="development__level">
						<label class="dev-label">Select Property Development</label>
						<div class="opt">
							<div class="form-check form-check-inline">
								<input class="form-check-input dev-check" type="radio" name="dev_type" id="const" value="construction">
								<label class="form-check-label" for="flexRadioDefault1">
									<span class="hide-on-mobile">Under</span> Construction
								</label>
							</div>
						</div>
						<div class="opt">
							<div class="form-check form-check-inline">
								<input class="form-check-input dev-check" type="radio" name="dev_type" id="devel" value="built" checked>
								<label class="form-check-label" for="flexRadioDefault2">
									Built / Developed
								</label>
							</div>
						</div>
					</div>
					<div class="built-before">
						<div class="formBlock select built-sel" style="background: white">
                  <label for="after" style=" font-size: 12px; color: #888; text-align: center; display: block; padding: 2px;">Built Before Year:</label>
                     <input type="num" name="after" id="built_before" class="form-control" placeholder="YYYY" style="text-align:center">
							<!-- <select name="after" id="built_before" class="form-control">
								<option value="0" selected="">Built After <small>(optional)</small></option>
								<!--<option value="0">Show all</option>--
								<option>1980</option>
								<option>1985</option>
								<option>1990</option>
								<option>1995</option>
								<option>2000</option>
								<option>2005</option>
								<option>2010</option>
								<option>2015</option>
								<option>2016</option>
								<option>2017</option>
								<option>2018</option>
								<option>2019</option>
								<option>2020</option>
								<option>2021</option>
								<option>2022</option>

							</select> -->
						</div> <!-- end built after -->

						<div class="formBlock select develop-sel" style="background: white">
                  <label for="built_before" style=" font-size: 12px; color: #888; text-align: center; display: block; padding: 2px;">Done by Year</label>
                  <input type="num" name="built_before" id="delivered_before" class="form-control" placeholder="YYYY"  style="text-align:center">


							<!-- <select name="built_before" id="delivered_before" class="form-control">
								<option value="0" selected="">Delivered Before <small>(optional)</small></option>
								<!--<option value="0">Show all</option>--
								<option>2023</option>
								<option>2024</option>
								<option>2025</option>
								<option>2026</option>

							</select> -->
						</div> <!-- end constructoion select -->
					</div>
				</div>
			</div>
			<div class="res-div hide">
				<div class="residential-extra">
					<!-- min beds -->
					<div class="formBlock select min-beds">
						<select name="beds" id="min_beds" class="form-control">
							<option value="0" selected="">Min Beds</option>
							<!--<option value="0">Show all</option>-->
							<option value="888">Studio / Loft</option>
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
							<option>11</option>
							<option>12+</option>
						</select>
					</div> <!-- end min beds -->

					<!-- min beds -->
					<div class="formBlock select min-baths">
						<select name="baths" id="max_baths" class="form-control">
							<option value="0" selected="">Min Baths</option>
							<!--<option value="0">Show all</option>-->
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6+</option>
						</select>
					</div> <!-- end min beds -->
				</div>
			</div>
		</div>
	</div>
	<div class="search-btn mobile-show m-block">
			<button type="submit" class="search_btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"></path>
					<path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"></path>
				</svg>
			</button>
		</div>
	<div class="advanced-search">
		<div class="not-open">
			<span class="icon"><svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M14 21H10L9.44904 18.5206C8.7879 18.2618 8.17573 17.9053 7.63028 17.4689L5.20573 18.232L3.20573 14.7679L5.07828 13.0503C5.02673 12.7077 5 12.357 5 12C5 11.643 5.02673 11.2923 5.07828 10.9496L3.20573 9.23204L5.20574 5.76794L7.6303 6.53106C8.17575 6.09467 8.78791 5.73819 9.44904 5.47935L10 3H14L14.551 5.47935C15.2121 5.73819 15.8242 6.09466 16.3697 6.53104L18.7942 5.76794L20.7942 9.23204L18.9217 10.9496C18.9733 11.2922 19 11.643 19 12C19 12.357 18.9733 12.7078 18.9217 13.0504L20.7942 14.7679L18.7942 18.232L16.3697 17.4689C15.8243 17.9053 15.2121 18.2618 14.551 18.5206L14 21Z" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
					<path d="M12 9V12M12 15V12M12 12H9M12 12H15" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
				</svg></span> Advanced Search
		</div>
		<div class="yes-open">
			<span class="icon">
				<svg width="15px" height="15px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
					<path fill="#ffffff" d="M195.2 195.2a64 64 0 0 1 90.496 0L512 421.504 738.304 195.2a64 64 0 0 1 90.496 90.496L602.496 512 828.8 738.304a64 64 0 0 1-90.496 90.496L512 602.496 285.696 828.8a64 64 0 0 1-90.496-90.496L421.504 512 195.2 285.696a64 64 0 0 1 0-90.496z" />
				</svg>
			</span> Close Advanced Search
		</div>
	</div>
</form>

<script>
  document.getElementById('min_price').addEventListener('click', function(event) {
    event.stopPropagation();
  });
</script>