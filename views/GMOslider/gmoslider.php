<div class="gmoslider flexslider <?php echo htmlspecialchars($styleName); ?><?php echo " " . $settings['textPosition']; ?>" data-session-id="<?php echo htmlspecialchars($sessionID); ?>">

	<ul class="slides">

		<?php if(is_array($views) && count($views) > 0) : ?>
			<?php foreach($views as $view) : ?>
				<li><?php echo $view->toHTML(); ?></li>
			<?php endforeach; ?>
		<?php endif; ?>
		

	</ul>

</div>

