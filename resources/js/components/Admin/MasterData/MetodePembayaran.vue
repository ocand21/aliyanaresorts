<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Master Data</li>
        <li class="breadcrumb-item active">Metode Pembayaran</li>
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
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <strong>Daftar Rekening Pembayaran</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <a to="/admin/booking/room" href="#" @click="modalBaru()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Rekening</a>
                                    <hr>
                                    <v-client-table :data="metode" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" href="#" @click="editModal(row)"><span class="fa fa-edit yellow"></span> Edit</a>
                                                <a class="dropdown-item" @click="hapusMetode(row.id)" href="#"> <span class="fa fa-trash red"></span> Hapus</a>
                                            </div>
                                        </div>
                                    </v-client-table>
                                </div>
                            </div>
                            <div class="card-footer text-right">

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <strong>Daftar Kartu Debit/Kredit</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <a to="/admin/booking/room" href="#" @click="modalKartuBaru()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Kartu</a>
                                    <hr>
                                    <v-client-table :data="masterKartu" :columns="columnsCard" :options="optionsCard">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" href="#" @click="editModal(row)"><span class="fa fa-edit yellow"></span> Edit</a>
                                                <a class="dropdown-item" @click="hapusMetode(row.id)" href="#"> <span class="fa fa-trash red"></span> Hapus</a>
                                            </div>
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

    <div class="modal fade bd-example-modal-lg" id="modalTambahKartu" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!editMode" class="modal-title">Tambah Metode Pembayaran</h5>
                    <h5 v-show="editMode" class="modal-title">Ubah Metode Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" @submit.prevent="editMode ? updateKartu() : tambahKartu()" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nama Kartu</label>
                            <input type="text" class="form-control" v-model="formKartu.nama_kartu" :class="{'is-invalid' : formKartu.errors.has('formKartu.nama_kartu')}" name="nama_kartu">
                            <has-error :form="formKartu" field="nama_kartu"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Deksripsi</label>
                            <input type="text" class="form-control" v-model="formKartu.deskripsi" :class="{'is-invalid' : formKartu.errors.has('formKartu.deskripsi')}" name="deskripsi">
                            <has-error :form="formKartu" field="deskripsi"></has-error>
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

    <div class="modal fade bd-example-modal-lg" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!editMode" class="modal-title">Tambah Metode Pembayaran</h5>
                    <h5 v-show="editMode" class="modal-title">Ubah Metode Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" @submit.prevent="editMode ? updateMetode() : tambahMetode()" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Bank</label>
                            <input type="text" class="form-control" v-model="form.bank" :class="{'is-invalid' : form.errors.has('form.bank')}" name="bank">
                            <has-error :form="form" field="bank"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Nomor Rekening</label>
                            <input type="text" v-model="form.no_rekening" :class="{'is-invalid' : form.errors.has('form.no_rekening')}" class="form-control"></input>
                            <has-error :form="form" field="no_rekening"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Atas Nama</label>
                            <input type="text" v-model="form.atas_nama" :class="{'is-invalid' : form.errors.has('form.atas_nama')}" class="form-control"></input>
                            <has-error :form="form" field="atas_nama"></has-error>
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
            columnsCard: [
                'aksi', 'nama_kartu', 'deskripsi'
            ],
            optionsCard: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    nama_kartu: 'Nama Kartu',
                    deskripsi: 'Deksripsi',
                },
                sortable: ['bank', 'nama_kartu', 'deskripsi',],
                filterable: ['bank', 'nama_kartu', 'deskripsi',],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            columns: [
                'aksi', 'bank', 'no_rekening', 'atas_nama',
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    no_rekening: 'No Rekening',
                    bank: 'Bank',
                    atas_nama: 'Atas Nama',
                },
                sortable: ['bank', 'no_rekening', 'atas_nama',],
                filterable: ['bank', 'no_rekening', 'atas_nama',],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            metode: [],
            formKartu: new Form({
              id: '',
              nama_kartu: '',
              deskripsi: '',
            }),
            form: new Form({
              id: '',
              bank: '',
              no_rekening: '',
              atas_nama: '',
            }),
            masterKartu: [],
        }
    },
    methods: {
      tambahKartu(){
        this.$Progress.start();
        this.formKartu.post('/api/admin/master-kartu')
            .then(() => {
                Fire.$emit('AfterCreate');
                this.formKartu.reset();
                $('#modalTambahKartu').modal('hide');
                swal(
                    'Sukses!',
                    'Kartu berhasil ditambah.',
                    'success'
                )
                this.$Progress.finish();
            })
            .catch(() => {
                swal(
                    'Gagal!',
                    'Terjadi kesalahan',
                    'warning'
                )
            })
      },
      loadKartu(){
        axios.get('/api/admin/master-kartu').then(({
            data
        }) => (this.masterKartu = data));
      },
      hapusMetode(id){
        swal({
            title: 'Anda yakin?',
            text: "Operasi ini tidak dapat dibatalkan!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus metode!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                this.$Progress.start();
                this.form.delete('/api/admin/metode-pembayaran/' + id).then(() => {
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
      updateMetode(){

        this.$Progress.start();
        this.form.put('/api/admin/metode-pembayaran/' + this.form.id)
            .then(() => {
                $('#modalTambah').modal('hide');
                swal(
                    'Sukses!',
                    'Metode berhasil dirubah.',
                    'success'
                )
                this.$Progress.finish();
                Fire.$emit('AfterCreate')
            }).catch(() => {
                swal(
                    'Gagal!',
                    'Terjadi kesalahan',
                    'warning'
                )
            });
      },
      editModal(metode){
        this.form.reset();
        this.editMode = true;
        $('#modalTambah').modal('show');
        this.form.fill(metode);
      },
      modalBaru(){
        this.form.reset();
        $('#modalTambah').modal('show');
      },
      modalKartuBaru(){
        this.formKartu.reset();
        $('#modalTambahKartu').modal('show');
      },
      tambahMetode(){
        this.$Progress.start();
        this.form.post('/api/admin/metode-pembayaran')
            .then(() => {
                Fire.$emit('AfterCreate');
                this.form.reset();
                $('#modalTambah').modal('hide');
                swal(
                    'Sukses!',
                    'Metode berhasil ditambah.',
                    'success'
                )
                this.$Progress.finish();
            })
            .catch(() => {
                swal(
                    'Gagal!',
                    'Terjadi kesalahan',
                    'warning'
                )
            })
      },
      dataMetode(){
        axios.get('/api/admin/metode-pembayaran').then(({
            data
        }) => (this.metode = data));
      }
    },
    created() {
        this.$Progress.start();
        this.dataMetode();
        this.loadKartu();
        Fire.$on('AfterCreate', () => {
            this.dataMetode('');
            this.loadKartu();
        });
        this.$Progress.finish();
    }
}
</script>
