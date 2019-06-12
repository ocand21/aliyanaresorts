<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Reservasi</li>
        <li class="breadcrumb-item active">Detil Booking</li>
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
                                <strong>Detil Booking</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">

                                  <div class="row">
                                    <div class="col-md-12 text-right">
                                      <button type="button" class="btn btn-success btn-sm" name="button" @click="cetakInvoice()"><i class="fa fa-print"></i> Cetak Invoice</button>
                                    </div>
                                  </div>
                                  <div class="row">

                                    <div class="col-md-3">
                                      <h5>Kode Booking</h5>
                                    </div>
                                    <div class="col-md-9">
                                      <h5 class="text-uppercase">{{booking.kode_booking}}</h5>
                                    </div>
                                    <div class="col-md-3">
                                      <h5>Nama Pelanggan</h5>
                                    </div>
                                    <div class="col-md-9">
                                      <h5>{{booking.nama}}</h5>
                                    </div>
                                    <div class="col-md-3">
                                      <h5>Email Pelanggan</h5>
                                    </div>
                                    <div class="col-md-9">
                                      <h5>{{booking.email}}</h5>
                                    </div>
                                    <div class="col-md-3">
                                      <h5>No Telepon</h5>
                                    </div>
                                    <div class="col-md-9">
                                      <h5>{{booking.no_telepon}}</h5>
                                    </div>
                                    <div class="col-md-3">
                                      <h5>Alamat Pelanggan</h5>
                                    </div>
                                    <div class="col-md-9">
                                      <h5>{{booking.alamat}}</h5>
                                    </div>
                                  </div>
                                  <hr class="green">
                                  <div class="row">
                                    <div class="col-md-3">
                                      <h5>Tanggal Check-in</h5>
                                    </div>
                                    <div class="col-md-9">
                                      <h5>{{booking.tgl_checkin | myDate}}</h5>
                                    </div>
                                    <div class="col-md-3">
                                      <h5>Tanggal Check-out</h5>
                                    </div>
                                    <div class="col-md-9">
                                      <h5>{{booking.tgl_checkout | myDate}}</h5>
                                    </div>
                                    <div class="col-md-3">
                                      <h5>Jumlah Kamar</h5>
                                    </div>
                                    <div class="col-md-9">
                                      <h5>{{booking.jmlKamar}} Kamar</h5>
                                    </div>

                                      <div class="col-md-3">
                                        <h5>Total</h5>
                                      </div>
                                      <div class="col-md-9">
                                        <h5 class="red">Rp. {{booking.total | currency}}</h5>
                                      </div>
                                  </div>
                                  <hr>
                                  <div class="row">
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
                                            <td><p>Rp. {{kamar.harga | currency}}</p></td>
                                          </tr>
                                        </tbody>

                                    </table>
                                  </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                              <router-link :to="{ name: 'bookings', params: {} }" class="btn btn-danger btn-sm">Kembali</router-link>
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
import Editor from '@tinymce/tinymce-vue';
export default {
    components: {
        'editor': Editor
    },
    data() {
        return {
            editMode: false,
            columns: [
                'no_room', 'tipe', 'kapasitas', 'jml_tamu', 'harga',
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
                },
                sortable: ['no_room', 'tipe', 'kapasitas', 'harga', 'jml_tamu'],
                filterable: ['no_room', 'tipe', 'kapasitas', 'harga', 'jml_tamu'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                },
            },
            booking: [],
            dataKamar: [],
        }
    },
    methods: {
      cetakInvoice(){

        window.open('/booking/invoice/'+this.$route.params.kode_booking, '_blank');
      },
      detilKamar(){
        axios.get('/api/admin/booking/detil/kamar/' + this.$route.params.kode_booking).then(({
            data
        }) => (this.dataKamar = data));
      },
      dataBooking(){
        axios.get('/api/admin/booking/detil/' + this.$route.params.kode_booking).then(({
            data
        }) => (this.booking = data));
      }
    },
    created() {
        this.$Progress.start();
        this.dataBooking();
        this.detilKamar();
        this.$Progress.finish();
    }
}
</script>
