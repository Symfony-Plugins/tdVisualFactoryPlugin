<?php

/**
 * PlugintdImageAlbum form.
 *
 * @package    tdVisualFactoryPlugin
 * @subpackage form
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PlugintdImageAlbumForm extends BasetdImageAlbumForm
{
  public function setup()
  {
    parent::setup();

    $this->removeFields();

    $this->manageValidators();
  }

  protected function removeFields()
  {
    unset($this['created_at'], $this['updated_at']);
  }

  protected function manageValidators()
  {
    $this->setValidator('name',
      new sfValidatorString(array(), array('required' => 'Musisz podać nazwę albumu.')));

    $this->setValidator('description',
      new sfValidatorString(array(), array('required' => 'Musisz podać opis albumu.')));
  }
}
