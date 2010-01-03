<?php

/**
 * PlugintdWatermark form.
 *
 * @package    tdVisualFactoryPlugin
 * @subpackage form
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PlugintdWatermarkForm extends BasetdWatermarkForm
{
  public function setup()
  {
    parent::setup();

    $this->removeFields();

    $this->manageWidgets();

    $this->manageValidators();
  }

  protected function removeFields()
  {
    unset($this['created_at'], $this['updated_at']);
  }

  protected function manageWidgets()
  {
    $this->setWidget('file', new sfWidgetFormInputFileEditable(array(
      'with_delete' => false,
      'delete_label' => 'usuń zdjęcie watermarka',
      'label'     => 'Watermark image',
      'file_src'  => '/uploads/watermarks/'.$this->getObject()->getFile(),
      'is_image'  => true,
      'edit_mode' => !$this->isNew(),
      'template'  => '<div class="admin-watermark">%file%<br />%input%<br />%delete% %delete_label%</div>',
    )));
  }

  protected function manageValidators()
  {
    $this->setValidator('name',
      new sfValidatorString(array(), array('required' => 'Musisz podać nazwę watermarka.')));

    $this->setValidator('file', new sfValidatorFile(array(
      'required'   => true,
      'path'       => sfConfig::get('td_visual_factory_watermark_dir'),
      'mime_types' => 'web_images',
    ), array(
      'required' => 'Musisz wybrać plik',
    )));
  }
}
