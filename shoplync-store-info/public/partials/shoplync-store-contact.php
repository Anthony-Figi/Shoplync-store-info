<?php 
	//Grab all options
	$options = get_option($this->plugin_name);

	// Cleanup
	$phone = $options['tel'];
	$email = $options['email'];
	$base_url = get_bloginfo('wpurl');
?>

<nav class="header-nav">
<div class="container">
<div class="row">
	<div class="col-md-5 col-xs-12">
		<div id="_desktop_contact_link">
			<div id="contact-link">
			<?php if(!empty($phone)): ?>
				<span class="shop-tel"><i class="fa fa-phone"></i><a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a></span>
			<?php endif ?>
			<?php if(!empty($phone)): ?>
								<span class="shop-email"><i class="fa fa-envelope"></i><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></span>
			<?php else: ?>
								<span class="shop-email"><i class="fa fa-envelope"></i><a href="<?php echo $base_url."/contact"?>">Contact Us</a></span>
			<?php endif ?>
			</div>
		</div>
	</div>
	<div class="col-md-7 right-nav"></div>
</div>
</div>
</nav>