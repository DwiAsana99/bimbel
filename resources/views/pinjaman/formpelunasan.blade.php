<script id="lunas-template" type="text/x-handlebars-template">
  <div id="datapelunasan">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label">No. Nasabah</label>
          <input disabled type="text" class="form-control" value="@{{ NoNasabah }}">
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-8">
        <div class="form-group">
          <label class="control-label">Nama Nasabah</label>
          <input disabled type="text" class="form-control" value="@{{ NamaNasabah }}">
          <span class="help-block with-errors"></span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label">Jumlah Pinjaman</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input disabled type="text" class="form-control" value="@{{formatuang JumlahPinjaman }}">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label class="control-label">Tanggal Realisasi</label>
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input disabled type="text" class="form-control" value="@{{ TglInput }}">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label class="control-label">Jangka Waktu</label>
          <div class="input-group">
            <input disabled type="text" class="form-control" value="@{{ JangkaWaktu }}">
            <span class="input-group-addon">Bulan</span>
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label class="control-label">Tanggal Jatuh Tempo</label>
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input disabled type="text" class="form-control" value="@{{ TglJatuhTempo }}">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group has-error">
          <label class="control-label">Pokok Harus Bayar</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input disabled type="text" class="form-control" value="@{{formatuang JumlahPinjaman }}">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group has-success">
          <label class="control-label">Pokok Sudah Bayar</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input disabled type="text" class="form-control" value="@{{formatuang PokokDibayar }}">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group has-warning">
          <label class="control-label">Sisa Pokok</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input disabled id="pokok_lunas" type="text" class="form-control formuang">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group has-error">
          <label class="control-label">Bunga Harus Bayar</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input disabled type="text" class="form-control" value="@{{formatuang BungaNominal }}">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group has-success">
          <label class="control-label">Bunga Sudah Bayar</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input disabled type="text" class="form-control" value="@{{formatuang BungaDibayar }}">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group has-warning">
          <label class="control-label">Sisa Bunga</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input {{ $setting->InputBunga == true ? "" : "readonly" }} name="bunga_lunas" type="text" class="form-control formuang">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="control-label">Total Pembayaran</label>
          <div class="input-group">
            <span class="input-group-addon"><strong style="font-size: 24px;">Rp</strong></span>
            <input readonly name="total_lunas" type="text" class="form-control input-lg formuang" style="font-size: 34px;">
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
    </div>
  </div>
</script>
