<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Reservasi</li>
        <li class="breadcrumb-item">Booking</li>
        <li class="breadcrumb-item active">Cek Kamar</li>
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
                                <strong>Cek Kamar</strong>

                            </div>

                            <form class="" method="post" @submit.prevent="cekKamar()">
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="">Tanggal Check-in</label>
                                            </div>
                                            <div class="col-md-4">
                                                <date-picker name="tgl_checkin" v-model="form.tgl_checkin" :lang="lang" value-type="format" :class="{ 'is-invalid': form.errors.has('tgl_checkin') }"></date-picker>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <div class="col-md-3">
                                                <label for="">Tanggal Check-out</label>
                                            </div>
                                            <div class="col-md-4">
                                                <date-picker name="tgl_checkout" v-model="form.tgl_checkout" :lang="lang" value-type="format" :class="{ 'is-invalid': form.errors.has('tgl_checkout') }"></date-picker>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <div class="col-md-3">
                                                <label for="">Tipe Kamar</label>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" v-model="form.id_tipe">
                                                    <option value="0">Semua</option>
                                                    <option v-for="tipe in TipeKamar" :key="tipe.id" :value="tipe.id">{{tipe.tipe}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <div class="col-md-3">
                                                <label for="">Jumlah Kamar</label>
                                            </div>
                                            <div class="col-md-4">

                                                <input type="text" name="jml_tamu" class="form-control" v-model="form.jml_tamu" :class="{ 'is-invalid': form.errors.has('jml_tamu') }">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-success"> <span class="fa fa-search"></span> Cek </button>
                                    <router-link :to="{ name: 'bookings', params: {} }" class="btn btn-danger"> Batal</router-link>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="row" v-show="dataKamar != 0">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Hasil Pencarian</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <!-- <v-client-table :data="dataKamar" :columns="columns" :options="options">
                                  <p slot="tipe.kapasitas" slot-scope="{row}">{{row.tipe.kapasitas}} Person</p>
                                  <p slot="tipe.harga" slot-scope="{row}">Start @ Rp. {{row.tipe.harga | currency}}/Night</p>
                                </v-client-table> -->
                                    <div class="alert alert-success" role="alert">
                                        {{dataKamar.msg}}
                                    </div>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Kamar Tersedia</th>
                                                <th>Tipe Kamar</th>
                                                <th>Kapasitas</th>
                                                <!-- <th>Deskripsi</th> -->
                                                <th>Harga</th>
                                                <th>Jumlah Kamar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <form class="" method="post" id="formBooking" enctype="multipart/form-data">
                                                <!-- <input type="text" class="form-control" name="jml_kamar[]"> -->
                                                <input type="hidden" name="jml_kamar" class="form-control" :value="form.jml_kamar">
                                                <input type="hidden" name="tgl_checkin" class="form-control" :value="form.tgl_checkin">
                                                <input type="hidden" name="tgl_checkout" class="form-control" :value="form.tgl_checkout">

                                            </form>
                                            <tr v-for="kamar in dataKamar.kamar" :key="kamar.id">
                                                <td>{{kamar.jml_kamar}}</td>
                                                <td>{{kamar.tipe}}</td>
                                                <td>{{kamar.kapasitas}} Person</td>
                                                <!-- <td>{{kamar.tipe.deskripsi}}</td> -->

                                                <td>Start @ Rp. {{kamar.harga | currency}}/Night</td>
                                                <td>
                                                    <select v-model="form.jml_kamar" class="form-control">
                                                        <option value=""> -- </option>
                                                        <option value="1">1</option>
                                                    </select>
                                                </td>
                                                <td class="text-center"><a href="#" @click="roomTemp(kamar.id_tipe)" class="btn btn-warning btn-sm"> <span class="fa fa-check"></span>Book</a> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-primary" @click="modal()" v-show="countTemp != 0">
                                    Proses <span class="badge badge-light">{{countTemp}}</span>
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="detailBooking" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" @submit.prevent="prosesBooking()" id="formProsesBooking" method="post">
                    <div class="modal-body">
                        <h5><strong>Detil Kamar</strong></h5>
                        <hr>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>

                                    <th>Aksi</th>
                                    <th>No Kamar</th>
                                    <th>Tipe kamar</th>
                                    <th>Kapasitas</th>
                                    <th>Jml Kamar</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="temp in dataTemp.temp" :key="temp.id">
                                    <td class="text-center"><a href="#" class="btn btn-light" @click="hapusTemp(temp.id)">
                                            <i class="fa fa-minus red"></i> Hapus
                                        </a></td>
                                    <td class="text-center">
                                        <form class="" v-if="temp.no_room == null" @submit.prevent="roomModal(temp.id)" method="post">
                                            <!-- <input type="hidden" name="id_tipe" :value="temp.id_tipe">
                                            <input type="hidden" name="jml_kamar" :value="temp.jml_kamar"> -->
                                            <button type="submit" class="btn btn-sm btn-success"><span class="fa fa-plus"></span> No Kamar</button>
                                        </form>
                                        {{temp.no_room}}
                                    </td>
                                    <td>{{temp.tipe}}</td>
                                    <td>{{temp.kapasitas}} Person</td>
                                    <td>{{temp.jml_kamar}}</td>
                                    <td>Rp. {{temp.harga | currency}}</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="5">
                                        <p class="text-right">Subtotal</p>
                                    </th>
                                    <th>
                                        <p v-for="subtotal in dataTemp.sub_total">Rp. {{subtotal.subtotal | currency}}</p>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="5">
                                        <p class="text-right">Total (Subtotal X {{dataTemp.durasi}} hari menginap)</p>
                                    </th>
                                    <th>
                                        <p class="red">Rp. {{dataTemp.total | currency}}</p>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <h5> <strong>Detil Booking</strong> </h5>
                        <hr>
                        <div class="form-group" v-for="temp in dataTemp.temp" :key="temp.no_room">
                            <input type="hidden" name="id_tipe[]" class="form-control" :value="temp.id_tipe">
                            <input type="hidden" name="jml_kamar[]" class="form-control" :value="temp.jml_kamar">
                            <input type="hidden" name="no_room[]" class="form-control" :value="temp.no_room">
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Kode Booking</strong></label>
                            <input type="text" name="kode_booking" class="form-control text-uppercase" :value="dataTemp.kd_booking" readonly>
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Tanggal Check-in</strong> </label>
                            <input type="date" name="tgl_checkin" :value="dataTemp.tgl_checkin" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Tanggal Check-out</strong> </label>
                            <input type="date" name="tgl_checkout" :value="dataTemp.tgl_checkout" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for=""> <strong>Total</strong> </label>
                            <input type="text" class="form-control" name="total" :value="dataTemp.total" readonly>
                        </div>
                        <hr>
                        <h5> <strong>Detil Pelanggan</strong> </h5>
                        <hr>
                        <div class="form-group">
                            <label for=""> <strong>Nama Lengkap (Sesuai Kartu Identitas)*</strong> </label>
                            <input type="text" class="form-control" name="nama">
                        </div>
                        <div class="form-group">
                            <label for=""> <strong>Tipe Identitas*</strong> </label>
                            <select class="form-control" name="tipe_identitas">
                                <option value="-">-- Pilih --</option>
                                <option value="KTP">KTP</option>
                                <option value="SIM">SIM</option>
                                <option value="PASSPORT">PASSPORT</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for=""> <strong>No Identitas*</strong> </label>
                            <input type="text" class="form-control" name="no_identitas">
                        </div>
                        <div class="form-group">
                            <label for=""> <strong>Alamat (Sesuai Kartu Identitas)*</strong> </label>
                            <textarea type="text" class="form-control" name="alamat"></textarea>
                        </div>
                        <div class="form-group">
                            <label for=""> <strong>No Telepon/ Handphone*</strong> </label>
                            <input type="text" class="form-control" name="no_telepon">
                        </div>
                        <div class="form-group">
                            <label for=""> <strong>Email</strong> </label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <label for=""> <strong>Tipe*</strong> </label>
                            <select class="form-control" name="tipe">
                                <option value="WIG">Walk In Guest</option>
                                <option value="PHONE">By Phone</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for=""> <strong>Keterangan</strong> </label>
                            <textarea type="keterangan" class="form-control" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Booking</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalRoom" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Kamar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" method="post" id="formCharges">
                    <div class="modal-body">
                        <div class="alert alert-success" role="alert">
                            {{avKamar.msg}}

                        </div>
                        <form action="" id="formPilihKamar" action="POST">
                            <input type="hidden" name="id_temp" id="id_temp" :value="avKamar.id_temp">
                        </form>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No Kamar</th>
                                    <th>Tipe Kamar</th>
                                    <th>Kapasitas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="kamar in avKamar.avKamar" :key="kamar.id">
                                    <td>{{kamar.no_room}}</td>
                                    <td>{{kamar.tipe}}</td>
                                    <td>
                                        {{kamar.kapasitas}}
                                    </td>
                                    <td class="text-center"><a href="#" @click="pilihKamar(kamar.no_room)" class="btn btn-warning btn-sm"> <span class="fa fa-check"></span>Pilih</a> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" name="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
                </div>
            </div>
        </div>
    </div>

</div>
</template>

<script>
import DatePicker from 'vue2-datepicker'
export default {
    components: {
        DatePicker,
    },
    data() {
        return {
            jml_kamar: [],
            editMode: false,
            formBook: new Form({
                no_room: '',
                tamu_booking: ''
            }),
            form: new Form({
                tgl_checkin: '',
                tgl_checkout: '',
                jml_tamu: '',
                id_tipe: '',
                jml_kamar: '',
            }),
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
            columns: [
                'tipe.tipe', 'tipe.kapasitas', 'tipe.deskripsi', 'tipe.harga', 'aksi'
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    no_room: 'No Kamar',
                    'tipe.tipe': 'Tipe Kamar',
                    'tipe.kapasitas': 'Kapasitas',
                    'tipe.deskripsi': 'Deskripsi',
                    'tipe.harga': 'Harga',
                },
                sortable: ['no_room', 'tipe.tipe', 'tipe.kapasitas', 'tipe.harga'],
                filterable: ['no_room', 'tipe.tipe', 'tipe.kapasitas', 'tipe.harga'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            fasilitas: [],
            dataKamar: [],
            countTemp: [],
            dataTemp: [],
            TipeKamar: [],
            avKamar: [],
        }
    },
    methods: {
        pilihKamar(no_room) {
            var formData = new FormData(document.getElementById("formPilihKamar"));
            let instance = this;

            this.$Progress.start();
            axios.post('/api/admin/booking/no-kamar/pilih/' + no_room, formData)
                .then(() => {
                    swal(
                        'Sukses!',
                        'Kamar berhasil ditambah.',
                        'success'
                    )
                    $('#modalRoom').modal('hide');
                    this.loadTemp();
                    this.$Progress.finish();
                })
                .catch(() => {
                    swal(
                        'Gagal!',
                        'Terjadi kesalahan',
                        'warning'
                    )
                })
        },
        roomModal(id_temp) {
            $('#modalRoom').modal('show');
            this.form.post('/api/admin/booking/no-kamar/' + id_temp).then(({
                data
            }) => (this.avKamar = data))
        },
        loadTipe() {
            axios.get('/api/admin/tipe-kamar').then(({
                data
            }) => (this.TipeKamar = data));
        },
        prosesBooking() {
            var formData = new FormData(document.getElementById("formProsesBooking"));
            let instance = this;
            this.$Progress.start();
            axios.post('/api/admin/booking/proses', formData)
                .then(() => {
                    Fire.$emit('AfterCreate');
                    swal(
                        'Sukses!',
                        'Ditambahkan ke daftar booking.',
                        'success'
                    )
                    this.$Progress.finish();
                    $('#detailBooking').modal('hide');
                    this.$router.push({
                        name: 'bookings'
                    });
                }).catch(() => {
                    swal("Gagal!", "Terjadi kesalahan.",
                        "warning");
                });
        },
        hapusTemp(id) {
            swal({
                title: 'Anda yakin?',
                text: "Hapus kamar dari list booking!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus dari list!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.value) {
                    this.$Progress.start();
                    axios.delete('/api/admin/booking/room/temp/hapus/' + id).then(() => {
                        Fire.$emit('AfterCreate');
                        swal(
                            'Dihapus!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                        this.$Progress.finish();
                        $('#detailBooking').modal('hide');
                        this.$Progress.finish();
                    }).catch(() => {
                        swal("Gagal!", "Terjadi kesalahan.",
                            "warning");
                    });
                }

            })
        },
        loadTemp() {
            axios.get("/api/admin/booking/room/temp").then(({
                data
            }) => (this.dataTemp = data))
        },
        modal() {
            $('#detailBooking').modal('show');
            this.loadTemp();
        },
        hitungTemp() {
            axios.get("/api/admin/booking/room/temp/count").then(({
                data
            }) => (this.countTemp = data))
        },
        roomTemp(id_tipe) {
            var formData = new FormData(document.getElementById("formBooking"));
            let instance = this;
            this.$Progress.start();
            axios.post('/api/admin/booking/room/add/' + id_tipe, formData)
                .then(() => {
                    Fire.$emit('AfterCreate');
                    swal(
                        'Sukses!',
                        'Ditambahkan ke daftar booking.',
                        'success'
                    )
                    this.$Progress.finish();
                }).catch(() => {
                    swal("Gagal!", "Terjadi kesalahan.",
                        "warning");
                });
        },
        cekKamar() {
            this.form.post('/api/admin/booking/cek-kamar').then(({
                data
            }) => (this.dataKamar = data))
        },

    },
    created() {
        this.$Progress.start();
        this.hitungTemp();
        this.loadTipe();
        Fire.$on('AfterCreate', () => {
            this.cekKamar();
            this.loadTemp();
            this.hitungTemp();
        });
        this.$Progress.finish();
    }
}
</script>
