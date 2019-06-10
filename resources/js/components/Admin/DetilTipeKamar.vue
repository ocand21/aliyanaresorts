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

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Detil Tipe Kamar {{detailTipe.tipe}}</strong>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="col-md-3">
                                  <h5>Tipe Kamar</h5>
                                </div>
                                <div class="col-md-9">
                                  <h5>{{detailTipe.tipe}}</h5>
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-md-3">
                                  <h5>Faslitas Kamar</h5>
                                </div>
                                <div class="col-md-9">
                                  <textarea rows="10" cols="80" class="form-control" readonly>{{detailTipe.fitur}}</textarea>
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-md-3">
                                  <h5>Deskripsi Kamar</h5>
                                </div>
                                <div class="col-md-9">
                                  <h5>{{detailTipe.deskripsi}}</h5>
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-md-3">
                                  <h5>Kapasitas Kamar</h5>
                                </div>
                                <div class="col-md-9">
                                  <h5>{{detailTipe.kapasitas}} Person</h5>
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-md-3">
                                  <h5>Harga Kamar</h5>
                                </div>
                                <div class="col-md-9">
                                  <h5>Rp. {{detailTipe.harga | currency}}</h5>
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-md-12">
                                  <h5>Foto Kamar</h5>
                                </div>
                                <div class="col-md-4" v-for="foto in fotoKamar" :key="foto.id">
                                  <img class="img-responsive rounded float-left img-thumbnail" :src="foto.lokasi" alt="" width="250px" style="margin: 10px;">
                                </div>
                              </div>
                            </div>
                            <div class="card-footer text-right">
                                <router-link to="/admin/kamar" class="btn btn-sm btn-success">
                                    <i class="fa fa-dot-circle-o"></i> Kembali</router-link>
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
          detailTipe: [],
          fotoKamar: [],
        }
    },
    methods: {
      loadFoto() {
          axios.get('/api/admin/tipe-kamar/foto/' + this.$route.params.id).then(({
              data
          }) => (this.fotoKamar = data));
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
    }
}
</script>
