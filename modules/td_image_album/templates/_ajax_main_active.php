<span id="image_is_active_action_<?php echo $td_image_album->getId() ?>">
  <?php if ($td_image_album->getActive()): ?>
    <?php include_partial('td_image_album/ajax_deactivate', array('td_image_album' => $td_image_album)) ?>
  <?php else: ?>
    <?php include_partial('td_image_album/ajax_activate', array('td_image_album' => $td_image_album)) ?>
  <?php endif; ?>
</span>