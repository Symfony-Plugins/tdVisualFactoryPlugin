<?php

/**
 * PlugintdImage form.
 *
 * @package    tdVisualFactoryPlugin
 * @subpackage filter
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PlugintdImageFormFilter extends BasetdImageFormFilter
{
  public function setup()
  {
    parent::setup();

    $this->removeFields();
  }

  protected function removeFields()
  {
    unset( $this['file'] );
  }
}
