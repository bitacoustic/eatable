<div style="z-index: 1000;position:fixed;width:auto;height:auto;bottom:0px;left:0px;font-size:0.7em;background-color: rgba(255,255,255,0.75)">
	<?php
		if(!is_null($this->user)) {
			echo "<strong><em>Debug</em></strong> &nbsp;&nbsp; " .
			 	 "<strong>Class:</strong> " . get_class($this->user) . " &nbsp;&nbsp; " . 
			 	 "<strong>id:</strong> " . $this->user->id . " &nbsp;&nbsp; " . 
				 "<strong>fname:</strong> " . $this->user->fname . " &nbsp;&nbsp; " .
				 "<strong>lname:</strong> " . $this->user->lname . " &nbsp;&nbsp; " .
				 "<strong>email:</strong> " . $this->user->email . " &nbsp;&nbsp; " .
				 "<strong>password:</strong> " . $this->user->password . " &nbsp;&nbsp; " .
				 "<strong>phone_number:</strong> " . $this->user->phone_number . " &nbsp;&nbsp; " .
				 "<strong>session_id:</strong> " . $this->user->session_id . " &nbsp;&nbsp; ";
		}
	?>
</div>