<?php use_helper('I18N') ?>
<li class="sf_admin_action_activate" id="ajax_activate_<?php echo $td_image_album->getId() ?>">
<?php use_helper('jQuery'); ?>
  <?php echo jq_link_to_remote(__('Activate', array(), 'sf_admin'), array(
    'update'   => 'image_is_active_action_'.$td_image_album->getId(),
    'url'      => '@ajax_image_activate?id='.$td_image_album->getId(),
    'script' => true,
    'complete' => jq_remote_function( array(
      'update' => 'image_is_active_column_'.$td_image_album->getId(),
      'url'    => 'graphics/tick',
      'script' => true
    )),
  )) ?>
</li>
