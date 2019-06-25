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
                                <strong>Tambah Menu</strong>
                            </div>
                            <div class="card-body">
                                <form class="" id="formTambah" method="post" enctype="multipart/form-data">

                                    <div class="col-md-12">
                                        <label for="">Foto</label>
                                        <div class="form-group">
                                            <input type="file" name="foto" class="form-control" multiple>
                                        </div>
                                        <label for="">Menu</label>
                                        <div class="form-group">
                                            <input type="text" name="menu" class="form-control">
                                        </div>
                                        <label for="">Nama</label>
                                        <div class="form-group">
                                            <input type="text" name="nama" class="form-control">
                                        </div>
                                        <label for="">Harga</label>
                                        <div class="form-group">
                                          <input type="text" name="harga" class="form-control">
                                        </div>
                                        <label for="">Catatan</label>
                                        <div class="form-group">
                                            <input type="text" name="catatan" class="form-control">
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-sm btn-primary" @click="tambahMenu" type="submit">
                                    <i class="fa fa-dot-circle-o"></i> Simpan</button>
                                <router-link to="/admin/kamar" class="btn btn-sm btn-danger">
                                    <i class="fa fa-dot-circle-o"></i> Batal</router-link>
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

        }
    },
    methods: {
        tambahMenu() {
            var formData = new FormData(document.getElementById("formTambah"));
            let instance = this;
            axios.post('/api/admin/menu-resto/tambah', formData)
                .then(() => {
                    this.$Progress.start();
                    console.log(formData);
                    Fire.$emit('AfterCreate');
                    swal(
                        'Sukses!',
                        'Data berhasil disimpan.',
                        'success'
                    )
                    this.$Progress.finish();
                    this.$router.push({
                        name: 'menu-resto'
                    });
                })
                .catch(() => {
                    swal(
                        'Gagal!',
                        'Terjadi kesalahan',
                        'warning'
                    )
                })
        }
    },
    created() {

    }
}
</script>
