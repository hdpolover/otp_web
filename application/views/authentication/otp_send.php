<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Webotpku</title>
	<!-- plugins:css -->
	<link rel="stylesheet" href="<?= base_url('assets/'); ?>vendors/feather/feather.css">
	<link rel="stylesheet" href="<?= base_url('assets/'); ?>vendors/ti-icons/css/themify-icons.css">
	<link rel="stylesheet" href="<?= base_url('assets/'); ?>vendors/css/vendor.bundle.base.css">

	<link rel="stylesheet" href="<?= base_url(); ?>assets/plugin/sweetalert2/sweetalert2.min.css">
	<!-- endinject -->
	<!-- Plugin css for this page -->
	<!-- End plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="<?= base_url('assets/'); ?>css/vertical-layout-light/style.css">
	<!-- endinject -->
	<link rel="shortcut icon" href="<?= base_url('assets/'); ?>images/favicon.png" />

	<!-- plugins:js -->
	<script src="<?= base_url('assets/'); ?>vendors/js/vendor.bundle.base.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>assets/plugin/sweetalert2/sweetalert2.min.js"></script>
</head>

<body>
	<!-- ALERT -->
	<?php if ($this->session->flashdata('error')) { ?>
		<script>
			Swal.fire({
				text: '<?php echo $this->session->flashdata('error'); ?>',
				icon: 'info',
			})
		</script>
	<?php
	} ?>

	<?php if ($this->session->flashdata('warning')) { ?>
		<script>
			Swal.fire({
				text: '<?php echo $this->session->flashdata('warning'); ?>',
				icon: 'warning',
			})
		</script>
	<?php
	} ?>

	<?php if ($this->session->flashdata('success')) { ?>
		<script>
			Swal.fire({
				text: '<?php echo $this->session->flashdata('success'); ?>',
				icon: 'success',
			})
		</script>
	<?php
	} ?>

	<?php
	function mask_email($email)
	{
		$mail_parts = explode("@", $email);
		$domain_parts = explode('.', $mail_parts[1]);
		$mail_parts[0] = mask($mail_parts[0], 2, 1); // show first 2 letters and last 1 letter
		$domain_parts[0] = mask($domain_parts[0], 2, 1); // same here
		$mail_parts[1] = implode('.', $domain_parts);
		return implode("@", $mail_parts);
	}
	function mask($str, $first, $last)
	{
		$len = strlen($str);
		$toShow = $first + $last;
		return substr($str, 0, $len <= $toShow ? 0 : $first) . str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)) . substr($str, $len - $last, $len <= $toShow ? 0 : $last);
	}
	function mask_mobile_no($number)
	{
		return substr($number, 0, 2) . '******' . substr($number, -2);
	}
	?>

	<div class="container-scroller">
		<div class="container-fluid page-body-wrapper full-page-wrapper">
			<div class="content-wrapper d-flex align-items-center auth px-0">
				<div class="row w-100 mx-0">
					<div class="col-lg-6 mx-auto">
						<div class="auth-form-light text-left py-5 px-4 px-sm-5">
							<div class="brand-logo">
								<img src="<?= base_url('assets/'); ?>images/logo.svg" class="w-100" alt="logo">
							</div>
							<center>
								<h4>Verifikasi OTP</h4>
							</center>
							<center>Silkan pilih salah satu metode dibawah ini untuk menerima kode OTP sebelum login.</center>
							<div class="mt-3">
								<div class="btn-group btn-block" role="group" aria-label="Basic example" id="send-button">
									<a href="<?= site_url('send-otp/email'); ?>" class="btn btn-primary ">Kirim kode OTP via Email (<?= mask_email($this->session->userdata('email')); ?>)</a>
									<a href="<?= site_url('send-otp/sms'); ?>" class="btn btn-primary ">Kirim kode OTP via SMS (<?= mask_mobile_no($this->session->userdata('no_telp')); ?>)</a>
									<a href="<?= site_url('send-otp/wa'); ?>" class="btn btn-primary ">Kirim kode OTP via WA (<?= mask_mobile_no($this->session->userdata('no_telp')); ?>)</a>
								</div>
							</div>
							<div class="text-center mt-4 font-weight-light">
								Ganti akun? <a href="<?= site_url('logout'); ?>" class="text-primary">Keluar</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- content-wrapper ends -->
		</div>
		<!-- page-body-wrapper ends -->
	</div>
	<!-- container-scroller -->

	<script>
		$('form').submit(function(event) {
			$('#send-button').prop("disabled", true);
			// add spinner to button
			$('#send-button').html(
				`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
			);
			return;
		});
	</script>
	<!-- endinject -->
	<!-- Plugin js for this page -->
	<!-- End plugin js for this page -->
	<!-- inject:js -->
	<script src="<?= base_url('assets/'); ?>js/off-canvas.js"></script>
	<script src="<?= base_url('assets/'); ?>js/hoverable-collapse.js"></script>
	<script src="<?= base_url('assets/'); ?>js/template.js"></script>
	<script src="<?= base_url('assets/'); ?>js/settings.js"></script>
	<script src="<?= base_url('assets/'); ?>js/todolist.js"></script>
	<!-- endinject -->
</body>

</html>