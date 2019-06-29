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
                                    <v-client-table :data="pembayaran" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" @click="konfirmasiPembayaran(row.id)" href="#"> <span class="fa fa-check green"></span> Konfirmasi</a>
                                                <a class="dropdown-item" href="#" @click="cetakInvoice(row.kode_booking)"><span class="fa fa-search info"></span> Detil</a>
                                            </div>
                                        </div>
                                        <p slot="kode_booking" slot-scope="{row}" class="text-uppercase">{{row.kode_booking}}</p>
                                        <p slot="jumlah" slot-scope="{row}" class="float-right red">Rp. {{row.jumlah | currency}}</p>
                                        <p slot="tgl_transfer" slot-scope="{row}" class="float-right red">{{row.tgl_transfer | myDate}}</p>

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
                'aksi', 'kode_booking', 'tgl_transfer', 'nama', 'no_rekening', 'atas_nama', 'bank', 'no_rekening_tujuan', 'jumlah', 'status'
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    nama: 'Nama Pemilik Rek',
                    tgl_transfer: 'Tgl Transfer',
                    kode_booking: 'Kode Booking',
                    no_rekening: 'No Rekening',
                    bank: 'Bank Tujuan',
                    atas_nama: 'Rekening Tujuan',
                    no_rekening_tujuan: 'No Rekening Tujuan',
                    jumlah: 'Nominal',
                },
                sortable: ['kode_booking', 'bank','nama', 'no_rekening', 'atas_nama', 'no_rekening_tujuan', 'jumlah',],
                filterable: ['kode_booking','bank', 'nama', 'no_rekening', 'atas_nama', 'no_rekening_tujuan', 'jumlah',],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            pembayaran: [],
        }
    },
    methods: {
      konfirmasiPembayaran(id){
        swal({
            title: 'Anda yakin?',
            text: "Operasi ini tidak dapat dibatalkan!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, konfirmasi pembayaran!',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.value) {
                this.$Progress.start();
                axios.post('/api/admin/payment/pembayaran/konfirm/' + id).then(() => {
                    swal(
                        'Dikonfirmasi!',
                        'Pembayaran dikonfirmasi.',
                        'success'
                    )
                    Fire.$emit('AfterCreate')
                    this.$Progress.finish();
                }).catch(() => {
                    swal("Gagal!", "Terjadi kesalahan.",
                        "warning");
                });
            }

        })
      },
      cetakInvoice(kode_booking){
        window.open('/booking/invoice/'+kode_booking, '_blank');
      },
      dataPembayaran(){
        axios.get('/api/admin/payment/pembayaran').then(({
            data
        }) => (this.pembayaran = data));
      }
    },
    created() {
        this.$Progress.start();
        this.dataPembayaran();
        Fire.$on('AfterCreate', () => {
            this.dataPembayaran();
        });
        this.$Progress.finish();
    }
}
</script>
