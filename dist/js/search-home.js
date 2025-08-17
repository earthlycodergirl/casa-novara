const formatter = new Intl.NumberFormat("en-US", {
	style: "currency",
	currency: "USD",

	// These options are needed to round to whole numbers if that's what you want.
	//minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
	maximumFractionDigits: 0 // (causes 2500.99 to be printed as $2,501)
});
$("#propertyType").on("change", function () {
	if ($("option:selected", this).val() == "20-") {
		$(".res-div").removeClass("hide");
	} else {
		$(".res-div").addClass("hide");
	}
});
$(".form-range").on("input", function () {
	var inputval = $(this).val();
	var newpr = formatter.format(parseInt(inputval));
	var update_span = $(this).data("rel");
	$(this)
		.siblings("output")
		.html(newpr + " usd");
	$("." + update_span).html(newpr);
	if (update_span == "min-val") {
		$(".min-txt").html(newpr);
		$("#maxRange").data("min", parseInt(inputval));
		$(".max-output").html(newpr);
        $("#in_price_min").val(parseInt(inputval));
	}else if(update_span == 'max-val'){
        $("#in_price_max").val(parseInt(inputval));
    }
});

$(".btn-reset").on("click", function () {
	var drel = $(this).data("rel");
	$("." + drel).html("");
    if (update_span == "min-val") {
        $("#in_price_min").val(0);
    }else if(update_span == 'max-val'){
        $("#in_price_max").val(0);
    }
});

$(".advanced-search").on("click", function () {
	// check what property type was selected
	var ptype = $("#propertyType option:selected").val();
	console.log(ptype);
	if ($(this).hasClass("opened")) {
		$(".advanced-drop").slideUp();
		$(this).removeClass("opened");
	} else {
		$(".advanced-drop").slideDown();
		$(this).addClass("opened");
	}
});

$(".dev-check").on("change", function () {
	console.log($(".dev-check:checked").val());
	if ($(".dev-check:checked").val() == "construction") {
		$(".built-sel").slideUp();
		$(".develop-sel").slideDown();
	} else {
		$(".built-sel").slideDown();
		$(".develop-sel").slideUp();
	}
});
