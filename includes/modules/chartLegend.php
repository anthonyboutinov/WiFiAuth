<ul class="legend nav<?php if (!$drawFullContent) echo " not-draw-full-content";?>" id="legend">
	<?php
	
	$chartLegendValues = $database->getLoginCountByLoginOption(30); // 30 days
	
	for ($i = 0; $i < $numberOfSocialNetworks; $i++) {
	?>
	<li style="width:<?=(100/$numberOfSocialNetworks);?>%">
		<div class="legend-circle animated zoomIn" style="border-color: <?=$chartColors[$i];?>;"></div>
		<div class="legend-title"><? echo $socialNetworksNames[$i];?></div>
		<div class="legend-last-value" style="color: <?=$chartColors[$i]; ?>;"><?=CommonFunctions::NVL($chartLegendValues[$i]['LOGIN_COUNT'], 0);?>%</div>
	</li>
	<?php } ?>
</ul>