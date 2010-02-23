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
    $this->manageHidden();
    $this->manageDelete();
    $this->manageFiles();
    $this->manageLabels();
    
    $this->setValidator('name',
      new sfValidatorString(array(), array('required' => 'Musisz podać nazwę zdjęcia.')));
  }

  protected function removeFields()
  {
    unset($this['created_at'], $this['updated_at']);
  }

  protected function manageHidden()
  {
    $this->widgetSchema['td_image_album_id'] = new sfWidgetFormInputHidden();
  }

  protected function manageDelete()
  {
    if ($this->object->exists())
    {
      $this->widgetSchema['delete'] = new sfWidgetFormInputCheckbox();
      $this->validatorSchema['delete'] = new sfValidatorPass();
    }
  }

  protected function manageFiles()
  {
    $thumb = sfConfig::get('td_visual_factory_size_thumbnail');
    $this->setWidget('file', new sfWidgetFormInputFileEditable(array(
      'with_delete' => false,
      'delete_label' => 'usuń zdjęcie',
      'file_src'  => '/uploads/td/images/'.$thumb.'/'.$this->getObject()->getFile(),
      'is_image'  => true,
      'edit_mode' => !$this->isNew(),
      'template'  => '%file%<br />%input%<br />%delete% %delete_label%',
    )));

    $this->setValidator('file', new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('td_visual_factory_image_dir'),
      'mime_types' => 'web_images',
    ), array(
      'required' => 'Musisz wybrać plik',
    )));
  }

  protected function manageLabels()
  {
    $this->widgetSchema->setLabels(array(
      'file'        => 'Plik',
      'name'        => 'Nazwa',
      'description' => 'Opis',
      'delete'      => 'Usuń',
    ));
  }

//  protected function doSave($con = null)
//  {
//    if (file_exists($this->getObject()->getFile()))
//    {
//      unlink($this->getObject()->getFile());
//    }
//    var_dump($this); exit;
//
//    $file = $this->getValue('file');
//    $filename = sha1($file->getOriginalName()).'.dupa'.$file->getExtension($file->getOriginalExtension());
//    $file->save(sfConfig::get('sf_upload_dir').'/'.$filename);
//
//    return parent::doSave($con);
//  }
}
