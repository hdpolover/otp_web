<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="row">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h2 class="font-weight-bold">Selamat datang, <?= $this->session->userdata('nama'); ?>!</h2>
            <h6 class="font-weight-normal mb-0"><span class="text-primary"><?= $this->session->userdata('email'); ?></span></h6>
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
            <div class="card">
              <div class="card-header">
                <h4 class="card-header-title">Riwayat Perangkat Lain</h4>
                <h6 class="font-weight-normal mb-0">Anda login di perangkat lain di bawah ini.</h6>
              </div>
              <div class="card-body">
                <table class="table table-hover table-bordered">
                  <thead class="thead-light">
                    <tr>
                      <th>No.</th>
                      <th>Status</th>
                      <th>IP</th>
                      <th>Perangkat</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($history_ip != false) { ?>
                      <?php $no = 1;
                      foreach ($history_ip as $key) { ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><span class="badge badge-<?= $key->status == 1 ? 'info' : 'danger'; ?>"><?= $key->status == 1 ? 'diperbolehkan' : 'blok akses'; ?></span></td>
                          <td><?= $key->last_ip; ?></td>
                          <td><?= $key->device; ?></td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- content-wrapper ends -->