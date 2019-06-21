<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item active">Slideshow</li>
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
                                <strong>Daftar Slideshow</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <a href="#" @click="modal()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Slideshow</a>
                                    <hr>
                                    <v-client-table :data="slideshow" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" @click="hapusSlide(row.id)" href="#"> <span class="fa fa-trash red"></span> Hapus</a>
                                            </div>
                                        </div>
                                        <img slot="foto" slot-scope="{row}" class="img-responsive rounded float-left img-thumbnail" :src="row.foto" alt="" width="250px">
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

    <div class="modal fade bd-example-modal-lg" id="newDepartemen" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode">Tambah Slideshow</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" id="formSlide" @submit.prevent="editMode ? updateDepartemen() : tambahSlide()" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Judul</label>
                            <input type="text" name="judul" class="form-control">
                            <has-error :form="form" field="judul"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Foto</label>
                            <input type="file" name="foto" class="form-control">
                            <has-error :form="form" field="foto"></has-error>
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
                judul: '',
                foto: '',
            }),
            columns: [
                'aksi', 'judul', 'foto',
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {

                    judul: 'Judul',
                    foto: 'Foto',
                },
                sortable: ['judul', 'foto'],
                filterable: ['judul', 'foto'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            slideshow: [],
        }
    },
    methods: {
        hapusSlide(id) {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus slide!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.value) {
                    this.$Progress.start();
                    axios.delete('/api/admin/slideshow/' + id).then(() => {
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
        updateDepartemen() {
            this.form.put('/api/admin/departemen/' + this.form.id)
                .then(() => {
                    this.$Progress.start();
                    swal(
                        'Sukses!',
                        'Departemen berhasil dirubah.',
                        'success'
                    )
                    this.$Progress.finish();
                    this.form.reset();
                    $('#newDepartemen').modal('hide');
                    Fire.$emit('AfterCreate')
                }).catch(() => {
                    swal(
                        'Gagal!',
                        'Terjadi kesalahan',
                        'warning'
                    )
                });
        },
        editDepartemen(departemen) {
            this.editMode = true;
            this.form.reset();
            $('#newDepartemen').modal('show');
            this.form.fill(departemen);
        },
        loadSlide() {
            axios.get('/api/admin/slideshow').then(({
                data
            }) => (this.slideshow = data));
        },
        tambahSlide() {
            var formData = new FormData(document.getElementById("formSlide"));
            let instance = this;
            this.$Progress.start();
            axios.post('/api/admin/slideshow', formData)
                .then(() => {
                    Fire.$emit('AfterCreate');
                    $('#newDepartemen').modal('hide');
                    swal(
                        'Sukses!',
                        'Slideshow berhasil ditambah.',
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
        modal() {
            this.editMode = false,
            this.form.reset();
            $('#newDepartemen').modal('show');
        },
    },
    created() {
        this.$Progress.start();
        this.loadSlide();
        Fire.$on('AfterCreate', () => {
            this.loadSlide('');
        });
        this.$Progress.finish();
    }
}
</script>
