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
                                      <h5>Cash</h5>
                                      <v-client-table :data="dataCash" :columns="columns" :options="options">
                                          <p slot="jml_bayar" slot-scope="{row}" class="float-right red">Rp. {{row.jml_bayar | currency}}</p>
                                      </v-client-table>

                                      <hr>
                                      <h5>Transfer</h5>
                                      <v-client-table :data="dataTransfer" :columns="columnTransfers" :options="optionTransfers">
                                          <p slot="jml_bayar" slot-scope="{row}" class="float-right red">Rp. {{row.jml_bayar | currency}}</p>
                                      </v-client-table>

                                      <hr>
                                      <h5>Credit Card/Debit</h5>
                                      <v-client-table :data="dataCredit" :columns="columnCredits" :options="optionCredits">
                                          <p slot="jml_bayar" slot-scope="{row}" class="float-right red">Rp. {{row.jml_bayar | currency}}</p>
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
            columnCredits: [
                'nama_kartu', 'card_no', 'card_name', 'expired_date', 'jml_bayar', 'name',
            ],
            optionCredits: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    nama_kartu: 'Tipe Kartu',
                    card_no: 'No Kartu',
                    card_name: 'Nama Kartu',
                    expired_date: 'Tgl Kadaluarsa',
                    name: 'Diinputkan oleh',
                },
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                },
            },
            columnTransfers: [
                'bank', 'nama_pemilik_rekening', 'no_rekening', 'tgl_transfer', 'jml_bayar', 'name',
            ],
            optionTransfers: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    bank: 'Metode Pembayaran',
                    tgl_transfer: 'Tanggal Transfer',
                    jml_bayar: 'Jumlah Bayar',
                    name: 'Dikonfirmasi oleh',
                },
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                },
            },
            columns: [
                'created_at', 'jml_bayar', 'name',
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    created_at: 'Tanggal Pembayaran',
                    jml_bayar: 'Jumlah Bayar',
                    name: 'Diterima oleh',
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
            dataCash: [],
            dataTransfer: [],
            dataCredit: [],
        }
    },
    methods: {
      riwayatCredit(){
        axios.get('/api/admin/payment/tagihan/credit/' + this.$route.params.kode_booking).then(({
            data
        }) => (this.dataCredit = data));
      },
      riwayatTransfer(){
        axios.get('/api/admin/payment/tagihan/transfer/' + this.$route.params.kode_booking).then(({
            data
        }) => (this.dataTransfer = data));
      },
      detilBooking(){
        this.$router.push({name: 'detil-booking', params:{kode_booking:this.$route.params.kode_booking}})
      },
      riwayatCash(){
        axios.get('/api/admin/payment/tagihan/cash/' + this.$route.params.kode_booking).then(({
            data
        }) => (this.dataCash = data));
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
        this.riwayatCash();
        this.riwayatCredit();
        this.riwayatTransfer();
        this.$Progress.finish();
    }
}
</script>
