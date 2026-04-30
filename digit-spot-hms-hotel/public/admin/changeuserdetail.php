<?php

	//update user profile

	if(isset($_POST['userdetailbutton'])) {

		$fieldset1 = escape_data($_POST['fieldset3']);
		$fieldset2 = escape_data($_POST['fieldset4']);
		$fieldset3 = escape_data($_POST['fieldset5']);
		$fieldset4 = escape_data($_POST['fieldset6']);

		$insert_query = array("id"=>USER_AUTHEN_ID);
		$insert_sql = array("staffname"=>ucwords(strtolower($fieldset1)),"emailaddress"=>strtolower($fieldset2),"mobile"=>$fieldset3,"gender"=>$fieldset4);
		$isProfileUpdated = mysqli_data_update($tbL7,$insert_sql,$insert_query);

		if(isset($isProfileUpdated) && $isProfileUpdated == 2) {
			?>
				<script> objHidden('modal-box'); objDisplay('pause-state'); writeObjheader('pause-state-msg','Updated successfully!'); autohidePopupBox('pause-state',3000); setTimeout(function() { window.location="?pf=y"; },4000); </script>
			<?php
		}
	}

?>