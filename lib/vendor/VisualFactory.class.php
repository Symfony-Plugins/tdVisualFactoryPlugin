<?php

/**
 * Visual Factory
 *
 * Class performing basic image transforming operations using GD and Imagick
 * libraries.
 *
 * (!) Image Magick doesn't support automatic orientation switching (600x400 -> 400x600)
 *
 * @package    VisualFactory
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 */
class VisualFactory
{
  /**
   * Reads image from the filepath given by the parameter using gd built-in
   * functions.
   *
   * @param String $filepath - file path of the input image
   * @return resource $image - an image resource identifier on success, false on errors.
   */
  public static function imageCreateFrom($filepath)
  {
    // file extension
    $path_arr = explode('.', $filepath);
    $ext = $path_arr[count($path_arr)-1];

    // reading image
    switch(strtolower($ext))
    {
      case 'jpg': case 'jpeg':
        $image = imagecreatefromjpeg($filepath);
        break;
      case 'gif':
        $image = imagecreatefromgif($filepath);
        break;
      case 'bmp': case 'wbmp':
        $image = imagecreatefromwbmp($filepath);
        break;
      case 'png':
        $image = imagecreatefrompng($filepath);
        break;
      default:
        throw new Exception('nieznany rodzaj pliku graficznego '.strtolower($ext).': '.$filepath);
    }
    return $image;
  }

  /**
   * Writes image to the filepath given by the parameter using gd built-in
   * functions.
   *
   * @param resource $image - an image resource identifier
   * @param String $filepath - file path for the output image
   */
  public static function imagePut($image, $filepath)
  {
    // file extension
    $path_arr = explode('.', $filepath);
    $ext = $path_arr[count($path_arr)-1];

    // reading image
    switch(strtolower($ext))
    {
      case 'jpg': case 'jpeg':
        imagejpeg($image, $filepath);
        break;
      case 'gif':
        imagegif($image, $filepath);
        break;
      case 'bmp': case 'wbmp':
        imagewbmp($image, $filepath);
        break;
      case 'png':
        imagepng($image, $filepath);
        break;
      default:
        throw new Exception('nieznany rodzaj pliku graficznego '.strtolower($ext).': '.$filepath);
    }
  }

  /**
   * Checks if given image is horizontal
   *
   * @param resource $image - an image resource identifier
   * @return Boolean - given image is horizontal
   */
  public static function isHorizontal($image)
  {
    $width = imagesx($image);
    $height = imagesy($image);
    return $width > $height;
  }

  /**
   * Checks if given image is vertical
   *
   * @param resource $image - an image resource identifier
   * @return Boolean - given image is vertical
   */
  public static function isVertical($image)
  {
    return ! self::isHorizontal($image);
  }

/*============================================================================*/
/*============================ GD library ====================================*/

 /**
  * Performs RESIZE operation using GD library.
  *
  * @param String $in_file - input file path
  * @param String $out_file - output file path
  * @param String $format - output file format, e. g. '800x600'
  * @param Boolean $switch - if the format shall be automatically switched
  */
  protected static function GDResize($in_file, $out_file, $format, $switch)
  {
    $source = self::imageCreateFrom($in_file);

    $src_width = imagesx($source);
    $src_height = imagesy($source);

    $f = explode('x', $format);
    if ($src_width > $src_height) {
      $new_width = $f[0];
      $new_height = $f[1];
    } else {
      $new_width = $f[1];
      $new_height = $f[0];
    }

    $destination = imagecreatetruecolor($new_width, $new_height);

    $new_dwh = $new_width / $new_height; // stosunek dł/wys.
    $src_dwh = $src_width / $src_height;

    if ($src_dwh == $new_dwh)
    { // proporcjonalne
      $fin_x = 0;
      $fin_y = 0;
      $fin_width = $src_width;
      $fin_height = $src_height;
    } elseif ($src_dwh > $new_dwh) { // src bardziej horyzontalny, new bardziej wertykalny
      $fin_height = $src_height;
      $fin_width = ceil($src_height * $new_width / $new_height);
      $fin_x = ($src_width - $fin_width) / 2;
      $fin_y = 0;
    } elseif ($src_dwh < $new_dwh) { // src bardziej wertykalny, new bardziej horyzontalny
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

    self::imagePut($destination, $out_file);

    imagedestroy($source);
  }

 /**
  * Performs WATERMARK operation using GD library.
  *
  * @param String $wm_file - watermark file path
  * @param String $in_file - input file path
  * @param String $out_file - output file path
  */
  protected static function GDWatermark($in_file, $out_file, $wm_file)
  {
    $watermark = imagecreatefrompng($wm_file);

    $image = self::imageCreateFrom($in_file);

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

    self::imagePut($image, $out_file);

    imagedestroy($image);
    imagedestroy($watermark);
  }

/*============================================================================*/
/*============================ Imagick library ===============================*/

 /**
  * Performs RESIZE operation on given input and output files using GD library.
  *
  * @param String $in_file - input file path
  * @param String $out_file - output file path
  * @param String $format - output file format, e. g. '800x600'
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
  * Performs WATERMARK operation on given input, output and watermark files
  * using Imagick library.
  *
  * @param String $wm_file - watermark file path
  * @param String $in_file - input file path
  * @param String $out_file - output file path
  */
  protected static function ImageMagickWatermark($in_file, $out_file, $wm_file)
  {
    $command_composite =
      "composite -gravity SouthWest ".
      escapeshellarg($wm_file).
      " ".
      escapeshellarg($in_file).
      " -strip ".
      escapeshellarg($out_file);

    shell_exec($command_composite);
  }

/*============================================================================*/
/*================================ interface =================================*/

 /**
  * Performs RESIZE operation on given input and output files with library mode
  * specified ('mode' parameter with value either 'gd' or 'im').
  *
  * @param String $mode - external library mode - either 'gd' or 'im'
  * @param String $in_file - input file path
  * @param String $out_file - output file path
  * @param String $format - output file format, e. g. '800x600'
  * @param Boolean $switch - if the format shall be automatically switched
  */
  public static function Resize($mode, $in_file, $out_file, $format, $switch = true)
  {
    switch($mode)
    {
      case 'im':
        self::ImageMagickResize($in_file, $out_file, $format);
        break;
      case 'gd':
        self::GDResize($in_file, $out_file, $format, $switch);
        break;
    }
  }

 /**
  * Performs WATERMARK operation on given input, output and watermark files
  * with library mode specified ('mode' parameter with value either 'gd' or
  * 'im').
  *
  * @param String $mode - external library mode - either 'gd' or 'im'
  * @param String $wm_file - watermark file path
  * @param String $in_file - input file path
  * @param String $mid_file - auxiliary file path (created after resize andused to put a watermark on)
  * @param String $out_file - output file path
  * @param String $format - output file format, e. g. '800x600'
  */
  public static function Watermark($mode, $in_file, $mid_file, $out_file, $format, $wm_file, $switch = true)
  {
    switch($mode)
    {
      case 'im':
        self::ImageMagickResize($in_file, $mid_file, $format);
        self::ImageMagickWatermark($mid_file, $out_file, $wm_file);
        break;
      case 'gd':
        self::GDResize($in_file, $mid_file, $format, $switch);
        self::GDWatermark($mid_file, $out_file, $wm_file);
        break;
    }
  }
}

?>