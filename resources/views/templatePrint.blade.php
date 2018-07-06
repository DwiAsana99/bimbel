<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <style>
        @page {
            footer: html_footerLaporan;
        }
        @page :first {
            header: html_headerLaporan;
            margin-top: 42mm;
        }
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
        .garis table, 
        .garis th, 
        .garis td {
            font-size: 10pt;
            border: 1px solid black;
            padding: 5px;
        }
    </style>
    @yield('css')
</head>
<body>
    <htmlpageheader name="headerLaporan">
        <table style="vertical-align: bottom;" width="100%">
            <tbody>
                <tr>
                    <td rowspan="4" width="17%" class="text-left text-top">
                        <img src="{{ asset('storage/koperasi/logo/'.Auth::user()->koperasi->Logo) ?: asset('images/icon.png') }}" style="width: 95px; height: 95px;">
                    </td>
                    <td width="66%" class="content-header"><strong>{{ Auth::user()->koperasi->Nama }}</strong></td>
                    <td rowspan="4" width="17%" class="text-right text-top">
                        <barcode code="{{ Auth::user()->koperasi->Nama .' / '. Auth::user()->name .' / '. date('d-m-Y H:i:s') }}" size="1" type="QR" error="M" class="barcode" disableborder="true"/>
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
    </htmlpageheader>
    <div class="body">
        <div class="content-header">
            @yield('content-header')
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
    <htmlpagefooter name="footerLaporan">
        <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr>
                <td width="33%"> <img style="width: 70px; " src="{{ asset('images/PandanaLogo.png') }}"/> </td>
                <td width="33%" class="text-right" style="font-weight: bold; ">{PAGENO}/{nbpg}</td>
            </tr>
        </table>
    </htmlpagefooter>
</body>
</html>