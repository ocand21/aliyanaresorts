<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item active">Konfigurasi</li>
        <!-- Breadcrumb Menu-->
        <li class="breadcrumb-menu d-md-down-none">
            <div class="btn-group" role="group" aria-label="Button group">
                <a class="btn" href="#">
                    <i class="icon-speech"></i>
                </a>
                <a class="btn" href="./">
                    <i class="icon-graph"></i>  Dashboard</a>
                <a class="btn" href="#">
                    <i class="icon-settings"></i>  Settings</a>
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
                                <strong>Konfigurasi Website</strong>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <form class="" method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Nama Hotel</label>
                                                <div class="form-group">
                                                    <input type="hidden" name="id">
                                                    <input type="text" name="hotel_name" class="form-control" v-model="form.hotel_name" :class="{'is-invalid' : form.errors.has('form.hotel_name')}">
                                                    <has-error :form="form" field="hotel_name"></has-error>
                                                </div>
                                                <label for="">Website</label>
                                                <div class="form-group">
                                                    <input type="text" name="web" class="form-control" v-model="form.web" :class="{'is-invalid' : form.errors.has('form.web')}">
                                                    <has-error :form="form" field="web"></has-error>
                                                </div>
                                                <label for="">Email 1</label>
                                                <div class="form-group">
                                                    <input type="text" name="mail1" class="form-control" v-model="form.mail1" :class="{'is-invalid' : form.errors.has('form.mail1')}">
                                                    <has-error :form="form" field="mail1"></has-error>
                                                </div>
                                                <label for="">Telepon</label>
                                                <div class="form-group">
                                                    <input type="text" name="phone" class="form-control" v-model="form.phone" :class="{'is-invalid' : form.errors.has('form.phone')}">
                                                    <has-error :form="form" field="phone"></has-error>
                                                </div>
                                                <label for="">FAX</label>
                                                <div class="form-group">
                                                    <input type="text" name="fax" class="form-control" v-model="form.fax" :class="{'is-invalid' : form.errors.has('form.fax')}">
                                                    <has-error :form="form" field="fax"></has-error>
                                                </div>
                                                <label for="">Visi</label>
                                                <div class="form-group">
                                                    <editor api-key="44nsbdus84r0ju13jm2ng5cpchi6ga081o9mku2kxxze6bgk" :init="{plugins: 'wordcount'}" v-model="form.visi" :class="{'is-invalid' : form.errors.has('form.visi')}"></editor>
                                                    <has-error :form="form" field="visi"></has-error>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Alamat Hotel</label>
                                                <div class="form-group">
                                                    <input type="text" name="alamat" class="form-control" v-model="form.alamat" :class="{'is-invalid' : form.errors.has('form.alamat')}">
                                                    <has-error :form="form" field="alamat"></has-error>
                                                </div>
                                                <label for="">Email 2</label>
                                                <div class="form-group">
                                                    <input type="text" name="mail2" class="form-control" v-model="form.mail2" :class="{'is-invalid' : form.errors.has('form.mail2')}">
                                                    <has-error :form="form" field="mail2"></has-error>
                                                </div>
                                                <label for="">URL Instagram</label>
                                                <div class="form-group">
                                                    <input type="text" name="instagram" class="form-control" v-model="form.instagram" :class="{'is-invalid' : form.errors.has('form.instagram')}">
                                                    <has-error :form="form" field="instagram"></has-error>
                                                </div>
                                                <label for="">URL Facebook</label>
                                                <div class="form-group">
                                                    <input type="text" name="facebook" class="form-control" v-model="form.facebook" :class="{'is-invalid' : form.errors.has('form.facebook')}">
                                                    <has-error :form="form" field="facebook"></has-error>
                                                </div>
                                                <label for="">Whatsapp</label>
                                                <div class="form-group">
                                                    <input type="text" name="whatsapp" class="form-control" v-model="form.whatsapp" :class="{'is-invalid' : form.errors.has('form.whatsapp')}">
                                                    <has-error :form="form" field="whatsapp"></has-error>
                                                </div>
                                                <label for="">Misi</label>
                                                <div class="form-group">
                                                    <editor api-key="44nsbdus84r0ju13jm2ng5cpchi6ga081o9mku2kxxze6bgk" :init="{plugins: 'wordcount'}" v-model="form.misi" :class="{'is-invalid' : form.errors.has('form.misi')}"></editor>
                                                    <has-error :form="form" field="misi"></has-error>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Tentang Hotel</label>
                                                <div class="form-group">
                                                    <editor api-key="44nsbdus84r0ju13jm2ng5cpchi6ga081o9mku2kxxze6bgk" :init="{plugins: 'wordcount'}" v-model="form.tentang" :class="{'is-invalid' : form.errors.has('form.tentang')}"></editor>
                                                    <has-error :form="form" field="tentang"></has-error>
                                                </div>
                                            </div>
                                        </div>


                                    </form>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-sm btn-primary" @click="createKonfig()" type="submit">
                                    <i class="fa fa-dot-circle-o"></i> Simpan</button>
                                <button class="btn btn-sm btn-danger" type="submit">
                                    <i class="fa fa-dot-circle-o"></i> Batal</button>
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
            form: new Form({
                hotel_name: '',
                alamat: '',
                web: '',
                mail1: '',
                mail2: '',
                instagram: '',
                facebook: '',
                phone: '',
                fax: '',
                whatsapp: '',
                visi: '',
                misi: '',
                tentang: '',
            }),
        }
    },
    methods: {
        createKonfig() {
            this.$Progress.start();
            this.form.post('/api/admin/konfigurasi-web')
                .then(() => {
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide');
                    swal(
                        'Sukses!',
                        'Data berhasil disimpan.',
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
        loadKonfig() {
            axios.get('/api/admin/konfigurasi-web').then(({
                data
            }) => (this.form.fill(data)));
        }
    },
    created() {
        this.loadKonfig();
    }
}
</script>
