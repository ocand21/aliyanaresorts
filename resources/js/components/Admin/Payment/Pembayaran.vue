<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Payment</li>
        <li class="breadcrumb-item active">Pembayaran</li>
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
                                <strong>Daftar Pembayaran</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                  <hr>
                                    <v-client-table :data="tagihan" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" href="#" @click="cetakInvoice(row.kode_booking)"><span class="fa fa-search info"></span> Detil</a>
                                                <a class="dropdown-item" @click="hapusFasilitas(row.id)" href="#"> <span class="fa fa-trash red"></span> Hapus</a>
                                            </div>
                                        </div>
                                        <p slot="kode_booking" slot-scope="{row}" class="text-uppercase">{{row.kode_booking}}</p>
                                        <p slot="total_tagihan" slot-scope="{row}" class="float-right red">Rp. {{row.total_tagihan | currency}}</p>
                                        <p slot="terbayarkan" slot-scope="{row}" class="float-right red">Rp. {{row.terbayarkan | currency}}</p>

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
import Editor from '@tinymce/tinymce-vue';
export default {
    components: {
        'editor': Editor
    },
    data() {
        return {
            editMode: false,
            columns: [
                'aksi', 'kode_booking', 'nama', 'no_telepon', 'total_tagihan', 'terbayarkan',
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
                    total_tagihan: 'Total Tagihan',
                    terbayarkan: 'Terbayarkan',
                    hutang: 'Balance',
                },
                sortable: ['kode_booking', 'nama', 'no_telepon', 'total_tagihan', 'terbayarkan', 'hutang',],
                filterable: ['kode_booking', 'nama', 'no_telepon', 'total_tagihan', 'terbayarkan', 'hutang',],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            tagihan: [],
        }
    },
    methods: {
      cetakInvoice(kode_booking){
        window.open('/booking/invoice/'+kode_booking, '_blank');
      },
      dataTagihan(){
        axios.get('/api/admin/payment/tagihan').then(({
            data
        }) => (this.tagihan = data));
      }
    },
    created() {
        this.$Progress.start();
        this.dataTagihan();
        this.$Progress.finish();
    }
}
</script>
