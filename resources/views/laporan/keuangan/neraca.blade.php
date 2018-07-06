@extends('templatePrint')
@section('title', 'Neraca - '.date_format(date_create($tgl),"d-m-Y"))
@section('css')
<style>
    .border {
      border: 1px solid black;
    }
</style>
@stop
@section('content-header')
    <h4>Laporan Neraca</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tgl),"d-m-Y") }}</strong>
    <table width="100%">
        <thead>
          <tr>
            <th width="50%" class="border" colspan="2">AKTIVA</th>
            <th width="50%" class="border" colspan="2">PASIVA</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border" colspan="2" style="vertical-align: top;">
              <table width="100%">
                <tbody>
                  {{$totala = 0}}
                  @foreach ($aktivas as $kak => $vak)
                    {{$totalak = 0}}
                    <tr>
                      <td colspan="3"><b>{{ $vak->Keterangan }}</b></td>
                    </tr>
                    @foreach ($vak->akuns as $kaa => $vaa)
                      @if (count($vaa->detils) > 1)
                        <tr>
                          <td colspan="3" style="padding-left: 10px;">{{ $vaa->Keterangan }}</td>
                        </tr>
                      @endif
                      @foreach ($vaa->detils as $kad => $vad)
                        <tr>
                          <td style="padding-left: {{ count($vaa->detils) > 1 ? '20px' : '10px' }};">{{ $vad->Keterangan }}</td>
                          {{$totaldd = 0}}
                          @foreach ($detilaktivas as $kadd => $vadd)
                            @if ($vad->KodeDetil == $vadd->KodeDetil)
                              {{$totala += $vadd->total}}
                              {{$totalak += $vadd->total}}
                              {{$totaldd = $vadd->total}}
                            @endif
                          @endforeach
                          <td align="right">{{ number_format($totaldd,0,",",".") }}</td>
                        </tr>
                      @endforeach
                    @endforeach
                    <tr>
                      <td colspan="2"><b>Total {{ $vak->Keterangan }}</b></td>
                      <td align="right"><b><u>{{ number_format($totalak,0,",",".") }}</u></b></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </td>
            <td class="border" colspan="2" style="vertical-align: top;">
              <table width="100%">
                <tbody>
                  {{$totalp = 0}}
                  <tr>
                    <td colspan="3"><b>KEWAJIBAN</b></td>
                  </tr>
                  {{$totalk = 0}}
                  @foreach ($kewajibans as $kkk => $vkk)
                    {{$totalkk = 0}}
                    <tr>
                      <td colspan="3" style="padding-left: 10px;"><b>{{ $vkk->Keterangan }}</b></td>
                    </tr>
                    @foreach ($vkk->akuns as $kka => $vka)
                      @if (count($vka->detils) > 1)
                        <tr>
                          <td colspan="3" style="padding-left: 20px;">{{ $vka->Keterangan }}</td>
                        </tr>
                      @endif
                      @foreach ($vka->detils as $kkd => $vkd)
                        <tr>
                          <td style="padding-left: {{ count($vka->detils) > 1 ? '30px' : '20px' }};">{{ $vkd->Keterangan }}</td>
                          {{$totaldd = 0}}
                          @foreach ($detilkewajibans as $kkdd => $vkdd)
                            @if ($vkd->KodeDetil == $vkdd->KodeDetil)
                              {{$totalp += $vkdd->total}}
                              {{$totalk += $vkdd->total}}
                              {{$totalkk += $vkdd->total}}
                              {{$totaldd = $vkdd->total}}
                            @endif
                          @endforeach
                          <td align="right">{{ number_format($totaldd,0,",",".") }}</td>
                        </tr>
                      @endforeach
                    @endforeach
                    <tr>
                      <td style="padding-left: 10px;"><b>Total {{ $vkk->Keterangan }}</b></td>
                      <td align="right" style="padding-right: 20px;"><b>{{ number_format($totalkk,0,",",".") }}</b></td>
                    </tr>
                  @endforeach
                  <tr>
                    <td colspan="2"><b>TOTAL KEWAJIBAN</b></td>
                    <td align="right"><b><u>{{ number_format($totalk,0,",",".") }}</u></b></td>
                  </tr>
                  <tr>
                    <td><br></td>
                  </tr>
                  <tr>
                    <td colspan="3"><b>MODAL</b></td>
                  </tr>
                  {{$totalm = 0}}
                  @foreach ($modals as $kmk => $vmk)
                    {{$totalmk = 0}}
                    <tr>
                      <td colspan="3" style="padding-left: 10px;"><b>{{ $vmk->Keterangan }}</b></td>
                    </tr>
                    @foreach ($vmk->akuns as $kma => $vma)
                      @if (count($vma->detils) > 1)
                        <tr>
                          <td colspan="3" style="padding-left: 20px;">{{ $vma->Keterangan }}</td>
                        </tr>
                      @endif
                      @foreach ($vma->detils as $kmd => $vmd)
                        <tr>
                          <td style="padding-left: {{ count($vma->detils) > 1 ? '30px' : '20px' }};">{{ $vmd->Keterangan }}</td>
                          {{$totaldd = 0}}
                          @foreach ($detilmodals as $kmdd => $vmdd)
                            @if ($vmd->KodeDetil == $vmdd->KodeDetil)
                              {{$totalp += $vmdd->total}}
                              {{$totalm += $vmdd->total}}
                              {{$totalmk += $vmdd->total}}
                              {{$totaldd = $vmdd->total}}
                            @endif
                          @endforeach
                          <td align="right">{{ number_format($totaldd,0,",",".") }}</td>
                        </tr>
                      @endforeach
                    @endforeach
                    <tr>
                      <td style="padding-left: 10px;"><b>Total {{ $vmk->Keterangan }}</b></td>
                      <td align="right" style="padding-right: 20px;"><b>{{ number_format($totalmk,0,",",".") }}</b></td>
                    </tr>
                  @endforeach
                  <tr>
                    <td colspan="2"><b>TOTAL MODAL</b></td>
                    <td align="right"><b><u>{{ number_format($totalm,0,",",".") }}</u></b></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td class="border"><b>TOTAL AKTIVA</b></td>
            <td class="border" align="right"><b>{{ number_format($totala,0,",",".") }}</b></td>
            <td class="border"><b>TOTAL PASIVA</b></td>
            <td class="border" align="right"><b>{{ number_format($totalp,0,",",".") }}</b></td>
          </tr>
        </tfoot>
    </table>
@stop