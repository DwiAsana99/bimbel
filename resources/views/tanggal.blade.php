<div class="modal fade" id="modal-tanggal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">{{session('aturtgl') == 1 ? 'Ganti' : 'Tutup' }} Hari : {{Fungsi::bulanID(session('tgl'))}}</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="control-label">Pilih Tanggal Selanjutnya</label>
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" autocomplete="off" class="form-control" id="tanggal-buku" value="{{session('tgl')}}">
            <span class="input-group-btn">
              <button type="button" id="btntanggal" class="btn btn-info btn-flat">Proses</button>
            </span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="{{ route('lap.keu.hari', ['tgl' => session('tgl')]) }}" type="button" id="btnreporthari" class="btn btn-success btn-flat form-control">Laporan Neraca : {{Fungsi::bulanID(session('tgl'))}}</a>
      </div>
    </div>
  </div>
</div>
