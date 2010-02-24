<?php

require_once dirname(__FILE__).'/../lib/td_image_albumGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/td_image_albumGeneratorHelper.class.php';

/**
 * td_image_album actions.
 *
 * @package    tdVisualFactoryPlugin
 * @subpackage backend
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class td_image_albumActions extends autoTd_image_albumActions
{
  /**
   * Activates selected track albums.
   *
   * @param sfWebRequest $request
   */
  public function executeBatchActivate(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');
    $query = Doctrine::getTable('tdImageAlbum')->getSelectedAlbumsQuery($ids);

    foreach ($query->execute() as $album)
    {
      $album->activate(true);
    }

    $this->getUser()->setFlash('notice', 'The selected image albums have been activated successfully.');
    $this->redirect('@td_image_album');
  }

  /**
   * Deactivates selected track albums.
   *
   * @param sfWebRequest $request
   */
  public function executeBatchDeactivate(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');
    $query = Doctrine::getTable('tdImageAlbum')->getSelectedAlbumsQuery($ids);

    foreach ($query->execute() as $album)
    {
      $album->deactivate(true);
    }

    $this->getUser()->setFlash('notice', 'The selected image albums have been deactivated successfully.');
    $this->redirect('@td_image_album');
  }

  /**
   * Activates selected track album.
   *
   * @param sfWebRequest $request
   */
  public function executeListActivate(sfWebRequest $request)
  {
    $album = $this->getRoute()->getObject();
    $album->activate();

    $this->getUser()->setFlash('notice', 'The selected image album has been activated successfully.');
    $this->redirect('@td_image_album');
  }

  /**
   * Deactivates selected track album.
   *
   * @param sfWebRequest $request
   */
  public function executeListDeactivate(sfWebRequest $request)
  {
    $album = $this->getRoute()->getObject();
    $album->deactivate();

    $this->getUser()->setFlash('notice', 'The selected image album has been deactivated successfully.');

    $this->redirect('@td_image_album');
  }
}
