<?php

/**
 * PlugintdImage form.
 *
 * @package    tdVisualFactoryPlugin
 * @subpackage form
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PlugintdImageForm extends BasetdImageForm
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
      'delete_label' => 'usuń plik zdjęcie',
      'label'     => 'Watermark image',
      'file_src'  => '/uploads/images/'.$this->getObject()->getFile(),
      'is_image'  => true,
      'edit_mode' => !$this->isNew(),
      'template'  => '%file%<br />%input%<br />%delete% %delete_label%',
    )));
  }

  protected function manageValidators()
  {
    $this->setValidator('name',
      new sfValidatorString(array(), array('required' => 'Musisz podać nazwę zdjęcia.')));

    $this->setValidator('file', new sfValidatorFile(array(
      'required'   => true,
      'path'       => sfConfig::get('td_visual_factory_image_dir'),
      'mime_types' => 'web_images',
    ), array(
      'required' => 'Musisz wybrać plik',
    )));
  }

  protected function doSave($con = null)
  {
    if (file_exists($this->getObject()->getFile()))
    {
      unlink($this->getObject()->getFile());
    }

    $file = $this->getValue('file');
    $filename = sha1($file->getOriginalName()).'.dupa'.$file->getExtension($file->getOriginalExtension());
    $file->save(sfConfig::get('sf_upload_dir').'/'.$filename);

    return parent::doSave($con);
  }
}
