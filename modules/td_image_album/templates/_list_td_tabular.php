<td class="sf_admin_text sf_admin_list_td_id" id="image_is_active_column_<?php echo $td_image_album->getId() ?>">
  <?php echo link_to($td_image_album->getId(), 'td_image_album_edit', $td_image_album) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_td_watermark_id">
  <?php echo $td_image_album->getTdWatermarkId() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo $td_image_album->getName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_description">
  <?php echo $td_image_album->getDescription() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_active">
  <?php echo get_partial('td_image_album/list_field_boolean', array('value' => $td_image_album->getActive())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_created_at">
  <?php echo false !== strtotime($td_image_album->getCreatedAt()) ? format_date($td_image_album->getCreatedAt(), "f") : '&nbsp;' ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($td_image_album->getUpdatedAt()) ? format_date($td_image_album->getUpdatedAt(), "f") : '&nbsp;' ?>
</td>
