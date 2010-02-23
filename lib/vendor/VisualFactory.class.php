<?php

/**
 *
 */
class VisualFactory
{
 /**
  *
  *
  * @param <type> $wm_file
  * @param <type> $in_file
  * @param <type> $out_file
  */
  public static function putWatermark($wm_file, $in_file, $out_file)
  {
    $watermark = imagecreatefrompng($wm_file);

    $in_ext = explode('.', $in_file);
    switch(strtolower($in_ext[count($in_ext)-1]))
    {
      case 'jpg': case 'jpeg':
        $image = imagecreatefromjpeg($in_file);
        break;
      case 'gif':
        $image = imagecreatefromgif($in_file);
        break;
      case 'bmp': case 'wbmp':
        $image = imagecreatefromwbmp($in_file);
        break;
      case 'png':
        $image = imagecreatefrompng($in_file);
        break;
      default:
        throw new Exception('nieznany rodzaj pliku graficznego');
    }

    $margin_right = 5;
    $margin_bottom = 5;
    $wm_sx = imagesx($watermark);
    $wm_sy = imagesy($watermark);
    $im_sx = imagesx($image);
    $im_sy = imagesy($image);

    imagecopy($image, $watermark,
      $margin_right, $im_sy - $wm_sy - $margin_bottom, // w tym miejscu smaruj watermark
      0, 0, // od tych współrzędnych bierzesz watermark
      $wm_sx, $wm_sy // cały watermark bierzesz
    );

    $out_ext = explode('.', $out_file);
    switch(strtolower($out_ext[count($out_ext)-1]))
    {
      case 'jpg': case 'jpeg':
        imagejpeg($image, $out_file);
        break;
      case 'gif':
        imagegif($image, $out_file);
        break;
      case 'bmp': case 'wbmp':
        imagewbmp($image, $out_file);
        break;
      case 'png':
        imagepng($image, $out_file);
        break;
      default:
        throw new Exception('nieznany rodzaj pliku graficznego');
    }

    imagedestroy($image);
    imagedestroy($watermark);
  }

 /**
  * 
  *
  * @param <type> $in_file
  * @param <type> $out_file
  * @param <type> $new_width
  * @param <type> $new_height
  */
  public static function putResized($in_file, $out_file, $new_width, $new_height)
  {
    $destination = imagecreatetruecolor($new_width, $new_height);

    $in_ext = explode('.', $in_file);
    switch(strtolower($in_ext[count($in_ext)-1]))
    {
      case 'jpg': case 'jpeg':
        $source = imagecreatefromjpeg($in_file);
        break;
      case 'gif':
        $source = imagecreatefromgif($in_file);
        break;
      case 'bmp': case 'wbmp':
        $source = imagecreatefromwbmp($in_file);
        break;
      case 'png':
        $source = imagecreatefrompng($in_file);
        break;
      default:
        throw new Exception('nieznany rodzaj pliku graficznego: '.strtolower($in_ext[1]).' '.$in_file);
    }

    $src_width = imagesx($source);
    $src_height = imagesy($source);

    $new_dwh = $new_width / $new_height; // stosunek dł/wys.
    $src_dwh = $src_width / $src_height;

    if ($src_dwh == $new_dwh)
    { //
      $fin_x = 0;
      $fin_y = 0;
      $fin_width = $src_width;
      $fin_height = $src_height;
    } elseif ($src_dwh > $new_dwh) { //
      $fin_height = $src_height;
      $in_width = ceil($src_height * $new_width / $new_height);
      $fin_x = ($src_width - $fin_width) / 2;
      $fin_y = 0;
    } elseif ($src_dwh < $new_dwh) { //
      $fin_height = ceil($src_width * $new_height / $new_width);
      $fin_width = $src_width;
      $fin_x = 0;
      $fin_y = ($src_height - $fin_height) / 2;
    }

    imagecopyresampled(
      $destination, $source,
      0, 0, // odkąd smarować na wyniku
      $fin_x, $fin_y, // skąd pobierać źródło
      $new_width, $new_height,
      $fin_width, $fin_height
    );

    $out_ext = explode('.', $out_file);
    switch(strtolower($out_ext[count($out_ext)-1]))
    {
      case 'jpg': case 'jpeg':
        imagejpeg($destination, $out_file);
        break;
      case 'gif':
        imagegif($destination, $out_file);
        break;
      case 'bmp': case 'wbmp':
        imagewbmp($destination, $out_file);
        break;
      case 'png':
        imagepng($destination, $out_file);
        break;
      default:
        throw new Exception('nieznany rodzaj pliku graficznego');
    }
  }

/*==========================================================================*/

 /**
  *
  *
  * @param <type> $in_file
  * @param <type> $out_file
  * @param <type> $format
  */
  protected static function ImageMagickResize($in_file, $out_file, $format)
  {
    $command_convert =
      "convert ".
      escapeshellarg($in_file).
      " -thumbnail $format -gravity center -extent $format -strip ".
      escapeshellarg($out_file);

    shell_exec($command_convert);
  }

 /**
  *
  *
  * @param <type> $wm_file
  * @param <type> $in_file
  * @param <type> $mid_file
  * @param <type> $out_file
  * @param <type> $format
  */
  protected static function ImageMagickWatermark($wm_file, $in_file, $mid_file, $out_file, $format)
  {
    $command_convert =
      "convert ".
      escapeshellarg($in_file).
      " -thumbnail $format -gravity center -extent $format -strip ".
      escapeshellarg($mid_file);

    $command_composite =
      "composite -gravity SouthWest ".
      escapeshellarg($wm_file).
      " ".
      escapeshellarg($mid_file).
      " -strip ".
      escapeshellarg($out_file);

    shell_exec($command_convert);
    shell_exec($command_composite);
  }

/*==========================================================================*/

 /**
  *
  *
  * @param <type> $in_file
  * @param <type> $out_file
  * @param <type> $format
  */
  protected static function GDResize($in_file, $out_file, $format)
  {
    $f = explode('x', $format);
    VisualFactory::putResized($in_file, $out_file, $f[0], $f[1]);
  }

 /**
  *
  *
  * @param <type> $wm_file
  * @param <type> $in_file
  * @param <type> $mid_file
  * @param <type> $out_file
  * @param <type> $format
  */
  protected static function GDWatermark($wm_file, $in_file, $mid_file, $out_file, $format)
  {
    $f = explode('x', $format);
    VisualFactory::putResized($in_file, $mid_file, $f[0], $f[1]);
    VisualFactory::putWatermark($wm_file, $mid_file, $out_file);
  }

/*==========================================================================*/

 /**
  *
  *
  * @param <type> $mode
  * @param <type> $in_file
  * @param <type> $out_file
  * @param <type> $format
  */
  public static function Resize($mode, $in_file, $out_file, $format)
  {
    switch($mode)
    {
      case 'im':
        self::ImageMagickResize($in_file, $out_file, $format);
        break;
      case 'gd':
        self::GDResize($in_file, $out_file, $format);
        break;
    }
  }

 /**
  *
  *
  * @param <type> $mode
  * @param <type> $wm_file
  * @param <type> $in_file
  * @param <type> $mid_file
  * @param <type> $out_file
  * @param <type> $format
  */
  public static function Watermark($mode, $wm_file, $in_file, $mid_file, $out_file, $format)
  {
    switch($mode)
    {
      case 'im':
        self::ImageMagickWatermark($wm_file, $in_file, $mid_file, $out_file, $format);
        break;
      case 'gd':
        self::GDWatermark($wm_file, $in_file, $mid_file, $out_file, $format);
        break;
    }
  }
}

?>