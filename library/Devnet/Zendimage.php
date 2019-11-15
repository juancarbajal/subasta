<?php
/*
 * Libreria ZendImage para todo lo que tenga que ver con Imagenes xD
 * Author: Solman Vaisman Gonzalez.
 * Email: Solman28@hotmail.com
 */
class Devnet_Zendimage
{

    var $image;
    var $type;
    var $width;
    var $height;

    //---Método de leer la imagen
    function loadImage($name)
    {
        $info = getimagesize($name);
        $this->width = $info[0];
        $this->height = $info[1];
        $this->type = $info[2];

        switch($this->type)
        {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($name);
                break;
            case IMAGETYPE_GIF:
                $this->image = imagecreatefromgif($name);
                break;
            case IMAGETYPE_PNG:
                $this->image = imagecreatefrompng($name);
                break;
        }
    }

    //---Método de guardar la imagen
    function save($name, $quality = 100)
    {
        switch ($this->type) {
        case IMAGETYPE_JPEG:
            imagejpeg($this->image, $name, $quality);
            break;
        case IMAGETYPE_GIF:
            imagegif($this->image, $name);
            break;
        case IMAGETYPE_PNG:
            $pngquality = floor(($quality - 10) / 10);
            imagepng($this->image, $name, $pngquality);
            break;
        }
    }

    //---Método de mostrar la imagen sin salvarla
    function show()
    {
        switch($this->type){
        case IMAGETYPE_JPEG:
            imagejpeg($this->image);
            break;
        case IMAGETYPE_GIF:
            imagegif($this->image);
            break;
        case IMAGETYPE_PNG:
            imagepng($this->image);
            break;
        }
    }

    //---Método de redimensionar la imagen sin deformarla
    function resize($value, $prop)
    {
        $propValue = ($prop == 'width') ? $this->width : $this->height;
        $propVersus = ($prop == 'width') ? $this->height : $this->width;

        $pcent = $value / $propValue;
        $valueVersus = $propVersus * $pcent;
        $image = ($prop == 'width') ? imagecreatetruecolor($value, $valueVersus) :
                imagecreatetruecolor($valueVersus, $value);

        switch ($prop) {
            case 'width':
                imagecopyresampled(
                    $image, $this->image, 0, 0, 0, 0, $value, $valueVersus, $this->width,
                    $this->height
                );
                break;

            case 'height':
                imagecopyresampled(
                    $image, $this->image, 0, 0, 0, 0, $valueVersus, $value, $this->width,
                    $this->height
                );
                break;
        }

        //---Actualizar la imagen y sus dimensiones

        $this->width = imagesx($image);
        $this->height = imagesy($image);
        $this->image = $image;
    }

    //---Método de extraer una sección de la imagen sin deformarla
    function crop($cwidth, $cheight, $pos = 'center')
    {
        if ($cwidth > $cheight) {
            $this->resize($cwidth, 'width');
        } else {
            $this->resize($cheight, 'height');
        }
        $image = imagecreatetruecolor($cwidth, $cheight);
        switch($pos) {
            case 'center':
            imagecopyresampled(
                $image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2),
                abs(($this->height - $cheight) / 2), $cwidth, $cheight, $cwidth, $cheight
            );
                break;

            case 'left':
            imagecopyresampled(
                $image, $this->image, 0, 0, 0, abs(($this->height - $cheight) / 2),
                $cwidth, $cheight, $cwidth, $cheight
            );
                break;

            case 'right':
            imagecopyresampled(
                $image, $this->image, 0, 0,
                $this->width - $cwidth, abs(($this->height - $cheight) / 2),
                $cwidth, $cheight, $cwidth, $cheight
            );
                break;

            case 'top':
            imagecopyresampled(
                $image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2), 0, $cwidth,
                $cheight, $cwidth, $cheight
            );
                break;

            case 'bottom':
            imagecopyresampled(
                $image, $this->image, 0, 0, abs(($this->width - $cwidth) / 2),
                $this->height - $cheight, $cwidth, $cheight, $cwidth, $cheight
            );
                break;
        }
        $this->image = $image;
    }

}
