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
                                <strong>Fasilitas</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h5>Nama Fasilitas</h5>
                                        </div>
                                        <div class="col-md-9">
                                            <h5>{{fasilitas.nama}}</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <h5>Foto Fasiltias</h5>
                                      </div>
                                      <div class="col-md-4" v-for="foto in fotoFasilitas" :key="foto.id">
                                        <img class="img-responsive rounded float-left img-thumbnail" :src="foto.lokasi" alt="" width="250px" style="margin: 10px;">
                                      </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-right">
                              <router-link :to="{ name: 'fasilitas'}" href="#" class="btn btn-warning">Kembali</router-link>
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
            fasilitas: [],
            fotoFasilitas: [],
        }
    },
    methods: {
        loadFoto() {
            axios.get('/api/admin/fasilitas/foto/' + this.$route.params.id).then(({
                data
            }) => (this.fotoFasilitas = data));
        },
        detilTipe(id) {
            this.$router.push({
                name: 'detil-tipe',
                params: {
                    id: id
                }
            })
        },
        loadFasilitas() {
            axios.get('/api/admin/fasilitas/' + this.$route.params.id).then(({
                data
            }) => (this.fasilitas = data));
        },
    },
    created() {
        this.$Progress.start();
        this.loadFasilitas();
        this.loadFoto();
        Fire.$on('AfterCreate', () => {
            this.loadFasilitas('');
        });
        this.$Progress.finish();
    }
}
</script>
