<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Reservasi</li>
        <li class="breadcrumb-item active">Checkout</li>
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
                                <strong>Daftar Checkout</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <form class="" action="" @submit.prevent="filterTanggal()" method="post">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Filter Tanggal</label>
                                                <date-picker name="tgl_awal" v-model="form.tgl_awal" :lang="lang" value-type="format" confirm :class="{ 'is-invalid': form.errors.has('tgl_awal') }"></date-picker>
                                                <date-picker name="tgl_akhir" v-model="form.tgl_akhir" :lang="lang" value-type="format" confirm :class="{ 'is-invalid': form.errors.has('tgl_akhir') }"></date-picker>
                                                <button type="submit" class="btn btn-danger btn-sm" name="button">Filter</button>
                                                <button type="button" @click="dataBooking()" class="btn btn-success btn-sm" name="button">Reset</button>

                                            </div>
                                        </div>
                                    </form>
                                    <hr>
                                    <v-client-table :data="bookings" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                              <a class="dropdown-item" href="#" @click="checkOut(row.kode_booking)"><span class="fa fa-suitcase-rolling"></span> Checkout</a>
                                              <a class="dropdown-item" href="#" @click="detilBooking(row.kode_booking)"><span class="fa fa-search info"></span> Detil</a>
                                            </div>
                                        </div>
                                        <p slot="kode_booking" slot-scope="{row}" class="text-uppercase">{{row.kode_booking}}</p>
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


</div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import Editor from '@tinymce/tinymce-vue';
export default {
    components: {
        DatePicker,
    },
    data() {
        return {
            editMode: false,
            form: new Form({
                tgl_awal: '',
                tgl_akhir: '',
            }),
            columns: [
                'aksi', 'kode_booking', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'status',
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
                sortable: ['kode_booking', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'status', ],
                filterable: ['kode_booking', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'status', ],
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
        }
    },
    methods: {
        checkOut(kode_booking){
          this.$router.push({
            name: 'form-checkout',
            params: {
              kode_booking: kode_booking
            }
          })
        },
        filterTanggal() {
            axios.get('/api/admin/checkout/' + this.form.tgl_awal + '/' + this.form.tgl_akhir).then(({
                data
            }) => (this.bookings = data));
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
            axios.get('/api/admin/checkout').then(({
                data
            }) => (this.bookings = data));
            this.form.reset();
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
