<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\KonfigWeb;
class InvoiceBooking extends Notification implements ShouldQueue
{
    use Queueable;

    protected $kode_booking;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($kode_booking)
    {
        $this->kode_booking = $kode_booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $kode_booking = $this->kode_booking;

        $konfig = KonfigWeb::first();
        $booking = DB::table('bookings')
                        ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                        ->select(DB::raw("bookings.kode_booking, bookings.jml_kamar as jmlKamar, pelanggan.nama, pelanggan.email, pelanggan.no_telepon, pelanggan.alamat,
                        bookings.tgl_checkin, bookings.tgl_checkout, bookings.total, (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status"))
                        ->first();

                        $rooms = DB::table('booking_rooms')
                                    ->join('kamar', 'kamar.no_room', 'booking_rooms.no_room')
                                    ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                                    ->select(DB::raw("booking_rooms.no_room, tipe_kamar.tipe, tipe_kamar.kapasitas, booking_rooms.jml_tamu, tipe_kamar.harga"))
                                    ->where('booking_rooms.kode_booking', $kode_booking)
                                    ->get();

        $subtotal = DB::table('booking_rooms')
                        ->join('kamar', 'kamar.no_room', 'booking_rooms.no_room')
                        ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                        ->select(DB::raw("SUM(harga) as sub_total"))
                        ->where('booking_rooms.kode_booking', $kode_booking)
                        ->first();

        $tgl1 = Carbon::parse($booking->tgl_checkin);
        $tgl2 = Carbon::parse($booking->tgl_checkout);
        $durasi = $tgl1->diffInDays($tgl2);

        // $total = $subtotal->sub_total * $durasi;

        return (new MailMessage)
                  ->view('mail.invoice', [
                    'booking' => $booking,
                    'rooms' => $rooms,
                    'subtotal' => $subtotal,
                    'durasi' => $durasi,
                    'konfig' => $konfig,
                  ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
