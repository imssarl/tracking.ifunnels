<?php
session_start();
include("cron_scheduler.php");

echo "<script>window.close();

			if (!window.opener.closed) {
			//window.opener.location.reload();
			window.opener.location.href='manage_submission.php';
			window.opener.focus();
			}
			</script>";
?>