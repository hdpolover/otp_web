<div class="main-panel">
	<div class="content-wrapper">
		<div class="row">
			<div class="col-md-12 grid-margin">
				<div class="row">
					<div class="col-12 col-xl-8 mb-4 mb-xl-0">
						<h2 class="font-weight-bold">Selamat datang, <?= $this->session->userdata('nama'); ?>!</h2>
						<h6 class="font-weight-normal mb-0"><span
								class="text-primary">Anda terdaftar pada <?= date("d F Y - H:i", strtotime($user->tgl_bergabung)); ?></span></h6>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 grid-margin stretch-card">
				<div class="card tale-bg">
					<div class="card-people mt-auto">
						<img src="<?= base_url('assets/'); ?>images/dashboard/people.svg" alt="people">
					</div>
				</div>
			</div>
			<div class="col-md-6 grid-margin transparent">
				<div class="row">
					<div class="col-md-12">
						<div class="card ">
							<div class="card-header ">
								<h4 class="card-header-title">Informasi Pengguna</h4>
								<h6 class="font-weight-normal mb-0">Berikut merupakan informasi pribadimu.</h6>
							</div>
							<div class="card-body">
								<form action="<?= site_url('home/simpan_info'); ?>" method="POST">
									<div class="form-group">
										<label for="inputNama" class="input-label">Nama <small class="text-danger">*</small></label>
										<input type="text" class="form-control" name="nama" id="inputNama" value="<?= $user->nama; ?>"
											required>
									</div>
									<div class="form-group">
										<label for="inputTelp" class="input-label">Nomor Telepon <small
												class="text-danger">*</small></label>
										<input type="tel" class="form-control" name="no_telp" id="inputTelp" value="<?= $user->no_telp; ?>"
											required>
									</div>
									<div class="form-group">
										<label for="inputEmail" class="input-label">Email <small class="text-danger">*</small></label>
										<input type="email" class="form-control" name="email" id="inputEmail" value="<?= $user->email; ?>"
											required>
									</div>
									<hr>
									<button type="submit" class="btn btn-primary float-right" id="send-button">simpan</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- content-wrapper ends -->


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