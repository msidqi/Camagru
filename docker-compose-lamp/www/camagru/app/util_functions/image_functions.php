<?php


function loadPNG(){

}

function blendImages($superpos_name, $destpath){
	$superpos_array = [
		'megaman'	=> '/photos/superpos/megaman.png',
		'palico'	=> '/photos/superpos/palico.png'
	];

	$sppath = APPROOT . $superpos_array[$superpos_name];
	$spinfo = getimagesize($sppath);
	$superpos = imagecreatefrompng($sppath);
	$dest = imagecreatefromjpeg($destpath);
	$dinfo = getimagesize($destpath);

	imagealphablending($superpos, true);
	imagesavealpha($superpos, true);
	imagealphablending($dest, true);
	imagesavealpha($dest, true);
	// imagecopymerge($dst_im, $src_im ,$dst_x ,$dst_y ,$src_x ,$src_y ,$src_w ,$src_h ,$pct )
	imagecopymerge($dest, $superpos, 10 > $dinfo[0] ? 0 : 10, 13 > $dinfo[1] ? 0 : 13, 0, 0, $spinfo[0], $spinfo[1], 100);
	// header('Content-Type: image/png');
	imagepng($dest, $destpath);

	imagedestroy($dest);
	imagedestroy($superpos);
}

?>