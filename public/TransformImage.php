<?php 
//echo 'inicio'.PHP_EOL;
try{
	$nombreFichero=$_GET["nomfichero"];
	$fileuser=$_GET["fileuser"];
    $nuevoAviso=$_GET["nombreaviso"];
if (file_exists("/home/apkotear/public/original/$fileuser/$nombreFichero")){
//echo 'entro'.PHP_EOL;
exec("convert -resize '600x600>' /home/apkotear/public/original/$fileuser/$nombreFichero /home/apkotear/public/images/$fileuser/". $nuevoAviso. $nombreFichero . ' & ');
exec("convert -resize '300x300>' /home/apkotear/public/original/$fileuser/$nombreFichero /home/apkotear/public/img/$fileuser/". $nuevoAviso. $nombreFichero . ' & ');
exec("convert -resize '150x150>' /home/apkotear/public/original/$fileuser/$nombreFichero /home/apkotear/public/thumbs/$fileuser/". $nuevoAviso. $nombreFichero. ' & ');
exec("convert -resize '75x75>'   /home/apkotear/public/original/$fileuser/$nombreFichero /home/apkotear/public/thumbnails/$fileuser/". $nuevoAviso. $nombreFichero. ' & ');
exec("mv /home/apkotear/public/original/$fileuser/$nombreFichero" . ' '. "/home/apkotear/public/original/$fileuser/".$nuevoAviso.$nombreFichero);

  $ar=fopen("datos.txt","a") or
    die("Problemas en la creacion");
  fputs($ar,'proceso correcto');
  fclose($ar); 

} else echo 'NO EXISTE '."/home/apkotear/public/original/$fileuser/$nombreFichero" ;
//echo "fin".PHP_EOL;
} catch (Exception $e){
    $ar=fopen("datos.txt","a") or
    die("Problemas en la creacion");
  fputs($ar,$e->getMessage());
  fclose($ar); 
    
//echo $e->getMessage();
}
?>
