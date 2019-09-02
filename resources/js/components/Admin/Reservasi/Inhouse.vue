<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Reservasi</li>
        <li class="breadcrumb-item active">Inhouse</li>
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
                                <strong>Daftar Inhouse</strong>

                            </div>
                            <div class="card-body">
                                <hr>
                                <div class="col-md-12">
                                  <h5>Petunjuk</h5>
                                  <a href="#" style="margin-bottom: 5px" class="btn btn-primary btn-sm"><span class="fa fa-search"></span> </a> * Detil Inhouse
                                  <a href="#" style="margin-bottom: 5px" class="btn btn-success btn-sm"><span class="fa fa-angle-double-left"></span></a> * Pindah Kamar

                                </div>
                                <div class="col-md-12">
                                    <v-client-table :data="bookings" :columns="columns" :options="options">
                                      <div slot="aksi" slot-scope="{row}">
                                          <a href="#" @click="detilInhouse(row.no_room)" style="margin-bottom: 5px" class="btn btn-primary btn-sm"><span class="fa fa-search"></span></a>
                                          <a href="#" v-show="row.tipe != 'Suite'" @click="pindahKamar(row.no_room)" style="margin-bottom: 5px" class="btn btn-success btn-sm"><span class="fa fa-angle-double-left"></span></a>
                                      </div>
                                        <p slot="kode_booking" slot-scope="{row}" class="text-uppercase">{{row.kode_booking}}</p>
                                        <p slot="jml_penghuni" slot-scope="{row}" class="text-right">{{row.jml_penghuni}} Orang</p>
                                        <p slot="tgl_checkin" slot-scope="{row}">{{row.tgl_checkin | myDate}}</p>
                                        <div slot="tgl_checkout" slot-scope="{row}">
                                          <a href="#" v-show="row.tgl_checkout === moment().format('YYYY-MM-DD') && row.status === 'Inhouse'" @click.prevent="" class="btn btn-sm badge badge-danger text-uppercase">Checkout NOW</a>
                                          <p v-show="row.tgl_checkout != moment().format('YYYY-MM-DD')">{{row.tgl_checkout | myDate}}</p>
                                        </div>
                                        <div slot="status" class="text-center" slot-scope="{row}">
                                            <a v-if="row.status === 'Waiting Payment'" @click.prevent="pembayaranModal(row.kode_booking)" href="" class="btn btn-sm badge badge-warning text-uppercase">Waiting Payment</a>
                                            <a v-if="row.status === 'Reserved'" href="" @click.prevent="" class="btn btn-sm badge badge-primary text-uppercase">Reserved</a>
                                            <a v-if="row.status === 'Inhouse'" href="#" @click.prevent="" class="btn btn-sm badge badge-info text-uppercase">Inhouse</a>
                                            <!-- <a v-if="row.status === 'Checkout'" href="#" @click.prevent="" class="btn btn-sm badge badge-danger text-uppercase">Checkout</a> -->
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

    <div class="modal fade bd-example-modal-lg" id="modalPindah" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pindah Kamar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <form class="" method="post" id="formCharges">
                      <div class="modal-body">
                        <div class="alert alert-success" role="alert">
                            {{avKamar.msg}}
                        </div>
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
                                    <td class="text-center">
                                      <form action="" id="formOldRoom">
                                        <input type="hidden" class="form-control" name="old_no" :value="avKamar.oldRoom.no_room">
                                      </form>
                                      <a href="#" @click="pilihKamar(kamar.no_room)" class="btn btn-warning btn-sm"> <span class="fa fa-check"></span>Pilih</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                      </div>
                  </form>
                </div>
                <div class="modal-footer">
                    <button type="button" name="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="modal fade bd-example-modal-lg" id="modalInhouse" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detil Inhouse Kamar {{dtlInhouse.no_room}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label for="">Nama Penghuni</label>
                    <input type="text" class="form-control" readonly :value="dtlInhouse.nama_penghuni">
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" @click="updateOpsional()">Update</button>
                    <button type="button" name="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
                </div>
            </div>
        </div>
    </div> -->






</div>
</template>

<script>
import moment from 'moment'
import Editor from '@tinymce/tinymce-vue';
export default {
    components: {
        'editor': Editor
    },
    data() {
        return {
            editMode: false,
            columns: [
                'aksi',  'status','kode_booking', 'no_room', 'tipe', 'nama_penghuni', 'jml_penghuni', 'tgl_checkin', 'tgl_checkout',
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    nama_penghuni: 'Nama Tamu',
                    kode_booking: 'Kode Booking',
                    jml_penghuni: 'Jumlah Tamu',
                    tgl_checkin: 'Tgl Checkin',
                    tgl_checkout: 'Tgl Checkout',
                    status: 'Status',
                },
                sortable: ['kode_booking', 'no_room', 'tipe', 'nama_penghuni', 'jml_penghuni', 'tgl_checkin', 'tgl_checkout',],
                filterable: ['kode_booking', 'no_room', 'tipe', 'nama_penghuni', 'jml_penghuni', 'tgl_checkin', 'tgl_checkout',],
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
            form: new Form({
              kode_booking: '',
              id_pelanggan: '',
              alasan: '',
              nama: '',
            }),
            dtlInhouse: [],
            avKamar: [],
        }
    },
    methods: {
      pilihKamar(no_room){
        var formData = new FormData(document.getElementById("formOldRoom"));
        let instance = this;
        swal({
            title: 'Anda yakin?',
            text: "Operasi ini tidak dapat dibatalkan!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, pindah kamar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                this.$Progress.start();
                axios.post('/api/admin/inhouse/pindah/' + no_room, formData).then(() => {
                    swal(
                        'Berhasil!',
                        'Kamar berhasil dipindah.',
                        'success'
                    )
                    Fire.$emit('AfterCreate')
                    this.$Progress.finish();
                    $('#modalPindah').modal('hide');
                }).catch(() => {
                    swal("Gagal!", "Terjadi kesalahan.",
                        "warning");
                });
            }

        })
      },
      pindahKamar(no_room){
        $('#modalPindah').modal('show');
        axios.get('/api/admin/inhouse/pindah/' + no_room).then(({
          data
        }) => (this.avKamar = data));
      },
      detilInhouse(no_room){
        this.$router.push({name: 'detil-inhouse', params:{no_room:no_room}})
      },
      detilModal(no_room){
        $('#modalInhouse').modal('show');
        axios.get('/api/admin/inhouse/' + no_room).then(({
          data
        }) => (this.dtlInhouse = data));
      },
      moment: function() {
        return moment();
      },
      detilBooking(kode_booking){
        this.$router.push({name: 'detil-booking', params:{kode_booking:kode_booking}})
      },
      dataBooking(){
        axios.get('/api/admin/inhouse').then(({
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
