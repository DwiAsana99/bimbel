<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <style>
        .content-header{
            text-align: center;
            text-transform: uppercase;
            font-size: 14pt;
        }
        .text-top{
            vertical-align: top;
        }
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
        .text-left{
            text-align: left;
        }
        table {
            border-collapse: collapse;
        }
        table, td, th {
            font-family: sans-serif;
        }
        .garis table, 
        .garis th, 
        .garis td {
            font-size: 12pt;
            /* border: 1px solid black; */
            padding: 5px;
        }
        .footer{
            position: absolute;
            right: 0.5cm;
        }
        .camel{
            text-transform: capitalize;
        }
    </style>
    @yield('css')
</head>
<body>
    <div class="header">
        <table style="vertical-align: bottom;" width="100%">
            <tbody>
                <tr>
                    <td rowspan="4" width="17%" class="text-left text-top">
                        <img src="{{ asset('storage/koperasi/logo/'.Auth::user()->koperasi->Logo) ?: asset('images/icon.png') }}" style="width: 95px; height: 95px;">
                    </td>
                    <td width="66%" class="content-header"><strong>{{ Auth::user()->koperasi->Nama }}</strong></td>
                    <td rowspan="4" width="17%" class="text-right text-top">
                        <barcode code="{{ $kwitansi->NoKwitansi }}" size="1" type="QR" error="M" class="barcode" disableborder="true"/>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 10pt;" width="66%" class="text-center">{{ Auth::user()->koperasi->Alamat }}</td>
                </tr>
                <tr>
                    <td style="font-size: 10pt;" width="66%" class="text-center">{{ Auth::user()->koperasi->NoTelp }}</td>
                </tr>
                <tr>
                    <td style="font-size: 10pt;" width="66%" class="text-center">{{ Auth::user()->koperasi->NoKoperasi }}</td>
                </tr>
            </tbody>
        </table>
        <hr style="color:#000000">
    </div>
    <div class="body">
        <div class="content-header">
            @yield('content-header')
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
    <br>
    <div class="footer">
        <table width="30%">
            <tr><td class="text-left">Denpasar, {{ Fungsi::bulanID($kwitansi->TglInput) }}</td></tr>
            <tr><td class="text-left">Yang @yield('yang') :</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>_____________________</td></tr>
        </table>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>