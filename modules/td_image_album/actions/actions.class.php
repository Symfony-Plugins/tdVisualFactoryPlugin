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
   * Activates selected image albums.
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
   * Deactivates selected image albums.
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
   * Activates an album from admin generator list using AJAX.
   *
   * @param sfWebRequest $request
   * @return Partial - generated partial enabling album deactivating (switch).
   */
  public function executeActivate(sfWebRequest $request)
  {
    $album = Doctrine::getTable('tdImageAlbum')->findOneById($request->getParameter('id'));
    $album->activate();
    return $this->renderPartial('td_image_album/ajax_deactivate', array('td_image_album' => $album));
  }

  /**
   * Deactivates an album from admin generator list using AJAX.
   *
   * @param sfWebRequest $request
   * @return Partial - generated partial enabling album activating (switch).
   */
  public function executeDeactivate(sfWebRequest $request)
  {
    $album = Doctrine::getTable('tdImageAlbum')->findOneById($request->getParameter('id'));
    $album->deactivate();
    return $this->renderPartial('td_image_album/ajax_activate', array('td_image_album' => $album));
  }
}
