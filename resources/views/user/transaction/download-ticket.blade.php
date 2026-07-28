<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>E-Ticket ITIHASA</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body{
            margin:0;
            padding:0;
            background:#f5f5f5;
            font-family:Arial, Helvetica, sans-serif;
        }

        .ticket{
            width:700px;
            margin:40px auto;
            background:#FDFBF8;
            border:2px solid #EADBC8;
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 15px 35px rgba(0,0,0,.15);
        }

        .header{
            background:#4E342E;
            color:white;
            text-align:center;
            padding:28px;
        }

        .header h1{
            font-size:38px;
            margin:0;
            letter-spacing:4px;
        }

        .header p{
            margin-top:8px;
            color:#EADBC8;
            font-size:18px;
        }

        .content{
            padding:40px;
        }

        .qr{
            text-align:center;
            margin-bottom:35px;
        }

        .qr-box{
            display:inline-block;
            background:white;
            border:4px solid #EADBC8;
            border-radius:20px;
            padding:20px;
        }

        .museum-name{
            margin-top:20px;
            display:inline-block;
            background:#FFF7ED;
            color:#C08A3E;
            padding:10px 25px;
            border-radius:50px;
            font-weight:bold;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:25px;
        }

        table td{
            padding:15px 0;
            border-bottom:1px solid #ececec;
            font-size:18px;
        }

        table td:first-child{
            color:#666;
            width:40%;
        }

        table td:last-child{
            text-align:right;
            font-weight:bold;
            color:#4E342E;
        }

        .footer{
            text-align:center;
            color:#666;
            font-size:15px;
            margin-top:20px;
            line-height:1.8;
        }

    </style>

</head>

<body>

<div class="ticket">

    <div class="header">

        <h1>ITIHASA</h1>

        <p>HERITAGE MUSEUM DIGITAL TICKET</p>

    </div>

    <div class="content">

        <div class="qr">

            <div class="qr-box">

                {!! $qrCode !!}

            </div>

            <br>

            <div class="museum-name">

                {{ $transaction->booking->museum->name }}

            </div>

        </div>

        <table>

            <tr>
                <td>Nama Pengunjung</td>
                <td>{{ $transaction->booking->user->name }}</td>
            </tr>

            <tr>
                <td>Tanggal Kunjungan</td>
                <td>{{ \Carbon\Carbon::parse($transaction->booking->visit_date)->format('d F Y') }}</td>
            </tr>

            <tr>
                <td>Invoice</td>
                <td>{{ $transaction->invoice_code }}</td>
            </tr>

            <tr>
                <td>Total Pembayaran</td>
                <td style="color:#C08A3E;">
                    Rp {{ number_format($transaction->total_amount,0,',','.') }}
                </td>
            </tr>

        </table>

        <div class="footer">

            Tunjukkan QR Code ini kepada petugas museum
            sebelum memasuki area museum.

        </div>

    </div>

</div>

</body>

</html>
