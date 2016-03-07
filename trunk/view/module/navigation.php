<?php
	/*
	 * Determine if the user has a valid session and display the
	 * appropriate navigation bar.
	 */
	if (!is_null($this->user) && $this->user->verify()) {
		include_once('view/module/navigation_logged_in.php');
	} else {
		include_once('view/module/navigation_logged_out.php');
	}

	date_default_timezone_set("America/Los_Angeles");
?>
