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
                                <strong>Daftar Staff</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <a href="#" @click="modal()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Staff</a>
                                    <hr>
                                    <v-client-table :data="staff" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" href="#" @click="editStaff(row)"><span class="fa fa-edit blue"></span> Edit</a>
                                                <a class="dropdown-item" @click="hapusStaff(row.id)" href="#"> <span class="fa fa-trash red"></span> Hapus</a>
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

    <div class="modal fade bd-example-modal-lg" id="newStaff" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode">Tambah Staff</h5>
                    <h5 class="modal-title" v-show="editMode">Ubah Hak Akses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" id="formFasilitas" @submit.prevent="editMode ? updateStaff() : tambahStaff()" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group" v-show="!editMode">
                            <label for="">Nama Lengkap</label>
                            <input type="text" name="name" v-model="form.name" :class="{'is-invalid' : form.errors.has('form.name')}" class="form-control">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group" v-show="!editMode">
                            <label for="">Email</label>
                            <input type="text" name="email" v-model="form.email" :class="{'is-invalid' : form.errors.has('form.email')}" class="form-control">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group" v-show="!editMode">
                            <label for="">Alamat</label>
                            <textarea type="text" name="address" v-model="form.address" :class="{'is-invalid' : form.errors.has('form.address')}" class="form-control"></textarea>
                            <has-error :form="form" field="address"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Departemen</label>
                            <select class="form-control" name="kode_departemen" v-model="form.kode_departemen" :class="{'is-invalid' : form.errors.has('form.kode_departemen')}">
                              <option v-for="dep in departemen" :key="dep.id" :value="dep.kode_departemen">{{dep.nama_departemen}}</option>
                            </select>
                            <has-error :form="form" field="kode_departemen"></has-error>
                        </div>
                        <div class="form-group" v-show="!editMode">
                            <label for="">Password</label>
                            <input type="password" name="password" v-model="form.password" :class="{'is-invalid' : form.errors.has('form.password')}" class="form-control">
                            <has-error :form="form" field="password"></has-error>
                        </div>
                        <div class="form-group" v-show="!editMode">
                            <label for="">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" v-model="form.password_confirmation" :class="{'is-invalid' : form.errors.has('form.password_confirmation')}" class="form-control">
                            <has-error :form="form" field="password_confirmation"></has-error>
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
              name: '',
              email: '',
              kode_departemen: '',
              address: '',
              password: '',
              password_confirmation: '',
            }),
            columns: [
                'aksi', 'name', 'email', 'nama_departemen'
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    name: 'Nama',
                    address: 'Alamat',
                    email: 'Email',
                    nama_departemen: 'Nama Departemen',
                },
                sortable: ['name', 'email', 'address', 'nama_departemen'],
                filterable: ['name', 'email', 'address', 'nama_departemen'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            staff: [],
            departemen: [],
        }
    },
    methods: {
        loadDepartemen(){
          axios.get("/api/admin/departemen").then(({
            data
          }) => (this.departemen = data));
        },
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
        hapusStaff(id) {
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
                    axios.delete('/api/admin/staff/' + id).then(() => {
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
        updateStaff() {
            this.$Progress.start();
            this.form.put('/api/admin/staff/' + this.form.id)
                .then(() => {
                    $('#newStaff').modal('hide');
                    this.form.reset();
                    swal(
                        'Sukses!',
                        'Staff berhasil dirubah.',
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
        editStaff(staff) {
            this.editMode = true;
            this.form.reset();
            $('#newStaff').modal('show');
            this.loadDepartemen();
            this.form.fill(staff);
        },
        loadStaff() {
            axios.get('/api/admin/staff').then(({
                data
            }) => (this.staff = data));
        },
        tambahStaff() {
            this.$Progress.start();
            this.form.post('/api/admin/staff')
                .then(() => {
                    Fire.$emit('AfterCreate');
                    $('#newStaff').modal('hide');
                    swal(
                        'Sukses!',
                        'Staff berhasil ditambah.',
                        'success'
                    )
                    this.$Progress.finish();
                    this.form.reset();
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
            $('#newStaff').modal('show');
            this.form.reset();
            this.loadDepartemen();
        },
    },
    created() {
        this.$Progress.start();
        this.loadStaff();
        this.loadKamar();
        Fire.$on('AfterCreate', () => {
            this.loadStaff('');
            this.loadKamar();
        });
        this.$Progress.finish();
    }
}
</script>
