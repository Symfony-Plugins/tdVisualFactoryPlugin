<td colspan="7">
  <?php echo __('<strong>Nazwa</strong>: <i>%%name%%</i><span id="image_is_active_column_'.$td_image_album->getId().'">%%active%%</span><br /><strong>Opis</strong>: <div class="text_box">%%description_short%%</div><br /><strong>Utworzono</strong>: <i>%%created_at%%</i><br /><strong>Zmieniono</strong>: <i>%%updated_at%%</i>', array('%%name%%' => $td_image_album->getName(), '%%active%%' => get_partial('td_image_album/list_field_boolean', array('value' => $td_image_album->getActive())), '%%description_short%%' => $td_image_album->getDescriptionShort(), '%%created_at%%' => false !== strtotime($td_image_album->getCreatedAt()) ? format_date($td_image_album->getCreatedAt(), "f") : '&nbsp;', '%%updated_at%%' => false !== strtotime($td_image_album->getUpdatedAt()) ? format_date($td_image_album->getUpdatedAt(), "f") : '&nbsp;'), 'sf_admin') ?>
</td>