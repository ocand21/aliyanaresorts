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
                                <strong>Edit {{detailTipe.tipe}}</strong>
                            </div>
                            <form method="POST" enctype="multipart/form-data" @submit.prevent="updateTipe" id="FormTambah">
                                <div class="card-body">

                                    <div class="col-md-12">

                                        <label for="">Tipe</label>
                                        <div class="form-group">
                                            <input :value="detailTipe.tipe" type="text" name="tipe" class="form-control">
                                        </div>
                                        <label for="">Fitur</label>
                                        <div class="form-group">
                                            <textarea :value="detailTipe.fitur" name="fitur" class="form-control" rows="6" cols="80"></textarea>
                                        </div>
                                        <label for="">Deskripsi</label>
                                        <div class="form-group">
                                            <textarea :value="detailTipe.deskripsi" name="deskripsi" class="form-control" rows="6" cols="80"></textarea>
                                        </div>
                                        <label for="">Kapasitas</label>
                                        <div class="form-group">
                                            <input :value="detailTipe.kapasitas" type="text" name="kapasitas" class="form-control">
                                        </div>
                                        <label for="">Harga</label>
                                        <div class="form-group">
                                            <input :value="detailTipe.harga" type="text" name="harga" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-sm btn-primary" type="submit">
                                        <i class="fa fa-dot-circle-o"></i> Simpan</button>
                                    <router-link to="/admin/kamar" class="btn btn-sm btn-danger">
                                        <i class="fa fa-dot-circle-o"></i> Batal</router-link>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <strong>Foto Kamar Tipe {{detailTipe.tipe}}</strong>
                            </div>
                            <div class="card-body">
                                <a href="#" @click="uploadModal()" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> Upload Foto </a>
                                <table class="table table-bordered table-hover" style="margin-top: 10px">
                                    <thead>
                                        <tr>
                                            <th>Foto</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="foto in fotoKamar" :key="foto.id">
                                            <td class="text-center"><img class="img-responsive" :src="foto.lokasi" alt="" width="200px"></td>
                                            <td class="text-center"><a @click="hapusFoto(foto.id)" href="#" class="btn btn-light btn-sm"><span class="fa fa-trash red"></span> Hapus</a> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-right">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="modalUpload" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" id="formUpload" @submit.prevent="uploadFoto()" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Foto</label>
                            <input type="file" name="foto[]" class="form-control" multiple>
                            <label for="" class="red">Maksimal 5 foto</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
            detailTipe: [],
            fotoKamar: [],
        }
    },
    methods: {
        uploadFoto() {
            var formData = new FormData(document.getElementById("formUpload"));
            let instance = this;
            axios.post('/api/admin/tipe-kamar/foto/upload/' + this.$route.params.id, formData)
                .then(() => {
                    this.$Progress.start();
                    console.log(formData);
                    swal(
                        'Sukses!',
                        'Foto berhasil diupload.',
                        'success'
                    )
                    this.$Progress.finish();
                    $('#modalUpload').modal('hide');
                    Fire.$emit('AfterCreate');
                }).catch(() => {
                    swal(
                        'Gagal!',
                        'Terjadi kesalahan',
                        'warning'
                    )
                });
        },
        uploadModal() {
            $('#modalUpload').modal('show');
        },
        hapusFoto(id) {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus foto!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.value) {
                    axios.delete('/api/admin/tipe-kamar/foto/hapus/' + id).then(() => {
                        swal(
                            'Dihapus!',
                            'Foto berhasil dihapus.',
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
        loadFoto() {
            axios.get('/api/admin/tipe-kamar/foto/' + this.$route.params.id).then(({
                data
            }) => (this.fotoKamar = data));
        },
        updateTipe() {
            var formData = new FormData(document.getElementById("FormTambah"));
            let instance = this;
            axios.post('/api/admin/tipe-kamar/edit/' + this.$route.params.id, formData)
                .then(() => {
                    this.$Progress.start();
                    console.log(formData);
                    swal(
                        'Sukses!',
                        'Tipe berhasil dirubah.',
                        'success'
                    )
                    this.$Progress.finish();
                    this.$router.push({
                        name: 'kamar'
                    });
                }).catch(() => {
                    swal(
                        'Gagal!',
                        'Terjadi kesalahan',
                        'warning'
                    )
                });
        },
        loadDetail() {
            axios.get('/api/admin/tipe-kamar/' + this.$route.params.id).then(({
                data
            }) => (this.detailTipe = data));
        },
    },
    created() {
        this.loadDetail();
        this.loadFoto();
        Fire.$on('AfterCreate', () => {
            this.loadFoto('');
        });
    }
}
</script>
