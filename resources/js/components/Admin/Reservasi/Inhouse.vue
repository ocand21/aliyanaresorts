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
                                <div class="col-md-12">
                                    <v-client-table :data="bookings" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" href="#" @click="detilBooking(row.kode_booking)"><span class="fa fa-search info"></span> Detil</a>
                                            </div>
                                        </div>
                                        <p slot="kode_booking" slot-scope="{row}" class="text-uppercase">{{row.kode_booking}}</p>
                                        <p slot="tgl_checkin" slot-scope="{row}">{{row.tgl_checkin | myDate}}</p>
                                        <p slot="tgl_checkout" slot-scope="{row}">{{row.tgl_checkout | myDate}}</p>
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
import Editor from '@tinymce/tinymce-vue';
export default {
    components: {
        'editor': Editor
    },
    data() {
        return {
            editMode: false,
            columns: [
                'aksi', 'kode_booking', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'status',
            ],
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
                },
                sortable: ['kode_booking', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'status',],
                filterable: ['kode_booking', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'status',],
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
        }
    },
    methods: {
      cancelModal(booking){
        this.form.reset();
        $('#modalCancel').modal('show');
        this.form.fill(booking);
      },
      cancelBooking(){
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
