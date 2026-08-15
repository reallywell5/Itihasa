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

        .manifest-box{
            margin-top:25px;
            padding:20px;
            background:#FFF7ED;
            border:1px solid #EADBC8;
            border-radius:16px;
        }

        .manifest-title{
            font-size:15px;
            font-weight:bold;
            color:#4E342E;
            margin-bottom:12px;
            text-transform:uppercase;
            letter-spacing:1px;
        }

        .manifest-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:6px 24px;
        }

        .manifest-item{
            font-size:14px;
            color:#444;
        }

        .manifest-item b{
            color:#C08A3E;
            margin-right:6px;
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

            @if ($transaction->booking->is_rombongan)
                <tr>
                    <td>Tipe Booking</td>
                    <td>Rombongan ({{ $transaction->booking->jumlah_anggota }} orang)</td>
                </tr>
            @endif

            <tr>
                <td style="vertical-align: top;">Rincian Tiket</td>
                <td style="text-align: right; line-height: 1.6;">
                    @foreach($transaction->booking->ticket_items as $item)
                        <div>
                            <span>{{ $item['ticket_name'] }} x {{ $item['qty'] }}</span>
                            @if($item['price'] > 0)
                                <span style="color:#888; font-weight:normal; font-size:14px;"> (@ Rp {{ number_format($item['price'], 0, ',', '.') }})</span>
                            @endif
                            @if($item['subtotal'] > 0)
                                <span style="color:#4E342E;"> = Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            @endif
                        </div>
                    @endforeach
                </td>
            </tr>

            <tr>
                <td>Total Pembayaran</td>
                <td style="color:#C08A3E; font-size: 20px;">
                    Rp {{ number_format($transaction->total_amount,0,',','.') }}
                </td>
            </tr>

        </table>

        @if ($transaction->booking->is_rombongan && !empty($transaction->booking->manifest))
            <div class="manifest-box">
                <div class="manifest-title">
                    Manifes Rombongan ({{ count($transaction->booking->manifest) }} orang)
                </div>
                <div class="manifest-grid">
                    @foreach ($transaction->booking->manifest as $index => $nama)
                        <div class="manifest-item">
                            <b>{{ $index + 1 }}.</b>{{ $nama }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="footer">

            Tunjukkan QR Code ini kepada petugas museum
            sebelum memasuki area museum.

        </div>

    </div>

</div>

</body>

</html>
