<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Reservasi</li>
        <li class="breadcrumb-item active">Booking</li>
        <!-- Breadcrumb Menu-->
        <li class="breadcrumb-menu d-md-down-none">
            <div class="btn-group" role="group" aria-label="Button group">
                <a class="btn" href="#">
                    <i class="icon-speech"></i>
                </a>
                <a class="btn" href="./">
                    <i class="icon-graph"></i>  Dashboard</a>
                <a class="btn" href="#">
                    <i class="icon-settings"></i> Settings</a>
            </div>
        </li>
    </ol>
    <div class="container-fluid">
        <div id="ui-view">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Daftar Booking</strong>

                            </div>
                            <div class="card-body table-responsive">
                                <div class="col-md-12">
                                    <router-link style="margin-bottom: 10px" to="/admin/booking/room" href="#" @click="modal()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Booking</router-link>
                                    <div class="radio">
                                        <label class="radio-inline"><input type="radio" v-model="filter.inhouse" @click="filterBooking(3)" value="3" name="optradio"> In House</label>
                                        <label class="radio-inline"><input type="radio" v-model="filter.rsv_today" @click="filterBooking('rsv_today')" value="rsv_today" name="optradio">Rsv Today</label>
                                        <label class="radio-inline"><input type="radio" v-model="filter.all_rsv" @click="filterBooking('all_rsv')" value="all_rsv" name="optradio">ALL RSV</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <v-client-table :data="bookings" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}">
                                            <a href="#" @click="detilModal(row.kode_booking)" style="margin-bottom: 5px" class="btn btn-primary btn-sm"><span class="fa fa-search"></span></a>
                                            <a v-if="row.status === 'Waiting Payment'" href="#" @click="cancelModal(row)" class="btn btn-danger btn-sm"><span class="fa fa-window-close"></span></a>
                                        </div>
                                        <p slot="kode_booking" slot-scope="{row}" class="text-uppercase">{{row.kode_booking}}</p>
                                        <p slot="tgl_checkin" slot-scope="{row}">{{row.tgl_checkin | myDate}}</p>
                                        <p slot="tgl_checkout" slot-scope="{row}">{{row.tgl_checkout | myDate}}</p>
                                        <p slot="total_tagihan" class="red" slot-scope="{row}">Rp. {{row.total_tagihan | currency}}</p>
                                        <p slot="terbayarkan" class="red" slot-scope="{row}">Rp. {{row.terbayarkan | currency}}</p>
                                        <p slot="kekurangan" class="red" slot-scope="{row}">Rp. {{row.kekurangan | currency}}</p>
                                        <p slot="jml_kamar" class="text-right" slot-scope="{row}">{{row.jml_kamar}} Kamar</p>
                                        <p slot="tgl_checkout" slot-scope="{row}">{{row.tgl_checkout | myDate}}</p>
                                        <p slot="created_at" slot-scope="{row}">{{row.created_at | myDate}}</p>
                                        <div slot="status" class="text-center" slot-scope="{row}">
                                            <a v-if="row.status === 'Waiting Payment'" @click.prevent="pembayaranModal(row.kode_booking)" href="" class="btn btn-sm badge badge-warning text-uppercase">Waiting Payment</a>
                                            <a v-if="row.status === 'Payment Accepted'" href="" @click.prevent="" class="btn btn-sm badge badge-primary text-uppercase">Payment Accepted</a>
                                            <a v-if="row.status === 'Checkin'" href="" @click.prevent="checkinRoute(row.kode_booking)" class="btn btn-sm badge badge-success text-uppercase">Checkin</a>
                                            <a v-if="row.status === 'Inhouse'" href="" @click.prevent="" class="btn btn-sm badge badge-info text-uppercase">Inhouse</a>
                                            <a v-if="row.status === 'Checkout'" href="" @click.prevent="" class="btn btn-sm badge badge-danger text-uppercase">Checkout</a>
                                        </div>
                                    </v-client-table>
                                </div>
                            </div>
                            <div class="card-footer text-right">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalDetil" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase">Detil Booking {{booking.kode_booking}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-success btn-sm" name="button" @click="pembayaranModal(booking.kode_booking)"><i class="fa fa-money-bill"></i> Pembayaran</button>
                                <button type="button" class="btn btn-success btn-sm" name="button" @click="detilTagihan(booking.kode_booking)"><i class="fa fa-money-bill"></i> Detil Tagihan</button>
                                <button type="button" class="btn btn-success btn-sm" name="button" @click="cetakInvoice(booking.kode_booking)"><i class="fa fa-print"></i> Cetak Invoice</button>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-3">
                                <p>Kode Booking</p>
                            </div>
                            <div class="col-md-9">
                                <p class="text-uppercase">{{booking.kode_booking}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Nama Pelanggan</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.nama}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Email Pelanggan</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.email}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>No Telepon</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.no_telepon}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Alamat Pelanggan</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.alamat}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Diinputkan Oleh</p>
                            </div>
                            <div class="col-md-9 blue">
                                <p>{{booking.created_by}}</p>
                            </div>

                            <div class="col-md-3">
                                <p>Tgl Input</p>
                            </div>
                            <div class="col-md-9 blue">
                                <p>{{booking.created_at}}</p>
                            </div>
                        </div>
                        <hr class="green">
                        <div class="row">
                            <div class="col-md-3">
                                <p>Tanggal Check-in</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.tgl_checkin | myDate}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Tanggal Check-out</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.tgl_checkout | myDate}}</p>
                            </div>

                            <div class="col-md-3">
                                <p>Total</p>
                            </div>
                            <div class="col-md-9">
                                <p class="red">Rp. {{booking.total | currency}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Terbayarkan</p>
                            </div>
                            <div class="col-md-9">
                                <p class="red">Rp. {{booking.terbayarkan | currency}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Kekurangan</p>
                            </div>
                            <div class="col-md-9">
                                <p class="red">Rp. {{booking.hutang | currency}}</p>
                            </div>

                            <div class="col-md-3">
                                <p>Metode Pembayaran</p>
                            </div>
                            <div class="col-md-9">
                                <p class="blue">{{booking.bank}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipe Kamar</th>
                                        <th>Kapasitas</th>
                                        <th>Jumlah Tamu</th>
                                        <!-- <th>Deskripsi</th> -->
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="kamar in dataKamar" :key="kamar.no_room">
                                        <td>{{kamar.tipe}}</td>
                                        <td>{{kamar.kapasitas}} Orang</td>
                                        <td>{{kamar.jml_kamar}} Kamar</td>
                                        <td>
                                            <p>Rp. {{kamar.harga | currency}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalPayment" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase">Pembayaran Booking {{booking.kode_booking}}</h5>
                    <button type="button" class="close" @click="tutupModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="cash-tab" data-toggle="tab" href="#cash" role="tab" aria-controls="cash" aria-selected="true">Cash</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="transfer-tab" data-toggle="tab" href="#transfer" role="tab" aria-controls="transfer" aria-selected="false">Transfer</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTab1Content">
                            <div class="tab-pane fade active show" id="cash" role="tabpanel" aria-labelledby="cash-tab">
                                <form class="" @submit.prevent="postCash()" method="post">
                                    <div class="form-group">
                                        <label for="">Kode Booking</label>
                                        <input type="text" name="kode_booking" v-model="cash.kode_booking" :class="{'is-invalid' : cash.errors.has('kode_booking')}" class="form-control text-uppercase" readonly value="">
                                        <has-error :form="cash" field="kode_booking"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Total Tagihan</label>
                                        <money v-model="cash.total_tagihan" v-bind="money" type="text" name="total_tagihan" readonly class="form-control" :class="{ 'is-invalid': cash.errors.has('total_tagihan') }" id="total"></money>
                                        <has-error :form="cash" field="total_tagihan"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jumlah Pembayaran</label>
                                        <money v-model="cash.jml_bayar" v-bind="money" type="text" name="jml_bayar" class="form-control" :class="{ 'is-invalid': cash.errors.has('jml_bayar') }" id="total"></money>
                                        <has-error :form="cash" field="jml_bayar"></has-error>
                                    </div>
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-success btn-sm" name="button">Simpan</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="transfer" role="tabpanel" aria-labelledby="transfer-tab">
                                <form class="" @submit.prevent="postTransfer()" method="post">
                                    <div class="form-group">
                                        <label for="">Kode Booking</label>
                                        <input type="text" name="kode_booking" v-model="transfer.kode_booking" :class="{'is-invalid' : transfer.errors.has('transfer.kode_booking')}" class="form-control text-uppercase" readonly value="">
                                        <has-error :form="transfer" field="kode_booking"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Total Tagihan</label>
                                        <money v-model="transfer.total_tagihan" v-bind="money" type="text" name="total_tagihan" readonly class="form-control" :class="{ 'is-invalid': transfer.errors.has('total_tagihan') }" id="total"></money>
                                        <has-error :form="transfer" field="total_tagihan"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Transfer Ke</label>
                                        <select class="form-control" name="id_metode" v-model="transfer.id_metode">
                                            <option v-for="mtd in metode" :key="mtd.id" :value="mtd.id">{{mtd.bank}} - {{mtd.no_rekening}} AN {{mtd.atas_nama}}</option>
                                        </select>
                                        <has-error :form="transfer" field="jml_bayar"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Nama Pemilik Rekening</label>
                                        <input type="text" name="nama_pemilik_rekening" v-model="transfer.nama_pemilik_rekening" :class="{'is-invalid' : transfer.errors.has('transfer.nama_pemilik_rekening')}" class="form-control" value="">
                                        <has-error :form="transfer" field="nama_pemilik_rekening"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Nomor Rekening</label>
                                        <input type="text" name="no_rekening" v-model="transfer.no_rekening" :class="{'is-invalid' : transfer.errors.has('transfer.no_rekening')}" class="form-control" value="">
                                        <has-error :form="transfer" field="no_rekening"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Transfer</label>
                                        <date-picker name="tgl_transfer" v-model="transfer.tgl_transfer" :lang="lang" value-type="format" :class="{ 'is-invalid': transfer.errors.has('tgl_transfer') }"></date-picker>
                                        <has-error :form="transfer" field="tgl_transfer"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jumlah Pembayaran</label>
                                        <money v-model="transfer.jml_bayar" v-bind="money" type="text" name="jml_bayar" class="form-control" :class="{ 'is-invalid': transfer.errors.has('jml_bayar') }" id="total"></money>
                                        <has-error :form="transfer" field="jml_bayar"></has-error>
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-success btn-sm" name="button">Simpan</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard
                                locavore carles etsy salvia banksy
                                hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you
                                probably haven't heard
                                of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" name="button" @click="tutupModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade bd-example-modal-lg" id="modalCancel" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" @submit.prevent="cancelBooking()" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Kode Booking</label>
                            <input type="text" readonly class="form-control" v-model="form.kode_booking" :class="{'is-invalid' : form.errors.has('form.kode_booking')}" name="kode_booking">
                            <has-error :form="form" field="kode_booking"></has-error>
                            <input type="hidden" v-model="form.id_pelanggan" name="" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Pelanggan</label>
                            <input type="text" v-model="form.nama" readonly :class="{'is-invalid' : form.errors.has('form.nama')}" class="form-control"></input>
                            <has-error :form="form" field="nama"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Alasan</label>
                            <input type="text" v-model="form.alasan" :class="{'is-invalid' : form.errors.has('form.alasan')}" class="form-control"></input>
                            <has-error :form="form" field="alasan"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-show="editMode" type="submit" class="btn btn-primary">Ubah</button>
                        <button v-show="!editMode" type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
</template>

<script>
import DatePicker from 'vue2-datepicker'
import Editor from '@tinymce/tinymce-vue';
export default {
    components: {
        'editor': Editor,
        DatePicker,
    },
    data() {
        return {
            editMode: false,
            columns: [
                'aksi', 'status',  'kode_booking', 'tipe', 'jml_kamar', 'nama', 'tgl_checkin', 'tgl_checkout', 'total_tagihan', 'terbayarkan', 'kekurangan', 'tipe_booking',
                'created_at', 'diinput_oleh'
            ],
            lang: {
                days: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                pickers: ['next 7 days', 'next 30 days', 'previous 7 days', 'previous 30 days'],
                placeholder: {
                    date: 'Pilih Tanggal',
                }
            },
            optionsDate: {
                format: 'YYYY-mm-dd'
            },
            money: {
                decimal: ',',
                thousands: '.',
                prefix: 'Rp ',
                suffix: '',
                precision: 0,
                masked: false
            },
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    nama: 'Nama Lengkap',
                    kode_booking: 'Kode Booking',
                    no_telepon: 'No Telepon',
                    tgl_checkin: 'Tgl Checkin',
                    tgl_checkout: 'Tgl Checkout',
                    status: 'Status',
                    tipe_booking: 'Tipe Booking',
                    created_at: 'Tgl Reservasi'
                },
                sortable: ['kode_booking', 'tipe', 'jml_kamar', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'total_tagihan', 'terbayarkan', 'kekurangan', 'status', 'tipe_booking', 'created_at',
                    'diinput_oleh'
                ],
                filterable: ['kode_booking', 'tipe', 'jml_kamar', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'total_tagihan', 'terbayarkan', 'kekurangan', 'status', 'tipe_booking', 'created_at',
                    'diinput_oleh'
                ],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            bookings: [],
            booking: [],
            dataKamar: [],
            form: new Form({
                kode_booking: '',
                id_pelanggan: '',
                alasan: '',
                nama: '',
            }),
            filter: new Form({
                inhouse: '',
                rsv_today: '',
                all_rsv: '',
            }),
            cash: new Form({
                kode_booking: '',
                total_tagihan: '',
                jml_bayar: '',
            }),
            transfer: new Form({
                kode_booking: '',
                total_tagihan: '',
                jml_bayar: '',
                id_metode: '',
                nama_pemilik_rekening: '',
                no_rekening: '',
                tgl_transfer: '',
            }),
            metode: [],
        }
    },
    methods: {
        checkinRoute(kode_booking){
          this.$router.push({
            name: 'form-checkin',
            params: {
              kode_booking: kode_booking
            }
          })
        },
        postTransfer() {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, proses pembayaran!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    this.transfer.post('/api/admin/payment/transfer').then(() => {
                        swal(
                            'Berhasil!',
                            'Pembayaran Transfer Berhasil.',
                            'success'
                        )
                        this.tutupModal();
                        this.transfer.reset();
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish();
                    }).catch(() => {
                        swal("Gagal!", "Terjadi kesalahan.",
                            "warning");
                    });
                }

            })
        },
        tutupModal() {
            $('#modalPayment').modal('hide');
            $('#modalDetil').modal('hide');
        },
        postCash() {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, proses pembayaran!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    this.cash.post('/api/admin/payment/cash').then(() => {
                        swal(
                            'Berhasil!',
                            'Pembayaran Cash Berhasil.',
                            'success'
                        )
                        this.tutupModal();
                        this.cash.reset();
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish();
                    }).catch(() => {
                        swal("Gagal!", "Terjadi kesalahan.",
                            "warning");
                    });
                }

            })
        },
        metodePembayaran() {
            axios.get('/api/admin/payment/metode').then(({
                data
            }) => (this.metode = data));
        },
        pembayaranModal(kode_booking) {
            $('#modalPayment').modal('show');
            // $('#modalDetil').modal('hide');
            axios.get('/api/admin/booking/detil/' + kode_booking).then(({
                data
            }) => (this.cash.fill(data)));
            axios.get('/api/admin/booking/detil/' + kode_booking).then(({
                data
            }) => (this.transfer.fill(data)));
            this.metodePembayaran();
        },
        detilTagihan(kode_booking) {
            $('#modalDetil').modal('hide');
            this.$router.push({
                name: 'detil-tagihan',
                params: {
                    kode_booking: kode_booking
                }
            })
        },
        cetakInvoice(kode_booking) {
            window.open('/booking/invoice/' + kode_booking, '_blank');
        },
        detilKamar() {
            axios.get('/api/admin/booking/detil/kamar/' + this.$route.params.kode_booking).then(({
                data
            }) => (this.dataKamar = data));
        },
        dataBooking() {
            axios.get('/api/admin/booking/detil/' + this.$route.params.kode_booking).then(({
                data
            }) => (this.booking = data));
        },
        detilModal(booking) {
            $('#modalDetil').modal('show');
            axios.get('/api/admin/booking/detil/kamar/' + booking).then(({
                data
            }) => (this.dataKamar = data));
            axios.get('/api/admin/booking/detil/' + booking).then(({
                data
            }) => (this.booking = data));
        },
        filterBooking(filter) {
            axios.get('/api/admin/booking/filter/' + filter).then(({
                data
            }) => (this.bookings = data));
        },
        cancelModal(booking) {
            this.form.reset();
            $('#modalCancel').modal('show');
            this.form.fill(booking);
        },
        cancelBooking() {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batalkan booking!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    this.form.post('/api/admin/booking/cancel').then(() => {
                        swal(
                            'Dibatalkan!',
                            'Booking dibatalkan.',
                            'success'
                        )
                        Fire.$emit('AfterCreate')
                        $('#modalCancel').modal('hide');
                        this.$Progress.finish();
                    }).catch(() => {
                        swal("Gagal!", "Terjadi kesalahan.",
                            "warning");
                    });
                }

            })
        },
        detilBooking(kode_booking) {
            this.$router.push({
                name: 'detil-booking',
                params: {
                    kode_booking: kode_booking
                }
            })
        },
        dataBooking() {
            axios.get('/api/admin/booking').then(({
                data
            }) => (this.bookings = data));
        }
    },
    created() {
        this.$Progress.start();
        this.dataBooking();
        Fire.$on('AfterCreate', () => {
            this.dataBooking('');
        });
        this.$Progress.finish();
    }
}
</script>
