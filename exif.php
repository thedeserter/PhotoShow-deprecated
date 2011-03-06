<div class="close">
	<a href="#">x</a>
</div>

<?php
/*
*  Created by Thibaud Rohmer on 2010-12-23.
*/

$exifDat=@exif_read_data($_POST['img']);

if($exifDat)
{

	$model	=	"";
	$ISO	=	"";
	$focal	=	"";
	$aperture=	"";
	$shutter=	"";
	
	if(isset($exifDat["Model"])) 			$model=$exifDat["Model"];
	if(isset($exifDat["ISOSpeedRatings"])) 	$ISO=$exifDat["ISOSpeedRatings"];
	if(isset($exifDat["FocalLength"])) 		$focal=$exifDat["FocalLength"]+0;
	if(isset($exifDat["FNumber"])) 			$aperture=$exifDat["FNumber"]+0;
	if(isset($exifDat["ExposureTime"])) 	$shutter=$exifDat["ExposureTime"];
	
	echo ("
		<div class='info_type1'> $model </div>
		<div class='exif_infos'>
		<div class='info_type2 border-right'>ISO $ISO</div>
		<div class='info_type2 border-right'>$focal mm</div>
		<div class='info_type2 border-right'>f/$aperture</div>
		<div class='info_type2'>$shutter</div>
		</div>");

}else{

	echo "<div class='info_type1'>No EXIF found.</div>";
}

?>

<script>
$(".close").click(function(){
	$(this).parent().parent().fadeOut("slow");
});
</script>
