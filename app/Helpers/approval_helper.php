<?php
if(!function_exists("approval_status")) {
	function approval_status($statusApproval) {
		switch($statusApproval) {
			case '0':
				return "<span class='text-warning' style='display:inline;'>Pending <i class='fa fa-clock'></i></span>";
			break;
			case '1':
				return "<span class='text-success' style='display:inline;'>Approved <i class='fa fa-check-circle'></i></span>";
			break;
			case '2':
				return "<span class='text-danger' style='display:inline;'>Rejected <i class='fa fa-times-circle'></i></span>";
			break;
			default:
				return "-";
		}
	}
}