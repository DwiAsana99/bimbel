{{-- <script id="bayar-template" type="text/x-handlebars-template">
  <div class="row" id="rincianbayar">
    <div class="col-md-12 no-padding">
      <div class="table-responsive">
        <table class="table table-condensed">
          <tbody>
            <tr>
              <td width="20%"> <strong>No. Nasabah</strong> </td>
              <td colspan="3">	@{{ NoNasabah }}</td>
            </tr>
            <tr style="border-bottom: 2px solid #7c3a3a;">
              <td width="20%"> <strong>Nama Nasabah</strong> </td>
              <td colspan="3">	@{{ NamaNasabah }}</td>
            </tr>
            <tr>
              <td width="20%"> <strong>No Pinjaman</strong> </td>
              <td> @{{ NoPinjaman }}</td>
              <td width="20%"><strong>Tunggakan Pokok</strong></td>
              <td> Rp @{{formatuang TunggakanPokok }}</td>
            </tr>
            <tr>
              <td><strong>Jumlah Pinjaman</strong></td>
              <td> Rp @{{formatuang JumlahPinjaman }}</td>
              <td><strong>Pokok Bulan Ini</strong></td>
              <td> Rp @{{formatuang PokokPeriodeIni }}</td>
            </tr>
            <tr>
              <td><strong>Jumlah Sudah Dibayar</strong></td>
              <td> Rp @{{formatuang SudahDibayar }}</td>
              <td><strong>Bunga Bulan Ini</strong></td>
              <td> Rp @{{formatuang BungaPeriodeIni }}</td>
            </tr>
            <tr>
              <td><strong>Sisa Pinjaman</strong></td>
              <td> Rp @{{formatuang SisaPinjaman }}</td>
              <td><strong>Denda</strong></td>
              <td> Rp @{{formatuang Denda }}</td>
            </tr>
            <tr style="border-bottom: 2px solid #7c3a3a;">
              <td><strong>Tgl Pembayaran Terakhir</strong></td>
              <td> @{{ BayarTerakhir }}</td>
              <td><strong>Total Tagihan</strong></td>
              <td> Rp @{{formatuang Total }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</script>
<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label class="control-label">Pembayaran Pokok</label>
      <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <input type="text" class="form-control formuang" name="Pokok" required>
      </div>
      <span class="help-block with-errors"></span>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label class="control-label">Pembayaran Bunga</label>
      <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <input readonly type="text" class="form-control formuang" name="NominalBunga" required>
      </div>
      <span class="help-block with-errors"></span>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label class="control-label">Pembayaran Denda</label>
      <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <input readonly type="text" class="form-control formuang" name="Denda" required>
      </div>
      <span class="help-block with-errors"></span>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label class="control-label">Total</label>
      <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <input readonly type="text" class="form-control formuang" name="Jumlah" required>
      </div>
      <span class="help-block with-errors"></span>
    </div>
  </div>
</div> --}}
<script id="bayar-template" type="text/x-handlebars-template">
  <div class="row" id="rincianbayar">
    <div class="col-md-12 no-padding">
      <div class="table-responsive">
        <table class="table table-condensed">
          <tbody>
            <tr>
              <td width="20%"> <strong>No. Nasabah</strong> </td>
              <td colspan="3">	@{{ NoNasabah }}</td>
            </tr>
            <tr style="border-bottom: 2px solid #7c3a3a;">
              <td width="20%"> <strong>Nama Nasabah</strong> </td>
              <td colspan="3">	@{{ NamaNasabah }}</td>
            </tr>
            <tr>
              <td width="20%"> <strong>No Pinjaman</strong> </td>
              <td> @{{ NoPinjaman }}</td>
              <td width="20%"><strong>Tunggakan Pokok</strong></td>
              <td> Rp @{{formatuang tunggakanpokok }}</td>
            </tr>
            <tr>
              <td><strong>Jumlah Pinjaman</strong></td>
              <td> Rp @{{formatuang JumlahPinjaman }}</td>
              <td><strong>Pokok Bulan Ini</strong></td>
              <td> Rp @{{formatuang AngsuranPerBulan }}</td>
            </tr>
            <tr>
              <td><strong>Jumlah Sudah Dibayar</strong></td>
              <td> Rp @{{formatuang dibayar }}</td>
              <td><strong>Bunga Bulan Ini</strong></td>
              <td> Rp @{{formatuang bunga }}</td>
            </tr>
            <tr>
              <td><strong>Sisa Pinjaman</strong></td>
              <td> Rp @{{formatuang SisaPinjaman }}</td>
              <td><strong>Denda</strong></td>
              <td> Rp @{{formatuang denda }}</td>
            </tr>
            <tr style="border-bottom: 2px solid #7c3a3a;">
              <td><strong>Tgl Pembayaran Terakhir</strong></td>
              <td> @{{ bayarakhir }}</td>
              <td><strong>Total Tagihan</strong></td>
              <td> Rp @{{formatuang total }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</script>
<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label class="control-label">Pembayaran Pokok</label>
      <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <input type="text" class="form-control formuang" name="Pokok" required>
      </div>
      <span class="help-block with-errors"></span>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label class="control-label">Pembayaran Bunga</label>
      <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <input type="text" {{ $setting->InputBunga == true ? "" : "readonly" }} class="form-control formuang" name="NominalBunga" required>
      </div>
      <span class="help-block with-errors"></span>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label class="control-label">Pembayaran Denda</label>
      <div class="input-group">
        <span class="input-group-addon">Rp</span>
        <input type="text" {{ $setting->InputDenda == true ? "" : "readonly" }} class="form-control formuang" name="Denda" required>
      </div>
      <span class="help-block with-errors"></span>
    </div>
  </div>
</div>
<div class="row">
  <div class="form-group col-md-6">
    <label class="control-label">Total</label>
    <div class="input-group">
      <span class="input-group-addon">Rp</span>
      <input readonly type="text" class="form-control input-lg formuang" name="Jumlah" required>
    </div>
    <span class="help-block with-errors"></span>
  </div>
  <div class="form-group col-md-6">
    <label class="control-label">Bayar Dengan Rekening Tabungan (Saldo Pada Tabungan)</label>
    <div class="input-group">
      <span class="input-group-addon">
        <input type="checkbox" name="denganTabungan">
      </span>
      <input type="text" readonly name="SaldoTabungan" class="form-control formuang input-lg">
      <input type="hidden" name="NoTabungan">
    </div>
    <span class="help-block with-errors"></span>
  </div>
</div>