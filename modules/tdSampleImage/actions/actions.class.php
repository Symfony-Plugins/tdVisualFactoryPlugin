<?php

/**
 * tdSampleImage actions.
 *
 * @package    gospel
 * @subpackage tdSampleImage
 * @author     Tomasz Ducin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tdSampleImageActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->albums = Doctrine::getTable('tdImageAlbum')
      ->getActiveAlbumsQuery()
      ->fetchArray();

    $this->forward404Unless(count($this->albums) > 0);

    $this->getResponse()->addStylesheet('/tdVisualFactoryPlugin/css/td_image.css');
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($collection = Doctrine::getTable('tdImageAlbum')
      ->getActiveAlbumWithImagesByIdQuery($request->getParameter('id'))
      ->fetchArray());

    $this->album = $collection[0];

    $this->getResponse()->addStylesheet('/tdVisualFactoryPlugin/css/lightbox.css');
    $this->getResponse()->addStylesheet('/tdVisualFactoryPlugin/css/td_image.css');
  }
}
