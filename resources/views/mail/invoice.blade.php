<?php
  Date::setLocale('id');
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="box-sizing:border-box;font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%;-webkit-tap-highlight-color:rgba(0, 0, 0, 0);">
<head style="box-sizing: border-box;">
<meta charset="utf-8" style="box-sizing: border-box;">
<meta name="viewport" content="width=device-width, initial-scale=1" style="box-sizing: border-box;">
<!-- CSRF Token --><meta name="csrf-token" content="{{ csrf_token() }}" style="box-sizing: border-box;">
<title style="box-sizing: border-box;">Invoice</title>
<!-- Scripts -->
</head>
<body style='box-sizing:border-box;margin:0;font-family:-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff;'>
<p style="box-sizing:border-box;margin-top:0;margin-bottom:1rem;">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" style="box-sizing: border-box;"> --}}
    <link rel="stylesheet" href="/css/app.css" style="box-sizing: border-box;"><!-- Fonts --><link rel="dns-prefetch" href="//fonts.gstatic.com" style="box-sizing: border-box;"><link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" style="box-sizing: border-box;"></p>
<div class="container" style="box-sizing:border-box;width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto;max-width:1140px;">
<img class="mx-auto mt-4 mb-3" width="100" height="60" src="http://sistem.aliyanaresorts.com/img/icon/logo.png" style="box-sizing:border-box;vertical-align:middle;border-style:none;margin-bottom:1rem !important;margin-top:1.5rem !important;margin-right:auto !important;margin-left:auto !important;"><div class="card mb-4" style="border-top:5px solid #958f44;box-sizing:border-box;position:relative;display:flex;-ms-flex-direction:column;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:1px solid rgba(0, 0, 0, 0.125);border-radius:0.25rem;margin-bottom:1.5rem !important;">
 <div class="card-body table-responsive" style="box-sizing:border-box;display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;-ms-flex:1 1 auto;flex:1 1 auto;padding:1.25rem;">
   <p class="text-justify" style="box-sizing:border-box;margin-top:0;margin-bottom:1rem;text-align:justify !important;">Dear {{$booking->nama}},</p>
   <p class="text-muted text-justify" style="box-sizing:border-box;margin-top:0;margin-bottom:1rem;text-align:justify !important;color:#6c757d !important;">Pesanan anda telah kami terima. Terima kasih telah mempercayai kami sebagai tempat penginapan anda. Silakan ikuti petunjuk dibawah untuk melanjutkan ke tahap pembayaran pesanan anda.
   Terima kasih</p>

   <hr style="box-sizing:content-box;height:0;overflow:visible;margin-top:1rem;margin-bottom:1rem;border:0;border-top:1px solid rgba(0, 0, 0, 0.1);">
<h5 class="text-center" style="box-sizing:border-box;margin-top:0;margin-bottom:0.5rem;font-weight:500;line-height:1.2;font-size:1.25rem;text-align:center !important;"><strong style="box-sizing:border-box;font-weight:bolder;">Detil Booking</strong></h5>
   <div class="col-sm-12 invoice-col" style="box-sizing:border-box;float:left;width:100%;position:relative;padding-right:15px;padding-left:15px;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%;">
     <strong style="box-sizing:border-box;font-weight:bolder;">Invoice #{{$booking->kode_booking}}</strong>
     <br style="box-sizing: border-box;"><p style="box-sizing:border-box;margin-top:0;margin-bottom:1rem;"></p>
     <strong style="box-sizing:border-box;font-weight:bolder;">Tanggal Checkin: </strong>{{ Date::parse($booking->tgl_checkin)->format('l, j F Y') }}<br style="box-sizing: border-box;"><strong style="box-sizing:border-box;font-weight:bolder;">Tanggal Checkout: </strong>{{ Date::parse($booking->tgl_checkout)->format('l, j F Y') }}<br style="box-sizing: border-box;"><strong style="box-sizing:border-box;font-weight:bolder;">Jumlah Kamar: </strong>{{$booking->jmlKamar}}<br style="box-sizing: border-box;">
</div>

   <table class="table table-striped" style="margin-top:10px;box-sizing:border-box;border-collapse:collapse;width:100%;margin-bottom:1rem;color:#212529;">
<thead style="box-sizing: border-box;"><tr style="box-sizing: border-box;">
<th style="box-sizing:border-box;text-align:inherit;padding:0.75rem;vertical-align:bottom;border-top:1px solid #dee2e6;border-bottom:2px solid #dee2e6;">No Kamar</th>
               <th style="box-sizing:border-box;text-align:inherit;padding:0.75rem;vertical-align:bottom;border-top:1px solid #dee2e6;border-bottom:2px solid #dee2e6;">Tipe Kamar</th>
               <th style="box-sizing:border-box;text-align:inherit;padding:0.75rem;vertical-align:bottom;border-top:1px solid #dee2e6;border-bottom:2px solid #dee2e6;">Kapasitas</th>
               <th style="box-sizing:border-box;text-align:inherit;padding:0.75rem;vertical-align:bottom;border-top:1px solid #dee2e6;border-bottom:2px solid #dee2e6;">Jumlah Tamu</th>
               <th style="box-sizing:border-box;text-align:inherit;padding:0.75rem;vertical-align:bottom;border-top:1px solid #dee2e6;border-bottom:2px solid #dee2e6;">Harga</th>
           </tr></thead>
<tbody style="box-sizing: border-box;">
         @foreach($rooms as $room)
         <tr style="box-sizing:border-box;background-color:rgba(0, 0, 0, 0.05);">
<td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;">{{$room->no_room}}</td>
             <td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;">{{$room->tipe}}</td>
             <td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;">{{$room->kapasitas}} Orang</td>
             <td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;">{{$room->jml_tamu}} Orang</td>
             <td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;">Rp. {{ format_uang($room->harga)}}</td>
         </tr>
         @endforeach
       </tbody>
</table>
<br style="box-sizing: border-box;"><div class="row invoice-info" style="box-sizing:border-box;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px;">
       <div class="col-6" style="box-sizing:border-box;position:relative;width:100%;padding-right:15px;padding-left:15px;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%;">
           <p class="lead" style="box-sizing:border-box;margin-top:0;margin-bottom:1rem;font-size:1.25rem;font-weight:300;">Metode Pembayaran</p>

       </div>

       <div class="col-md-6" style="box-sizing:border-box;position:relative;width:100%;padding-right:15px;padding-left:15px;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%;">
           <p class="lead" style="box-sizing:border-box;margin-top:0;margin-bottom:1rem;font-size:1.25rem;font-weight:300;">Tanggal Jatuh Tempo {{ Date::parse($booking->tgl_checkout)->format('l, j F Y') }}</p>
           <div class="table-responsive" style="box-sizing:border-box;display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;">
               <table class="table" style="box-sizing:border-box;border-collapse:collapse;width:100%;margin-bottom:1rem;color:#212529;"><tbody style="box-sizing: border-box;">
<tr style="box-sizing: border-box;">
<td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;"><strong style="box-sizing:border-box;font-weight:bolder;">Subtotal</strong></td>
                         <td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;">Rp. {{format_uang($subtotal->sub_total)}}</td>
                     </tr>
<tr style="box-sizing: border-box;">
<td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;"> <strong style="box-sizing:border-box;font-weight:bolder;">Durasi Menginap</strong> </td>
                         <td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;">{{$durasi}} Hari</td>
                     </tr>
<tr style="box-sizing: border-box;">
<td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;"><strong style="box-sizing:border-box;font-weight:bolder;">Total</strong></td>
                         <td style="box-sizing:border-box;padding:0.75rem;vertical-align:top;border-top:1px solid #dee2e6;">Rp. {{format_uang($booking->total)}}</td>
                     </tr>
</tbody></table>
<a href="{{ url('/booking/invoice/' . $booking->kode_booking)}}" class="btn btn-xl btn-success btn-block" style="box-sizing:border-box;color:#fff;text-decoration:none;background-color:#28a745;display:block;font-weight:400;text-align:center;vertical-align:middle;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;border:1px solid transparent;padding:0.375rem 0.75rem;font-size:1rem;line-height:1.5;border-radius:0.25rem;transition:none;border-color:#28a745;width:100%;">Klik Di sini</a>
           </div>

       </div>
   </div>
 </div>
</div>



<div class="text-center text-muted" style="box-sizing:border-box;text-align:center !important;color:#6c757d !important;">{{$konfig->hotel_name}}</div>
<div class="text-center text-muted" style="box-sizing:border-box;text-align:center !important;color:#6c757d !important;">  {{$konfig->alamat}}</div>
<div class="text-center text-muted mb-4" style="box-sizing:border-box;margin-bottom:1.5rem !important;text-align:center !important;color:#6c757d !important;">{{$konfig->phone}}</div>


</div>


</body>
</html>
