<div class="row">
  <div class="form-group col-md-6">
    <label class="control-label">Nasabah</label>
    <div class="input-group">
      <select class="form-control" name="NoNasabah" required style="width: 100%;" required></select>
      <span class="input-group-btn">
        <button  data-toggle="modal" data-target="#modal-nasabah" type="button" class="btn btn-info btn-flat"><i class="glyphicon glyphicon-plus"></i></button>
      </span>
    </div>
  </div>
  <div class="form-group col-md-6">
    <label class="control-label">Rekening Tabungan</label>
    <div class="input-group">
      <input type="text" readonly class="form-control" name="NoTabungan" required>
      <span class="input-group-btn">
        <button id="btntabungan" disabled type="button" class="btn btn-info btn-flat"><i class="glyphicon glyphicon-plus"></i></button>
      </span>
    </div>
    <span class="help-block with-errors"></span>
  </div>
</div>
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
<div class="row">
  <div class="form-group col-md-6">
    <label class="control-label">Setoran Tabungan</label>
    <div class="input-group">
      <span class="input-group-addon">Rp</span>
      <input type="text" class="form-control formuang" name="PotonganTabungan" required>
    </div>
    <span class="help-block with-errors"></span>
  </div>
  <div class="form-group col-md-6">
    <label class="control-label">Biaya Asuransi</label>
    <div class="input-group">
      <span class="input-group-addon">Rp</span>
      <input type="text" class="form-control formuang" name="PotonganAsuransi" required>
    </div>
    <span class="help-block with-errors"></span>
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
