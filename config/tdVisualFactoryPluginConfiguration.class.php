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
    // number of days for visitor counter statistics
    sfConfig::set('td_visual_factory_watermark_dir', sfConfig::get('sf_upload_dir').'/watermarks');

    // number of days for visitor counter statistics
    sfConfig::set('td_visual_factory_image_dir', sfConfig::get('sf_upload_dir').'/images');
  }
}