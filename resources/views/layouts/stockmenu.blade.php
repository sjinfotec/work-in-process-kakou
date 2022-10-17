<?php
$server_sn = $_SERVER['SCRIPT_NAME'];
$server_ru = $_SERVER['REQUEST_URI'];
$css_stock1 = "";
$css_stock2 = "";
if($server_ru == "/stock_a") {
	$css_stock1 = "stock_active";
}
elseif($server_ru == "/stock") {
	$css_stock2 = "stock_active";
}

?>
<div id="cnt_menu">
	<nav class="">
		<div id="menu_li">
			<ul>
				<li class="gc4b <?php echo $css_stock1; ?>"><a class="" href="{{ url('/stock_a') }}">棚卸 / 預かり・在庫</a></li>
				<li class="gc4b <?php echo $css_stock2; ?>"><a class="" href="{{ url('/stock') }}">棚卸 / TIPS</a></li>
				<!--<li class="gc4b"><a class="" href="{{ url('/stock_z') }}"><img class="iconsize_sm" src="{{ asset('images/round-add-circle-w.svg') }}" alt="">棚卸 / 在庫</a></li>-->
			</ul>
		</div>
	</nav>
</div>
