<?php

/**
 * PlugintdImageAlbumTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    tdVisualFactoryPlugin
 * @subpackage model
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class PlugintdImageAlbumTable extends Doctrine_Table
{
  /**
   * Returns DQL query retrieving all active image albms.
   *
   * @return Doctrine_Query
   */
  static public function getActiveAlbumsQuery()
  {
    return Doctrine_Query::create()
             ->from('tdImageAlbum a')
             ->where('a.active = "1"');
  }

  /**
   * Returns DQL query retrieving active track album given by file.
   *
   * @param Integer $id - track album id
   * @return Doctrine_Query
   */
  static public function getActiveAlbumByIdQuery($id)
  {
    return Doctrine_Query::create()
             ->from('tdImageAlbum a')
             ->where('a.id = ?', $id)
             ->andWhere('a.active = "1"');
  }

  /**
   * Returns DQL query retrieving active track album given by file.
   *
   * @param Integer $id - track album id
   * @return Doctrine_Query
   */
  static public function getActiveAlbumWithImagesByIdQuery($id)
  {
    return self::getActiveAlbumByIdQuery($id)
             ->leftJoin('a.Images i');
  }

  /**
   * Returns DQL query retrieving albums selected by ids.
   *
   * @param Array $ids - Identifiers of selected albums.
   * @return Doctrine_Query
   */
  static public function getSelectedAlbumsQuery($ids)
  {
    return Doctrine_Query::create()
      ->from('tdImageAlbum a')
      ->whereIn('a.id', $ids);
  }

  /**
   * Returns ids of all active albums.
   *
   * @param Array $ids - Identifiers of active albums.
   * @return Array
   */
  static public function getActiveAlbumsIds()
  {
    $query = self::getActiveAlbumsQuery()
      ->select('a.id');
    $data = $query->fetchArray();

    $ids = array();
    foreach($data as $d)
    {
      $ids[] = $d['id'];
    }
    return $ids;
  }

  /**
   * Returns DQL query retrieving random albums.
   *
   * @param Integer $count - Number of albums to be returned by the query.
   * @return Doctrine_Query
   */
  static public function getRandomActiveAlbumsQuery($count)
  {
    $ids = self::getActiveAlbumsIds();
    // if less videos available than expected
    if ($count > count($ids)) $count = count($ids);

    $selected = array();
    for ($i = 0; $i < $count; $i++)
    {
      $id = rand(0, count($ids));
      while (!isset($ids[$id]))
        $id = rand(0, count($ids));
      $selected[] = $ids[$id];
      unset($ids[$id]);
    }

    return empty($selected) ? null : self::getSelectedAlbumsQuery($selected);
  }
}