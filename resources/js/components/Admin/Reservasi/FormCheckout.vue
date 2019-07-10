<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Reservasi</li>
        <li class="breadcrumb-item active">Form Checkout</li>
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
                                <strong>Form Checkout</strong>
                            </div>
                            <div class="card-body">
                                <form class="" id="formTambah" method="post" enctype="multipart/form-data">

                                    <div class="col-md-12">
                                        <button type="button" @click="modalCharges()" name="button" class="btn btn-sm btn-warning"> <span class="fa fa-plus"></span> Charges </button>
                                    </div>

                                    <div class="col-md-12" style="margin-top: 10px">
                                        <div class="alert alert-warning" role="alert">
                                            <h4 class="alert-heading">Attention!</h4>
                                            <p>All rooms are non smoking. If smooking occurs during your stay, Rp. 500.000 cleaning fee will be charged to your account</p>

                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">

                                        <h3>Data Pelanggan</h3>
                                        <label for="">Nama Lengkap</label>
                                        <div class="form-group">
                                            <input type="text" readonly name="nama" v-model="form.nama" :class="{'is-invalid' : form.errors.has('form.nama')}" class="form-control">
                                            <has-error :form="form" field="no_room"></has-error>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="">Tipe Identitas</label>
                                                <input type="text" readonly class="form-control" name="tipe_identitas" v-model="form.tipe_identitas" :class="{'is-invalid' : form.errors.has('form.tipe_identitas')}">
                                            </div>
                                            <div class="col-md-9">
                                                <label for="">No Identitas</label>
                                                <input type="text" readonly class="form-control" name="no_identitas" v-model="form.no_identitas" :class="{'is-invalid' : form.errors.has('form.no_identitas')}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="">No Telepon</label>
                                                <input type="text" readonly class="form-control" name="no_telepon" v-model="form.no_telepon" :class="{'is-invalid' : form.errors.has('form.no_telepon')}">
                                            </div>
                                            <div class="col-md-9">
                                                <label for="">Email</label>
                                                <input type="email" readonly class="form-control" name="email" v-model="form.email" :class="{'is-invalid' : form.errors.has('form.email')}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Alamat</label>
                                            <textarea name="alamat" readonly class="form-control" rows="4" cols="80" v-model="form.alamat" :class="{'is-invalid' : form.errors.has('form.alamat')}"></textarea>
                                        </div>

                                    </div>

                                    <hr>

                                    <div class="col-md-12">
                                        <h3>Detil Booking</h3>
                                        <div class="form-group">
                                            <label for="">Kode Booking</label>
                                            <input type="text" readonly name="kode_booking" class="form-control" v-model="form.kode_booking" :class="{'is-invalid' : form.errors.has('form.kode_booking')}">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Jumlah Kamar</label>
                                            <input type="text" readonly name="jmlKamar" class="form-control" v-model="form.jmlKamar" :class="{'is-invalid' : form.errors.has('form.jmlKamar')}">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Tanggal Checkin</label>
                                                <input type="text" readonly name="tgl_checkin" class="form-control" v-model="form.tgl_checkin" :class="{'is-invalid' : form.errors.has('form.tgl_checkin')}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Tanggal Checkout</label>
                                                <input type="text" readonly name="tgl_checkout" class="form-control" v-model="form.tgl_checkout" :class="{'is-invalid' : form.errors.has('form.tgl_checkout')}">
                                            </div>
                                        </div>

                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No Kamar</th>
                                                    <th>Tipe Kamar</th>
                                                    <th>Kapasitas</th>
                                                    <th>Jumlah Tamu</th>
                                                    <!-- <th>Deskripsi</th> -->
                                                    <th>Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="kamar in dataKamar" :key="kamar.no_room">
                                                    <td>{{kamar.no_room}}</td>
                                                    <td>{{kamar.tipe}}</td>
                                                    <td>{{kamar.kapasitas}}</td>
                                                    <td>{{kamar.jml_tamu}}</td>
                                                    <td>
                                                        <p>Rp. {{kamar.harga | currency}}</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <thead>
                                                <tr>
                                                    <th colspan="4">
                                                        <p class="text-right">Durasi Menginap</p>
                                                    </th>
                                                    <td>
                                                        <p>{{dataCheckin.durasi}} Hari</p>
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <th colspan="4">
                                                        <p class="text-right">Subtotal</p>
                                                    </th>
                                                    <td>
                                                        <p>Rp. {{dataCheckin.detil.total | currency}}</p>
                                                    </td>
                                                </tr>
                                                <tr v-for="ch in dataCharges" :key="ch.id">
                                                    <th colspan="4">
                                                        <p class="text-right">{{ch.nama_charge}} {{ch.jumlah_persen}}%</p>
                                                    </th>
                                                    <td>
                                                        <p>Rp. {{ch.jumlah_rupiah | currency}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                  <th colspan="4">
                                                    <p class="text-right">Total</p>
                                                  </th>
                                                  <td>
                                                    <p>Rp. {{dataTagihan.total_tagihan | currency}}</p>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <th colspan="4">
                                                    <p class="text-right">Terbayarkan</p>
                                                  </th>
                                                  <td>
                                                    <p>Rp. {{dataTagihan.terbayarkan | currency}}</p>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <th colspan="4">
                                                    <p class="text-right">Kekurangan</p>
                                                  </th>
                                                  <td>
                                                    <p>Rp. {{dataTagihan.hutang | currency}}</p>
                                                  </td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </form>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-sm btn-primary" @click="prosesCheckout" type="submit">
                                    <i class="fa fa-dot-circle-o"></i> Konfirmasi Checkin</button>
                                <router-link to="/admin/check-in" class="btn btn-sm btn-danger">
                                    <i class="fa fa-dot-circle-o"></i> Batal</router-link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalCh" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Apply Charges</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" method="post" id="formCharges">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nama Charges</label>
                            <input type="text" class="form-control" v-model="formCharge.nama_charge" :class="{'is-invalid' : formCharge.errors.has('formCharge.nama_charge')}" name="nama_charge">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Persen (%)</label>
                            <input type="text" class="form-control" v-model="formCharge.jumlah_persen" :class="{'is-invalid' : formCharge.errors.has('formCharge.jumlah_persen')}" name="jumlah_persen"></input>
                        </div>
                        <div class="form-group">
                            <label for="">Nominal Rupiah</label>
                            <input type="text" class="form-control" v-model="formCharge.jumlah_rupiah" :class="{'is-invalid' : formCharge.errors.has('formCharge.jumlah_rupiah')}" name="jumlah_rupiah"></input>
                        </div>
                        <div class="form-group">
                            <label for="">keterangan</label>
                            <input type="text" class="form-control" v-model="formCharge.keterangan" :class="{'is-invalid' : formCharge.errors.has('formCharge.keterangan')}" name="keterangan"></input>
                        </div>
                    </div>

                </form>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" @click="applyCharges()">Apply</button>
                    <button type="button" name="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
                </div>
            </div>
        </div>
    </div>


</div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue';
export default {
    components: {
        'editor': Editor
    },
    data() {
        return {
            formCharge: new Form({
                nama_charge: '',
                jumlah_persen: '',
                jumlah_rupiah: '',
                keterangan: '',
            }),
            form: new Form({
                nama: '',
                tipe_identitas: '',
                no_identitas: '',
                alamat: '',
                email: '',
                no_telepon: '',
                kode_booking: '',
                tgl_checkin: '',
                tgl_checkout: '',
                jmlKamar: '',
            }),

            dataKamar: [],
            dataCheckin: [],
            charges: [],
            dataCharges: [],
            dataTagihan: [],
        }
    },
    methods: {
        loadTagihan(){
            axios.get('/api/admin/checkin/tagihan/' + this.$route.params.kode_booking).then(({
                data
            }) => (this.dataTagihan = data));
        },
        loadCharges() {
            axios.get('/api/admin/checkin/load-charges/' + this.$route.params.kode_booking).then(({
                data
            }) => (this.dataCharges = data));
        },
        applyCharges() {
            var formData = new FormData(document.getElementById("formCharges"));
            let instance = this;
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, apply charge!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    this.formCharge.post('/api/admin/checkin/charges/' + this.$route.params.kode_booking, ).then(() => {
                        swal(
                            'Berhasil!',
                            'Charge berhasil di apply.',
                            'success'
                        )
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish();
                        $('#modalCh').modal('hide');
                        this.formCharge.reset();
                    }).catch(() => {
                        swal("Gagal!", "Terjadi kesalahan.",
                            "warning");
                    });
                }

            })
        },
        dataCharges() {
            axios.get('/api/admin/charges').then(({
                data
            }) => (this.charges = data));
        },
        modalCharges() {
            $('#modalCh').modal('show');
            this.dataCharges();
        },
        detilCheckin() {
            axios.get('/api/admin/checkin/detil/' + this.$route.params.kode_booking).then(({
                data
            }) => (this.dataCheckin = data));
        },
        detilKamar() {
            axios.get('/api/admin/booking/detil/kamar/' + this.$route.params.kode_booking).then(({
                data
            }) => (this.dataKamar = data));
        },
        loadData() {
            axios.get('/api/admin/booking/detil/' + this.$route.params.kode_booking).then(({
                data
            }) => (this.form.fill(data)));
        },
        prosesCheckout() {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, konfirmasi checkout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    this.form.post('/api/admin/checkout/proses/' + this.$route.params.kode_booking).then(() => {
                        swal(
                            'Berhasil!',
                            'Checkout berhasil.',
                            'success'
                        )
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish();
                        this.$router.push({
                            name: 'check-out'
                        });
                    }).catch(() => {
                        swal("Gagal!", "Terjadi kesalahan.",
                            "warning");
                    });
                }

            })
        }
    },
    created() {
        Fire.$on('AfterCreate', () => {
          this.loadCharges();
          this.loadTagihan();
          this.loadData();
          this.detilKamar();
          this.detilCheckin();
        });
        this.loadCharges();
        this.loadTagihan();
        this.loadData();
        this.detilKamar();
        this.detilCheckin();
    }
}
</script>
