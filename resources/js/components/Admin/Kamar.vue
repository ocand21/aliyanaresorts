<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item active">Kamar</li>
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
                                <strong>Tipe Kamar</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <router-link to="/admin/kamar/tipe/tambah" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Tipe</router-link>
                                    <hr>
                                    <v-client-table :data="TipeKamar" :columns="columns" :options="options">
                                        <a slot="edit" slot-scope="{row}" href="#" @click="editTipe(row.id)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        <a slot="hapus" slot-scope="{row}" href="#" @click="hapusTipe(row.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                        <a slot="detil" slot-scope="{row}" href="#" @click="detilTipe(row.id)">
                                            <i class="fa fa-search green"></i>
                                        </a>
                                        <p slot="fitur" class="red" slot-scope="{row}" v-html="row.fitur"></p>
                                        <p slot="harga" class="red" slot-scope="{row}">Rp. {{row.harga | currency}}</p>
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
                                <strong>Daftar Kamar</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <a href="#" @click="modal()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Kamar</a>
                                    <hr>
                                    <v-client-table :data="dataKamar" :columns="columnsKamar" :options="optionsKamar">
                                        <a slot="edit" slot-scope="{row}" href="#" @click="editKamar(row)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        <a slot="hapus" slot-scope="{row}" href="#" @click="hapusKamar(row.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>

                                        <span slot="status" slot-scope="{row}" class="badge badge-info text-uppercase">{{row.status}}</span>
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

    <div class="modal fade bd-example-modal-lg" id="newKamar" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kamar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" @submit.prevent="editMode ? updateKamar() : tambahKamar()" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Tipe</label>
                            <select class="form-control" v-model="form.id_tipe" name="id_tipe" :class="{'is-invalid' : form.errors.has('form.id_tipe')}">
                                <option v-for="tipe in TipeKamar" :value="tipe.id">{{tipe.tipe}}</option>
                            </select>
                            <has-error :form="form" field="id_tipe"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">No Kamar</label>
                            <input type="text" v-model="form.no_room" :class="{'is-invalid' : form.errors.has('form.no_room')}" class="form-control"></input>
                            <has-error :form="form" field="no_room"></has-error>
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
            form: new Form({
                id: '',
                id_tipe: '',
                no_room: '',
            }),
            TipeKamar: [],
            columnsKamar: [
                'edit', 'hapus', 'tipe', 'no_room', 'status'
            ],
            optionsKamar: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    edit: 'Edit',
                    hapus: 'Hapus',
                    tipe: 'Tipe',
                    no_room: 'No Kamar',
                    status: 'Status',
                },
                sortable: ['tipe', 'no_room', 'status'],
                filterable: ['tipe', 'no_room', 'status'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    edit: 'text-center',
                    hapus: 'text-center',
                    detil: 'text-center'
                },
            },
            columns: [
                'edit', 'hapus', 'detil', 'tipe', 'kapasitas', 'harga'
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    edit: 'Edit',
                    hapus: 'Hapus',
                    tipe: 'Tipe',
                    fitur: 'Fitur',
                },
                sortable: ['tipe', 'fitur', 'kapasitas', 'harga'],
                filterable: ['tipe', 'fitur', 'kapasitas', 'harga'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    edit: 'text-center',
                    hapus: 'text-center',
                    detil: 'text-center'
                },
            },
            dataKamar: [],
        }
    },
    methods: {
        hapusTipe(id) {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus kamar!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.value) {
                    this.$Progress.start();
                    this.form.delete('/api/admin/tipe-kamar/' + id).then(() => {
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
        detilTipe(id) {
            this.$router.push({
                name: 'detil-tipe',
                params: {
                    id: id
                }
            })
        },
        loadKamar() {
            axios.get('/api/admin/kamar').then(({
                data
            }) => (this.dataKamar = data));
        },
        hapusKamar(id) {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus kamar!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.value) {
                    this.$Progress.start();
                    this.form.delete('/api/admin/kamar/' + id).then(() => {
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
        updateKamar() {
            this.$Progress.start();
            this.form.put('/api/admin/kamar/' + this.form.id)
                .then(() => {
                    $('#newKamar').modal('hide');
                    swal(
                        'Sukses!',
                        'Tipe berhasil dirubah.',
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
        editKamar(TipeKamar) {
            this.editMode = true;
            this.form.reset();
            $('#newKamar').modal('show');
            this.form.fill(TipeKamar);
        },
        editTipe(id) {
            this.$router.push({
                name: 'edit-tipe',
                params: {
                    id: id
                }
            })
        },
        loadTipe() {
            axios.get('/api/admin/tipe-kamar').then(({
                data
            }) => (this.TipeKamar = data));
        },
        tambahKamar() {
            this.$Progress.start();
            this.form.post('/api/admin/kamar')
                .then(() => {
                    Fire.$emit('AfterCreate');
                    this.form.reset();
                    $('#newKamar').modal('hide');
                    swal(
                        'Sukses!',
                        'Kamar berhasil ditambah.',
                        'success'
                    )
                    this.$Progress.finish();
                    this.$router.push({
                        name: 'kamar'
                    });
                })
                .catch(() => {
                    swal(
                        'Gagal!',
                        'Terjadi kesalahan',
                        'warning'
                    )
                })
        },
        modal() {
            $('#newKamar').modal('show');
        },
    },
    created() {
        this.$Progress.start();
        this.loadTipe();
        this.loadKamar();
        Fire.$on('AfterCreate', () => {
            this.loadTipe('');
            this.loadKamar();
        });
        this.$Progress.finish();
    }
}
</script>
