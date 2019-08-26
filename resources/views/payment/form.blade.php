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

    <title>Konfirmasi Pembayaran {!! $booking->kode_booking !!}</title>

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
                        <a href="{{ route('invoice', $booking->kode_booking) }}" id="pay-button" class="btn btn-success float-right"><i class="fa fa-file"></i> Invoice
                        </a>
                    </div>

                </div>
            </div>
        </nav>


        <div class="container">
            <div class="row">
                <div class="col-12" style="margin-top: 15px">
                    <div class="invoice p-3 mb-3">
                        <div class="col-md-12">
                            <h3>Konfirmasi Pembayaran</h3>
                        </div>
                        <hr>
                        @if(count($errors)>0)
                            <div class="alert alert-danger alert-dismissable" role="alert">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"> × </button>
                                <strong>Errors:</strong>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                </ul>
                            </div>
                            @endif
                            @if (Session::has('flash_message'))
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"> × </button>
                                {!! session('flash_message') !!}
                            </div>
                            @endif
                            <form class="" action="{{route('payment.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Kode Booking</label>
                                            <input type="text" class="form-control text-uppercase" readonly name="kode_booking" value="{{$booking->kode_booking}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tanggal Transfer</label>
                                            <input type="date" name="tgl_transfer" class="form-control" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Bukti Transfer</label>
                                            <input type="file" name="image" class="form-control" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="id_pelanggan" value="{{$pelanggan->id}}">
                                            <label for="">Nama Pemilik Rekening</label>
                                            <input type="text" name="nama_pemilik_rekening" value="" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Nomor Rekening</label>
                                            <input type="text" name="no_rekening" value="" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Metode Pembayaran</label>
                                            <select class="form-control" name="id_metode">
                                                <option value="">-- Metode Pembayaran --</option>
                                                @foreach ($metode as $mtd)
                                                <option value="{{$mtd->id}}">{{$mtd->bank}} - {{$mtd->no_rekening}} AN {{$mtd->atas_nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Jumlah Tagihan</label>
                                            <input type="text" name="jml_tagihan" value="{{$booking->total}}" readonly class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nominal Pembayaran</label>
                                            <input type="text" name="jumlah" value="" class="form-control" required>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="submit" name="button" class="btn btn-success">Kirim</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="lead">Petunjuk Pembayaran</p>
                                        <li>Silakan pilih metode pembayaran yang diinginkan</li>
                                        <li>Transfer pembayaran sesuai nominal tagihan</li>
                                        <li>Simpan dan upload bukti transfer</li>
                                        <li>Isi form di samping sesuai data pembayaran anda</li>
                                        <br>
                                        <p>Kami akan mengirimkan email pemberitahuan apabila pembayaran sudah kami terima.</p>
                                        <p>Apabila dalam waktu 1x24 jam anda belum menerima email pemberitahuan, silakan menghubungi kami melalui telepon maupun email.</p>
                                    </div>
                                </div>


                            </form>

                    </div>
                </div>
            </div>
        </div>

    </div>


</body>

</html>
