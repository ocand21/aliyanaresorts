<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item active">Fasilitas</li>
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
                                <strong>Daftar Fasilitas</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <a href="#" @click="modal()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Fasilitas</a>
                                    <hr>
                                    <v-client-table :data="fasilitas" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" href="#" @click="detilFasilitas(row.id)"><span class="fa fa-search info"></span> Detil</a>
                                                <a class="dropdown-item" @click="hapusFasilitas(row.id)" href="#"> <span class="fa fa-trash red"></span> Hapus</a>
                                            </div>
                                        </div>
                                        <img slot="lokasi" slot-scope="{row}" class="img-responsive rounded float-left img-thumbnail" :src="row.lokasi" alt="" width="250px">
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
                    <h5 class="modal-title">Tambah Fasilitas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" id="formFasilitas" @submit.prevent="editMode ? updateKamar() : tambahFasilitas()" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Foto</label>
                            <input type="file" name="foto[]" class="form-control" multiple>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Fasilitas</label>
                            <input type="text" name="nama" class="form-control">
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
                'aksi', 'nama', 'lokasi',
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {

                    nama: 'Fasilitas',
                    lokasi: 'Foto',
                },
                sortable: ['nama'],
                filterable: ['nama'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                    hapus: 'text-center',
                    detil: 'text-center',
                    lokasi: 'text-center',
                },
            },
            fasilitas: [],
        }
    },
    methods: {
        detilFasilitas(id) {
            this.$router.push({
                name: 'detil-fasilitas',
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
        hapusFasilitas(id) {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus fasilitas!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.value) {
                    axios.delete('/api/admin/fasilitas/' + id).then(() => {
                        swal(
                            'Dihapus!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                        Fire.$emit('AfterCreate')
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
                    // Fire.$emit('AfterCreate')
                    this.$router.push({
                        name: 'fasilitas'
                    });
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
        loadFasilitas() {
            axios.get('/api/admin/fasilitas').then(({
                data
            }) => (this.fasilitas = data));
        },
        tambahFasilitas() {
            var formData = new FormData(document.getElementById("formFasilitas"));
            let instance = this;
            this.$Progress.start();
            axios.post('/api/admin/fasilitas', formData)
                .then(() => {
                    Fire.$emit('AfterCreate');
                    $('#newKamar').modal('hide');
                    swal(
                        'Sukses!',
                        'Fasilitas berhasil ditambah.',
                        'success'
                    )
                    this.$Progress.finish();
                    document.getElementById("formFasilitas").reset();
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
        this.loadFasilitas();
        this.loadKamar();
        Fire.$on('AfterCreate', () => {
            this.loadFasilitas('');
            this.loadKamar();
        });
        this.$Progress.finish();
    }
}
</script>
