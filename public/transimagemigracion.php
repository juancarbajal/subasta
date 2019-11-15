<?php
	$nuevoAviso=$_GET["nombreaviso"];
    $nomOriginal=$_GET["nomOriginal"];
    $idUsr=$_GET["idUsr"];
    $original=$_GET["original"];//='/home/apkotear/public/original/';
    $img=$_GET['img'];//='/home/apkotear/public/img/';
    $thumbs=$_GET['thumbs'];//='/home/apkotear/public/thumbs/';
    $thumbnails=$_GET['thumbnails'];//='/home/apkotear/public/thumbnails/';
    $images=$_GET['images'];//='/home/apkotear/public/images/';
    $originalmig=$_GET['originalmig'];//='/home/apkotear/public/originalmig/';
    
exec ('mkdir '.$original.'/'.$idUsr);
exec('chmod 777 '.$original.'/'.$idUsr.' -R');
exec ('mkdir '.$images.'/'.$idUsr);
exec('chmod 777 '.$images.'/'.$idUsr.' -R');
exec ('mkdir '.$img.'/'.$idUsr);
exec('chmod 777 '.$img.'/'.$idUsr.' -R');
exec ('mkdir '.$thumbs.'/'.$idUsr);
exec('chmod 777 '.$thumbs.'/'.$idUsr.' -R');
exec ('mkdir '.$thumbnails.'/'.$idUsr);
exec('chmod 777 '.$thumbnails.'/'.$idUsr.' -R');



exec('mv '.$originalmig.'/'.$nomOriginal . ' '. $original.'/'.$idUsr.'/'.$nomOriginal);
exec("convert input -resize '600x600>' ". $original.'/'.$idUsr."/$nomOriginal ".$images."/$idUsr/". $nuevoAviso. $nomOriginal. ' & ');
exec("convert input -resize '300x300>' ". $original .'/'.$idUsr."/$nomOriginal ".$img."/$idUsr/". $nuevoAviso. $nomOriginal. ' & ');
exec("convert input -resize '150x150>' ". $original .'/'.$idUsr."/$nomOriginal ".$thumbs."/$idUsr/". $nuevoAviso. $nomOriginal. ' & ');
exec("convert input -resize '75x75>' ". $original .'/'.$idUsr."/$nomOriginal ".$thumbnails."/$idUsr/". $nuevoAviso. $nomOriginal. ' & ');
exec('mv '.$original.'/'.$idUsr."/$nomOriginal" . ' '. $original."/$idUsr/".$nuevoAviso.$nomOriginal);

?>
