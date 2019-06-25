<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Master Data</li>
        <li class="breadcrumb-item active">Menu Resto</li>
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
                                <strong>Daftar Menu Resto</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <router-link to="/admin/master-data/menu-resto/tambah" href="#" @click="modal()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Menu</router-link>
                                    <hr>
                                    <v-client-table :data="menu" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" href="#" @click="detilBooking(row.kode_booking)"><span class="fa fa-search info"></span> Detil</a>
                                                <a class="dropdown-item" @click="hapusMenu(row.id)" href="#"> <span class="red fa fa-trash"></span> Hapus</a>
                                            </div>
                                        </div>
                                        <p slot="harga" slot-scope="{row}">Rp. {{row.harga | currency}}</p>
                                        <img slot="nama_file" slot-scope="{row}" class="img-responsive rounded float-left img-thumbnail" :src="row.nama_file" alt="" width="250px" style="margin: 10px;">
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

    <div class="modal fade bd-example-modal-lg" id="newMenu" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" @submit.prevent="editMode ? updateKamar() : tambahKamar()" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Menu</label>
                            <input type="text" class="form-control" name="menu"></input>
                        </div>
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" class="form-control" name="menu"></input>
                        </div>
                        <div class="form-group">
                            <label for="">Harga</label>
                            <input type="text" class="form-control" name="harga"></input>
                        </div>
                        <div class="form-group">
                            <label for="">Catatan</label>
                            <input type="text" class="form-control" name="catatan"></input>
                        </div>
                        <div class="form-group">
                            <label for="">Foto</label>
                            <input type="file" class="form-control" name="foto"></input>
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
                'aksi', 'menu', 'nama', 'harga', 'catatan',
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    nama: 'Nama',
                    menu: 'Menu',
                    harga: 'Harga',
                    nama_file: 'Foto',
                },
                sortable: ['menu', 'nama', 'harga',],
                filterable: ['menu', 'nama', 'harga',],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            menu: [],
            form: new Form({
              kode_booking: '',
              id_pelanggan: '',
              alasan: '',
              nama: '',
            }),
        }
    },
    methods: {

      hapusMenu(id){
        swal({
            title: 'Anda yakin?',
            text: "Operasi ini tidak dapat dibatalkan!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus menu!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                this.$Progress.start();
                axios.delete('/api/admin/menu-resto/hapus/' + id).then(() => {
                    swal(
                        'Dihapus!',
                        'Data berhasil dihapus.',
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
      dataMenu(){
        axios.get('/api/admin/menu-resto').then(({
            data
        }) => (this.menu = data));
      }
    },
    created() {
        this.$Progress.start();
        this.dataMenu();
        Fire.$on('AfterCreate', () => {
            this.dataMenu('');
        });
        this.$Progress.finish();
    }
}
</script>
