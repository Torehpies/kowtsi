<?php  if (count($errors) > 0) : ?>
	<div class="error">
		<?php foreach ($errors as $error) : ?>
			<p class = "error" style = "color: red"><?php echo $error ?></p>
		<?php endforeach ?>
	</div>
<?php  endif ?>
