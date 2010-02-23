<?php use_helper('I18N', 'Date') ?>

<h1><?php echo __('Gallery list', array(), 'td') ?></h1>

<?php if ($albums): ?>
<ul id="image">
  <?php foreach ($albums as $album): ?>
    <li>
      <div class="name">
        <span class="title"><?php echo __('Name', array(), 'td') ?>: </span>
        <span class="value"><?php echo $album['name'] ?></span>
      </div>
      <div class="author">
        <span class="title"><?php echo __('Description', array(), 'td') ?>: </span>
        <span class="value"><?php echo $album['description'] ?></span>
      </div>
      <div class="recorded_at">
        <span class="title"><?php echo __('Created at', array(), 'td') ?>: </span>
        <span class="value"><?php echo (false !== strtotime($album['created_at']) ? format_date($album['created_at'], "f") : '&nbsp;') ?></span>
      </div>
      <div class="link">
        <span><?php echo link_to(__('watch it', array(), 'td'), '@td_sample_image_show?id='.$album['id']) ?></span>
      </div>
    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
