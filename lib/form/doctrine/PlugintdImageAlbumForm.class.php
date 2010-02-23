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
    $this->manageWidgets();
    $this->manageValidators();
    $this->embedRelation('Images');

    $new_image_form = new tdImageForm();
    $new_image_form->setDefault('td_image_album_id', $this->object->id);
    $this->embedForm('new', $new_image_form);
  }

  protected function removeFields()
  {
    unset($this['created_at'], $this['updated_at']);
  }

  protected function manageWidgets()
  {
    $this->widgetSchema['td_watermark_id']->setOption('add_empty', '&rarr; Wybierz watermark &larr;');
  }

  protected function manageValidators()
  {
    $this->setValidator('name',
      new sfValidatorString(array(), array('required' => 'Musisz podać nazwę albumu.')));

    $this->setValidator('description',
      new sfValidatorString(array(), array('required' => 'Musisz podać opis albumu.')));
  }

  protected function doBind(array $values)
  {
    if ($this->isValid()
            && '' === trim($values['new']['file']['name'])
            && '' === trim($values['new']['name'])
            && '' === trim($values['new']['description']))
    {
      unset($values['new'], $this['new']);
    }

    if (isset($values['Images']))
    {
      foreach ($values['Images'] as $i => $imageValues)
      {
        if (isset($imageValues['delete']) && $imageValues['id'])
        {
          $this->scheduledForDeletion[$i] = $imageValues['id'];
        }
      }
    }

    parent::doBind($values);
  }

  /**
   * Updates object with provided values, dealing with evantual relation deletion
   *
   * @see sfFormDoctrine::doUpdateObject()
   */
  protected function doUpdateObject($values)
  {
    if (isset($this->scheduledForDeletion))
    {
      foreach ($this->scheduledForDeletion as $index => $id)
      {
        unset($values['Images'][$index]);
        unset($this->object['Images'][$index]);
        $image = Doctrine::getTable('tdImage')->findOneById($id);
        unlink(sfConfig::get('td_visual_factory_image_dir').'/'.$image->getFile());
        $image->delete();
      }
    }

    $this->getObject()->fromArray($values);
  }


  /**
   * Saves embedded form objects.
   *
   * @param mixed $con   An optional connection object
   * @param array $forms An array of forms
   */
  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (null === $con)
    {
      $con = $this->getConnection();
    }

    if (null === $forms)
    {
      $forms = $this->embeddedForms;
    }

    foreach ($forms as $form)
    {
      if ($form instanceof sfFormObject)
      {
        if (!isset($this->scheduledForDeletion) || !in_array($form->getObject()->getId(), $this->scheduledForDeletion))
        {
          $form->saveEmbeddedForms($con);
          $form->getObject()->save($con);
        }
      }
      else
      {
        $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
      }
    }
  }
}
