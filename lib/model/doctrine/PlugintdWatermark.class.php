<?php

/**
 * PlugintdWatermark
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    tdVisualFactoryPlugin
 * @subpackage model
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PlugintdWatermark extends BasetdWatermark
{
  /**
   * Returns short description of the watermark.
   *
   * @return String - short description.
   */
  public function getDescriptionShort()
  {
    return tdTools::getMbShortenedString($this->getDescription(), sfConfig::get('td_short_text_sign_count'));
  }
}