<?php

/**
 * Visual Factory
 *
 * Class performing basic image transforming operations using GD and Imagick
 * libraries.
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
   * @param String $filepath
   * @return resource an image resource identifier on success, false on errors.
   */
  protected static function imageCreateFrom($filepath)
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
   * @param resource an image resource identifier on success, false on errors.
   * @param String $filepath
   */
  protected static function imagePut($image, $filepath)
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
  * Performs WATERMARK operation using GD library.
  *
  * @param String $wm_file - watermark file path
  * @param String $in_file - input file path
  * @param String $out_file - output file path
  */
  protected static function putWatermark($wm_file, $in_file, $out_file)
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

 /**
  * Performs RESIZE operation using GD library.
  *
  * @param String $in_file - input file path
  * @param String $out_file - output file path
  * @param Integer $new_width - new width value of the output image
  * @param Integer $new_height - new height value of the output image
  */
  protected static function putResized($in_file, $out_file, $new_width, $new_height)
  {
    $destination = imagecreatetruecolor($new_width, $new_height);

    $image = self::imageCreateFrom($in_file);

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

    self::imagePut($destination, $out_file);

    imagedestroy($image);
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
  * @param String $mid_file - auxiliary file path (created after resize and used to put a watermark on)
  * @param String $out_file - output file path
  * @param String $format - output file format, e. g. '800x600'
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

/*============================================================================*/
/*============================ GD library ====================================*/

 /**
  * Performs RESIZE operation on given input and output files using GD library.
  *
  * @param String $in_file - input file path
  * @param String $out_file - output file path
  * @param String $format - output file format, e. g. '800x600'
  */
  protected static function GDResize($in_file, $out_file, $format)
  {
    $f = explode('x', $format);
    self::putResized($in_file, $out_file, $f[0], $f[1]);
  }

 /**
  * Performs WATERMARK operation on given input, output and watermark files
  * using GD library.
  *
  * @param String $wm_file - watermark file path
  * @param String $in_file - input file path
  * @param String $mid_file - auxiliary file path (created after resize and used to put a watermark on)
  * @param String $out_file - output file path
  * @param String $format - output file format, e. g. '800x600'
  */
  protected static function GDWatermark($wm_file, $in_file, $mid_file, $out_file, $format)
  {
    $f = explode('x', $format);
    self::putResized($in_file, $mid_file, $f[0], $f[1]);
    self::putWatermark($wm_file, $mid_file, $out_file);
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