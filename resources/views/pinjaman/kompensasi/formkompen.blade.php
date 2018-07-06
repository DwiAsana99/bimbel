<div class="row">
  <div class="col-md-12 no-padding">
    <div class="box box-success collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title">Data Pelunasan Pinjaman Sebelumnya</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body" style="">
        <script id="lunas-kompen-template" type="text/x-handlebars-template">
          <div id="datakompenpelunasan">
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
                    <input name="bunga_lunas" type="text" class="form-control formuang">
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
                    <input readonly name="total_lunas" type="text" class="form-control input-lg formuang" style="font-size: 34px;" value="@{{formatuang Total }}">
                  </div>
                  <span class="help-block with-errors"></span>
                </div>
              </div>
            </div>
          </div>
        </script>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label class="control-label">Jaminan</label>
      <input type="text" class="form-control" name="Jaminan" required>
      <span class="help-block with-errors"></span>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label">Jumlah Pinjaman</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input type="text" class="form-control formuang" name="JumlahPinjaman" required>
          </div>
          <span class="help-block with-errors"></span>
        </div>
        <div class="form-group">
          <label class="control-label">Biaya Materai/Map</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input type="text" class="form-control formuang" name="PotonganMateraiMap" required>
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="control-label">Propisi</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input type="text" class="form-control formuang" name="PotonganPropisi" required>
          </div>
          <span class="help-block with-errors"></span>
        </div>
        <div class="form-group">
          <label class="control-label">Biaya Lain-Lain</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input type="text" class="form-control formuang" name="PotonganLain" required>
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label">Jumlah Diterima</label>
      <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <input readonly type="text" class="form-control formuang" name="JumlahDiterima" required>
      </div>
      <span class="help-block with-errors"></span>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label">Jangka Waktu</label>
          <div class="input-group">
            <input type="number" min="1" class="form-control" name="JangkaWaktu" value="1" required>
            <span class="input-group-addon">Bulan</span>
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-8">
        <div class="form-group">
          <label class="control-label">Angsuran</label>
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input readonly type="text" class="form-control formuang" name="AngsuranPerbulan" required>
            <span class="input-group-addon">Per Bulan</span>
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label class="control-label">Jatuh Tempo</label>
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input readonly type="text" class="form-control" name="TglJatuhTempo" required>
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label">Bunga / Bulan</label>
          <div class="input-group">
            <input type="text" class="form-control" name="Bunga" required>
            <span class="input-group-addon">%</span>
          </div>
          <span class="help-block with-errors"></span>
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-group">
          <label class="control-label">Jenis Bunga</label>
          <select class="form-control" name="JenisBunga" required>
            <option value="menetap">Menetap</option>
            <option value="menurun">Menurun</option>
          </select>
          <span class="help-block with-errors"></span>
        </div>
      </div>
    </div>
  </div>
</div>
