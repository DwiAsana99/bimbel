@extends('templatePrint')
@section('title', 'Laba Rugi - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y"))
@section('content-header')
    <h4>Laporan Laba Rugi</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</strong>
    <br>
    <br>    
    <table width="100%">
        <tbody>
          {{$totalp = 0}}
          <tr>
            <td colspan="4"><b>PENDAPATAN</b></td>
          </tr>
          @foreach ($pendapatans as $kk => $vk)
            {{$totalk = 0}}
            <tr>
              <td colspan="4" style="padding-left: 10px;"><b>{{ $vk->Keterangan }}</b></td>
            </tr>
            @foreach ($vk->akuns as $ka => $va)
              @if (count($va->detils) > 1)
                <tr>
                  <td colspan="5" style="padding-left: 20px;">{{ $va->Keterangan }}</td>
                </tr>
              @endif
              @foreach ($va->detils as $kd => $vd)
                <tr>
                  <td style="padding-left: {{ count($va->detils) > 1 ? '30px' : '20px' }};">{{ $vd->Keterangan }}</td>
                  {{$totaldd = 0}}
                  @foreach ($detilpendapatans as $kdd => $vdd)
                    @if ($vd->KodeDetil == $vdd->KodeDetil)
                      {{$totalp += $vdd->total}}
                      {{$totalk += $vdd->total}}
                      {{$totaldd = $vdd->total}}
                    @endif
                  @endforeach
                  <td align="right">{{ number_format($totaldd,0,",",".") }}</td>
                </tr>
              @endforeach
            @endforeach
            <tr>
              <td style="padding-left: 10px;"><b>Total {{ $vk->Keterangan }}</b></td>
              <td align="right" style="padding-right: 20px;"><b><u>{{ number_format($totalk,0,",",".") }}</u></b></td>
            </tr>
          @endforeach
          <tr>
            <td colspan="3"><b>TOTAL PENDAPATAN</b></td>
            <td align="right"><b><u>{{ number_format($totalp,0,",",".") }}</u></b></td>
          </tr>
          <tr>
            <td><br></td>
          </tr>
          {{$totalb = 0}}
          <tr>
            <td colspan="4"><b>BEBAN</b></td>
          </tr>
          @foreach ($bebans as $kk => $vk)
            {{$totalk = 0}}
            <tr>
              <td colspan="4" style="padding-left: 10px;"><b>{{ $vk->Keterangan }}</b></td>
            </tr>
            @foreach ($vk->akuns as $ka => $va)
              @if (count($va->detils) > 1)
                <tr>
                  <td colspan="5" style="padding-left: 20px;">{{ $va->Keterangan }}</td>
                </tr>
              @endif
              @foreach ($va->detils as $kd => $vd)
                <tr>
                  <td colspan="2" style="padding-left: {{ count($va->detils) > 1 ? '30px' : '20px' }};">{{ $vd->Keterangan }}</td>
                  {{$totaldd = 0}}
                  @foreach ($detilbebans as $kdd => $vdd)
                    @if ($vd->KodeDetil == $vdd->KodeDetil)
                      {{$totalb += $vdd->total}}
                      {{$totalk += $vdd->total}}
                      {{$totaldd = $vdd->total}}
                    @endif
                  @endforeach
                  <td align="right">{{ number_format($totaldd,0,",",".") }}</td>
                </tr>
              @endforeach
            @endforeach
            <tr>
              <td style="padding-left: 10px;"><b>Total {{ $vk->Keterangan }}</b></td>
              <td align="right" style="padding-right: 20px;"><b><u>{{ number_format($totalk,2,",",".") }}</u></b></td>
            </tr>
          @endforeach
          <tr>
            <td colspan="3"><b>TOTAL BEBAN</b></td>
            <td align="right"><b><u>{{ number_format($totalb,0,",",".") }}</u></b></td>
          </tr>
        </tbody>
        <tr>
          <td><br></td>
        </tr>
        <tfoot>
          <tr>
            <td colspan="3"><b>SISA HASIL USAHA</b></td>
            <td align="right"><b><u>{{ number_format($totalp - $totalb,0,",",".") }}</u></b></td>
          </tr>
        </tfoot>
    </table>
@stop