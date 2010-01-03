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
    $this->setWidget('file_md5', new sfWidgetFormInputFileEditable(array(
      'with_delete' => true,
      'delete_label' => 'delete watermark image',
      'label'     => 'Watermark image',
      'file_src'  => '/uploads/watermarks/'.$this->getObject()->getFileMd5(),
      'is_image'  => true,
      'edit_mode' => !$this->isNew(),
      'template'  => '<div class="admin-watermark">%file%<br />%input%<br />%delete% %delete_label%</div>',
    )));

//    $this->setWidget(array(
//      'upload' => new sfWidgetFormInputSWFUpload()
//    ));
  }

  protected function manageValidators()
  {
    $this->setValidator('title',
      new sfValidatorString(array(), array('required' => 'Musisz podać nazwę watermarka.')));

    $this->setValidator('file_md5', new sfValidatorFile(array(
      'required'   => true,
      'path'       => sfConfig::get('td_visual_factory_watermark_dir'),
      'mime_types' => 'web_images',
    ), array(
      'required' => 'Musisz wybrać plik',
    )));
  }
}
