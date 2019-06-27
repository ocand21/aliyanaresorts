<?php
  Date::setLocale('id');
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Invoice {!! $booking->kode_booking !!}</title>

    <!-- Scripts -->
    <style media="screen">
        @media print {
          .no-print {
            display: none !important;
          }
          .main-sidebar,
          .left-side,
          .main-header,
          .content-header {
            display: none !important;
          }
          .content-wrapper,
          .right-side,
          .main-footer {
            margin-left: 0 !important;
            min-height: 0 !important;
            -webkit-transform: translate(0, 0) !important;
            -ms-transform: translate(0, 0) !important;
            -o-transform: translate(0, 0) !important;
            transform: translate(0, 0) !important;
          }
          .fixed .content-wrapper,
          .fixed .right-side {
            padding-top: 0 !important;
          }
          .invoice {
            width: 100%;
            border: 0;
            margin: 0;
            padding: 0;
          }
          .invoice-col {
            float: left;
            width: 33.3333333%;
          }
          .table-responsive {
            overflow: auto;
          }
          .table-responsive > .table tr th,
          .table-responsive > .table tr td {
            white-space: normal !important;
          }
        }
            .invoice {
          position: relative;
          background: #fff;
          border: 1px solid #f4f4f4;
          padding: 20px;
          margin: 10px 25px;
        }
        .invoice-title {
          margin-top: 0;
        }
        .invoice {
            width: 100%;
            border: 0;
            margin: 0;
            padding: 0;
          }
          .invoice-col {
            float: left;
            width: 33.3333333%;
          }
    </style>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                {{-- <img src="/img/icon/logo.png" alt="" width="100" height="60"> --}}
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>
                    <div class="col-12" style="margin-left: 5px">
                        <a href="" @click.prevent="printme" target="_blank" class="btn btn-light"><i class="fa fa-print"></i> Print</a>
                        <form id="payment-form" method="post" action="snapfinish">
                          <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                          <input type="hidden" name="result_type" id="result-type" value=""></div>
                          <input type="hidden" name="result_data" id="result-data" value=""></div>
                        </form>
                        <a id="pay-button" class="btn btn-success float-right"><i class="fa fa-credit-card"></i> Proses Pembayaran
                        </a>
                    </div>

                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-12" style="margin-top: 15px">

                    {{-- --}}

                    <div class="invoice p-3 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <img src="/img/icon/logo.png" alt="" width="100" height="60">
                                    <small class="float-right">Tanggal: {{ Date::parse($booking->created_at)->format('j/m/Y') }}</small>
                                </h4>
                            </div>
                        </div>

                        {{-- --}}
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                Dari

                                <address>
                                    <strong>{{$konfig->hotel_name}}</strong><br>
                                    {{$konfig->alamat}}<br>
                                    Telepon: {{$konfig->phone}} <br>
                                    Email: {{$konfig->mail1}} <br>
                                </address>
                            </div>

                            <div class="col-sm-4 invoice-col">
                                Untuk
                                <address>
                                    <strong>{{$booking->nama}}</strong><br>
                                    {{$booking->alamat}}<br>
                                    Telepon: {{$booking->no_telepon}}<br>
                                    Email: {{$booking->email}}<br>
                                </address>
                            </div>

                            <div class="col-sm-4 invoice-col">
                                <strong class="text-uppercase">Invoice #{{$booking->kode_booking}}</strong>
                                <br>
                                <p></p>
                                <strong>Tanggal Checkin: </strong>{{ Date::parse($booking->tgl_checkin)->format('l, j F Y') }}<br>
                                <strong>Tanggal Checkout: </strong>{{ Date::parse($booking->tgl_checkout)->format('l, j F Y') }}<br>
                                <strong>Jumlah Kamar: </strong>{{$booking->jmlKamar}}<br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No Kamar</th>
                                            <th>Tipe Kamar</th>
                                            <th>Kapasitas</th>
                                            <th>Jumlah Tamu</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rooms as $room)
                                        <tr>
                                            <td>{{$room->no_room}}</td>
                                            <td>{{$room->tipe}}</td>
                                            <td>{{$room->kapasitas}} Orang</td>
                                            <td>{{$room->jml_tamu}} Orang</td>
                                            <td>Rp. {{ format_uang($room->harga)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="row invoice-info">
                            <div class="col-6">
                                <p class="lead">Metode Pembayaran</p>
                                @foreach ($metode as $mtd)
                                  <p><strong>{{$mtd->bank}} - {{$mtd->no_rekening}} AN {{$mtd->atas_nama}}</strong></p>
                                @endforeach
                            </div>

                            <div class="col-md-6">
                                <p class="lead">Tanggal Jatuh Tempo {{ Date::parse($booking->tgl_checkout)->format('l, j F Y') }}</p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><strong>Subtotal</strong></td>
                                                <td>Rp. {{format_uang($subtotal->sub_total)}}</td>
                                            </tr>
                                            <tr>
                                                <td> <strong>Durasi Menginap</strong> </td>
                                                <td>{{$durasi}} Hari</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total</strong></td>
                                                <td>Rp. {{format_uang($tagihan->total_tagihan)}}</td>
                                            </tr>
                                            <tr>
                                              <td><strong>Terbayarkan</strong></td>
                                              <td>Rp. {{format_uang($tagihan->terbayarkan)}}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="row no-print">

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script src="{{ !config('services.midtrans.isProduction') ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script type="text/javascript">

    $('#pay-button').click(function (event) {
      event.preventDefault();
      $(this).attr("disabled", "disabled");

    $.ajax({

      url: './snaptoken',
      cache: false,
      success: function(data) {
        //location = data;
        console.log('token = '+data);

        var resultType = document.getElementById('result-type');
        var resultData = document.getElementById('result-data');
        function changeResult(type,data){
          $("#result-type").val(type);
          $("#result-data").val(JSON.stringify(data));
          //resultType.innerHTML = type;
          //resultData.innerHTML = JSON.stringify(data);
        }
        snap.pay(data, {

          onSuccess: function(result){
            changeResult('success', result);
            console.log(result.status_message);
            console.log(result);
            $("#payment-form").submit();
          },
          onPending: function(result){
            changeResult('pending', result);
            console.log(result.status_message);
            $("#payment-form").submit();
          },
          onError: function(result){
            changeResult('error', result);
            console.log(result.status_message);
            $("#payment-form").submit();
          }
        });
      }
    });
  });
  </script>

</body>

</html>
