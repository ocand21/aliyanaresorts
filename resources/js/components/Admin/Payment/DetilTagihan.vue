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
                                <strong>Detil Tagihan</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">

                                  <div class="row">
                                    <div class="col-md-12 text-right">
                                      <button type="button" class="btn btn-success btn-sm" name="button" @click="detilBooking()"><i class="fa fa-search"></i> Detil Booking</button>
                                      <button type="button" class="btn btn-success btn-sm" name="button" @click="cetakInvoice()"><i class="fa fa-print"></i> Cetak Invoice</button>
                                    </div>
                                  </div>
                                  <div class="row">

                                    <div class="col-md-3">
                                      <p>Kode Booking</p>
                                    </div>
                                    <div class="col-md-9">
                                      <p class="text-uppercase">{{dataTagihan.kode_booking}}</p>
                                    </div>
                                    <div class="col-md-3">
                                      <p>Nama Pelanggan</p>
                                    </div>
                                    <div class="col-md-9">
                                      <p>{{dataTagihan.nama}}</p>
                                    </div>
                                    <div class="col-md-3">
                                      <p>Email Pelanggan</p>
                                    </div>
                                    <div class="col-md-9">
                                      <p>{{dataTagihan.email}}</p>
                                    </div>
                                    <div class="col-md-3">
                                      <p>No Telepon</p>
                                    </div>
                                    <div class="col-md-9">
                                      <p>{{dataTagihan.no_telepon}}</p>
                                    </div>
                                    <div class="col-md-3">
                                      <p>Alamat Pelanggan</p>
                                    </div>
                                    <div class="col-md-9">
                                      <p>{{dataTagihan.alamat}}</p>
                                    </div>
                                    <div class="col-md-3">
                                      <p>Total Tagihan</p>
                                    </div>
                                    <div class="col-md-9">
                                      <p>Rp. {{dataTagihan.total_tagihan | currency}}</p>
                                    </div>
                                    <div class="col-md-3">
                                      <p>Terbayarkan</p>
                                    </div>
                                    <div class="col-md-9">
                                      <p>Rp. {{dataTagihan.terbayarkan | currency}}</p>
                                    </div>
                                    <div class="col-md-3">
                                      <p>Kekurangan</p>
                                    </div>
                                    <div class="col-md-9">
                                      <p>Rp. {{dataTagihan.hutang | currency}}</p>
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <p>Riwayat Pembayaran</p>

                                      <v-client-table :data="dataPembayaran" :columns="columns" :options="options">

                                          <p slot="jumlah" slot-scope="{row}" class="float-right red">Rp. {{row.jumlah | currency}}</p>

                                      </v-client-table>
                                    </div>

                                  </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                              <router-link :to="{ name: 'tagihan', params: {} }" class="btn btn-danger btn-sm">Kembali</router-link>
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
                'tgl_transfer', 'bank', 'atas_nama', 'jumlah', 'dikonfirmasi',
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    tgl_transfer: 'Tanggal Pembayaran',
                    bank: 'Metode Bayar',
                    atas_nama: 'Atas Nama',
                    jumlah: 'Jumlah',
                    dikonfirmasi: 'Diterima oleh',
                },
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
            dataTagihan: [],
            dataPembayaran: [],
        }
    },
    methods: {
      detilBooking(){
        this.$router.push({name: 'detil-booking', params:{kode_booking:this.$route.params.kode_booking}})
      },
      riwayatPembayaran(){
        axios.get('/api/admin/payment/pembayaran/riwayat/' + this.$route.params.kode_booking).then(({
            data
        }) => (this.dataPembayaran = data));
      },
      detilTagihan(){
        axios.get('/api/admin/payment/tagihan/' + this.$route.params.kode_booking).then(({
            data
        }) => (this.dataTagihan = data));
      },
      cetakInvoice(){
        window.open('/booking/invoice/'+this.$route.params.kode_booking, '_blank');
      },
    },
    created() {
        this.$Progress.start();
        this.detilTagihan();
        this.riwayatPembayaran();
        this.$Progress.finish();
    }
}
</script>
