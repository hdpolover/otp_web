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
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="<?= base_url('assets/'); ?>images/logo.svg" class="w-100" alt="logo">
              </div>
              <h4>Selamat datang!</h4>
              <h6 class="font-weight-light">Masukan kode aktivasi Anda. Cek kotak masuk atau spam folder email Anda.</h6>
              <form class="pt-3" action="<?= site_url('login/aktivasi_akun'); ?>" method="post">
                <div class="form-group">
                  <input type="number" class="form-control form-control-lg" name="kode_aktivasi" id="exampleInputEmail1" placeholder="Kode Aktivasi">
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="send-button">AKTIVASI AKUN</button>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Belum menerima email? <a href="<?= site_url('aktivasi-akun'); ?>" class="text-primary">Kirim ulang</a>
                </div>
              </form>
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
