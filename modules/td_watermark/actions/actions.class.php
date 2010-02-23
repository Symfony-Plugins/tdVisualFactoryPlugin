<?php

require_once dirname(__FILE__).'/../lib/td_watermarkGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/td_watermarkGeneratorHelper.class.php';

/**
 * td_watermark actions.
 *
 * @package    tdVisualFactoryPlugin
 * @subpackage backend
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class td_watermarkActions extends autoTd_watermarkActions
{
  public function postExecute()
  {
    parent::postExecute();
    $this->getResponse()->addStylesheet('/tdVisualFactoryPlugin/css/td_image.css');
  }
}
