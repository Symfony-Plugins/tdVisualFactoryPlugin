<?php
/**
 * tdVisualFactoryPluginConfiguration.class
 */

/**
 * tdVisualFactoryPluginConfiguration
 *
 * @package    tdVisualFactoryPlugin
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 */

class tdVisualFactoryPluginConfiguration extends sfPluginConfiguration
{
  /**
   * Initialize
   */
  public function initialize()
  {
    // watermark upload dir
    sfConfig::set('td_visual_factory_watermark_dir', sfConfig::get('sf_upload_dir').'/watermarks');

    // image upload dir
    sfConfig::set('td_visual_factory_image_dir', sfConfig::get('sf_upload_dir').'/images');
  }
}