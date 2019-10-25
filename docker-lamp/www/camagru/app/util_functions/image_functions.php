<?php


function getStickerName($num){
	$superpos_array = [
		1	=> '/photos/superpos/megaman.png',
		2	=> '/photos/superpos/melon.png',
		3	=> '/photos/superpos/rajang.png',
		4	=> '/photos/superpos/ayano.png',
		5	=> '/photos/superpos/scumbag.png'
	];
	return ($superpos_array[$num]);
}

function blendImages($sppath, $destpath){

	if ($sppath == null)
		$sppath = '/photos/superpos/ayano.png';
	if (!file_exists($destpath) || !file_exists(APPROOT . $sppath)){
		return (false);
	}
	$sppath = APPROOT . $sppath;
	$spinfo = getimagesize($sppath);
	if (!($superpos = imagecreatefrompng($sppath))){
		return (false);
	}	
	if (!($dest = @imagecreatefromstring(file_get_contents($destpath)))){
		imagedestroy($superpos);
		return (false);
	}
	$dinfo = getimagesize($destpath);
	// imagealphablending($superpos, true);
	// imagesavealpha($superpos, true);
	// imagealphablending($dest, true);
	// imagesavealpha($dest, true);
	// imagecopymerge($dst_im, $src_im ,$dst_x ,$dst_y ,$src_x ,$src_y ,$src_w ,$src_h ,$pct )
	if (!isset($dinfo[0]) || !isset($dinfo[1]) || !isset($spinfo[0]) || !isset($spinfo[1]))
		return (false);
	imagecopy($dest, $superpos, 10 > $dinfo[0] ? 0 : 10, 13 > $dinfo[1] ? 0 : 13, 0, 0, $spinfo[0], $spinfo[1]);
	imagepng($dest, $destpath);
	imagedestroy($dest);
	imagedestroy($superpos);
	return (true);
}
?>