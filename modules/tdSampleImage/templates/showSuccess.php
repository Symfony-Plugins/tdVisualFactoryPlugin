<?php
use_helper('I18N', 'Date');
$thumbnail_size = sfConfig::get('td_visual_factory_size_thumbnail');
$full_size = sfConfig::get('td_visual_factory_size_full');
?>

<h1><?php echo __('Gallery', array(), 'td') ?>: <?php echo $album['name'] ?></h1>

<ul id="image">
  <li>
      <div class="author">
        <span class="title"><?php echo __('Description', array(), 'td') ?>: </span>
        <span class="value"><?php echo $album['description'] ?></span>
      </div>
<?php foreach($album['Images'] as $image): ?>
    <a href="/uploads/td/images/<?php echo $full_size ?>/<?php echo $image['file'] ?>"
      title="<?php echo $image['name'].': '.$image['description'] ?>"
      rel="lightbox[roadtrip]">
        <img src="/uploads/td/images/<?php echo $thumbnail_size ?>/<?php echo $image['file'] ?>"
          alt="<?php echo $image['name'].': '.$image['description'] ?>"/>
    </a>
<?php endforeach; ?>
  </li>
</ul>
