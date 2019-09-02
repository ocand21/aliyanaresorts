<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Reservasi</li>
        <li class="breadcrumb-item active">Detil Inhouse Kamar </li>
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

                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Attention!</h4>
                            <p>All rooms are non smoking. If smooking occurs during your stay, Rp. 500.000 cleaning fee will be charged to your account</p>

                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Detil Inhouse Kamar {{dtlInhouse.no_room}}</strong>
                            </div>
                            <div class="card-body">
                                <form class="" id="formTambah" method="post" enctype="multipart/form-data">

                                    <div class="col-md-12">
                                        <button type="button" @click="modalCharges()" name="button" class="btn btn-sm btn-warning"> <span class="fa fa-plus"></span> Charges </button>
                                    </div>

                                    <hr>
                                    <div class="col-md-12">

                                        <h3>Detil Reservasi</h3>

                                        <div class="form-group">
                                          <label for="">Kode Reservasi</label>
                                          <input type="text" class="form-control text-uppercase" readonly :value="dtlInhouse.kode_booking">
                                        </div>

                                    </div>



                                </form>
                            </div>

                            <div class="card-footer text-right">
                                <!-- <button class="btn btn-sm btn-primary" type="submit">
                                    <i class="fa fa-dot-circle-o"></i> Konfirmasi Checkin</button> -->
                                <router-link to="/admin/inhouse" class="btn btn-sm btn-danger">
                                    <i class="fa fa-dot-circle-o"></i> Kembali</router-link>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="modalRoom" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Kamar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" method="post" id="formCharges">
                    <div class="modal-body">
                      <div class="alert alert-success" role="alert">
                          {{avKamar.msg}}
                      </div>
                      <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th>No Kamar</th>
                                  <th>Tipe Kamar</th>
                                  <th>Kapasitas</th>
                                  <th>Aksi</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="kamar in avKamar.avKamar" :key="kamar.id">
                                  <td>{{kamar.no_room}}</td>
                                  <td>{{kamar.tipe}}</td>
                                  <td>
                                    {{kamar.kapasitas}}
                                  </td>
                                  <td class="text-center"><a href="#" @click="pilihKamar(kamar.no_room)" class="btn btn-warning btn-sm"> <span class="fa fa-check"></span>Pilih</a> </td>
                              </tr>
                          </tbody>
                      </table>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" name="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
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
            formOpsional: new Form({
                id: '',
                no_room: '',
                nama_penghuni: '',
                jml_penghuni: '',
            }),
            formCharge: new Form({
                nama_charge: '',
                jumlah_persen: '',
                jumlah_rupiah: '',
                keterangan: '',
            }),
            form: new Form({
                id_tipe: '',
                nama: '',
                tipe_identitas: '',
                no_identitas: '',
                alamat: '',
                email: '',
                no_telepon: '',
                kode_booking: '',
                tgl_checkin: '',
                tgl_checkout: '',
                jmlKamar: '',
            }),
            dtlInhouse: [],
            avKamar: [],
        }
    },
    methods: {
      detilInhouse(){
        axios.get('/api/admin/inhouse/' + this.$route.params.no_room).then(({
          data
        }) => (this.dtlInhouse = data));
      }
    },
    created() {
        this.detilInhouse();
        Fire.$on('AfterCreate', () => {

        });


    }
}
</script>
