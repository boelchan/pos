<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>cetak</title>
    <style>
        #tabel {
            border-collapse: collapse;
        }

        #tabel td {
            padding-left: 5px;
            border: 1px solid black;
        }

        .center {
            text-align: center;
            margin: auto;
            width: 50%;
            /* border: 3px solid rgb(0, 0, 0); */
            padding: 10px;
        }
    </style>
</head>

<body style='font-family: arial;'>
    <table width="100%" class="table table-borderless">
        <tr>
            <td colspan="3" style="align:center" class="center">
                <span style="font-size: 40px"><b>SUMENEP DIGITAL PRINTING</b></span> <br>
                <span style="font-size: 25px"><b>PERCETAKAN - DIGITAL PRINT - KONVEKSI</b></span> <br>
                <span style="font-size: 20px">Jl. Sultan Abdurrahman Gg. IV Blok DC No. 04 BSA - Sumenep</span> <br>
                <span style="font-size: 20px">Email : sumenepdigitanprint@gmail.com - CS 087761477723</span>
            </td>
        </tr>
        <tr>
            <td width="15%" class="font-weight-bold">Invoice</td>
            <td width="2%" class="font-weight-bold">:</td>
            <td width="80%" class="font-weight-bold"></td>
            {{-- <td width="80%" class="font-weight-bold">{{$trx->invoices_number}}</td> --}}
        </tr>
        <tr>
            <td width="15%">Customer</td>
            <td width="2%">:</td>
            <td width="80%">{{$trx->customer->nama}}</td>
        </tr>
        <tr>
            <td width="15%">Tgl Pesanan</td>
            <td width="2%">:</td>
            <td width="80%">{{$trx->tanggal}}</td>
        </tr>
    </table>
    <table class="table" width="100%" style="border:solid 1px;border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid">Uraian</th>
                <th style="border: 1px solid">Jumlah</th>
                <th style="border: 1px solid">Harga</th>
                <th style="border: 1px solid">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trx->productTranscation as $index=>$item)
            <tr>
                <td style="border: 1px solid">{{$item->product->name}}</td>
                <td style="text-align: right;padding-right: 10px;border: 1px solid">{{ number_format($item->qty,0,',','.') }}</td>
                <td style="text-align: right;padding-right: 10px;border: 1px solid">{{ number_format($item->price,0,',','.') }}</td>
                <td style="text-align: right;padding-right: 10px;border: 1px solid">{{ number_format($item->sub_total,0,',','.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3" style="text-align: center;border: 1px solid"><i>SUB TOTAL</i></td>
                <td style="text-align: right;padding-right: 10px;border: 1px solid"><b>{{ number_format($trx->sub_total,0,',','.') }}</b></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center;border: 1px solid"><i>DISKON</i></td>
                <td style="text-align: right;padding-right: 10px;border: 1px solid"><b>{{ number_format($trx->discount,0,',','.') }}</b></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center;border: 1px solid"><i>TOTAL PEMBAYARAN</i></td>
                <td style="text-align: right;padding-right: 10px;border: 1px solid"><b>{{ number_format($trx->total,0,',','.') }}</b></td>
            </tr>
        </tbody>
    </table>
    <table class="table" width="100%" style="border-collapse: collapse;">
            <tr>
                <td>Sistem Pembayaran : TUNAI / TRANSFER</td>
            </tr>
            <tr>
                <td> Catatan </td>
            </tr>
            <tr>
                <td>
                    - Barang yang sudah dipesan tidak dapat dibatalkan kecuali ada perjanjian<br>
                    - Harga barang diatas sudah termasuk pajak (PPN & PPh)
                </td>
            </tr>
            <tr> <td>&nbsp;</td> </tr>
            <tr>
                <td style="text-align: center">=== Terima Kasih Atas Kepercayaan Anda === </td>
            </tr>
    </table>
</body>

</html>