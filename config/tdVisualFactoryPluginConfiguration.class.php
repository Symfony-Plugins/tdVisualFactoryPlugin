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
    sfConfig::set('td_visual_factory_watermark_dir', sfConfig::get('sf_web_dir').'/uploads/td/watermarks');

    // image upload dir
    sfConfig::set('td_visual_factory_image_dir', sfConfig::get('sf_upload_dir').'/td/images');

    // image sizes
    sfConfig::set('td_visual_factory_sizes', array('150x100', '600x400'));

    // watermarked image sizes
    sfConfig::set('td_visual_factory_watermark_sizes', array('600x400'));

    // temporary watermarked image prefix
    sfConfig::set('td_visual_factory_watermark_prefix', 'tmp-');

    // image thumbnail size index
    sfConfig::set('td_visual_factory_size_thumbnail', '150x100');

    // image full size index
    sfConfig::set('td_visual_factory_size_full', '600x400');

    // visual mode
    sfConfig::set('td_visual_factory_mode', 'gd');
  }
}