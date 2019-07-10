<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Master Data</li>
        <li class="breadcrumb-item active">Charges (Denda)</li>
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
                                <strong>Daftar Charges (Denda)</strong>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <a to="/admin/booking/room" href="#" @click="modalBaru()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Charges (Denda)</a>
                                    <hr>
                                    <v-client-table :data="charges" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}" class="btn-group show">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Aksi</button>
                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -187px, 0px);">
                                                <a class="dropdown-item" href="#" @click="editModal(row)"><span class="fa fa-edit yellow"></span> Edit</a>
                                                <a class="dropdown-item" @click="hapusMetode(row.id)" href="#"> <span class="fa fa-trash red"></span> Hapus</a>
                                            </div>

                                        </div>
                                        <p slot="jumlah_persen" slot-scope="{row}" class="text-right">{{row.jumlah_persen}}%</p>
                                        <p slot="jumlah_rupiah" slot-scope="{row}" class="text-right">Rp. {{row.jumlah_rupiah | currency}}</p>
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

    <div class="modal fade bd-example-modal-lg" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!editMode" class="modal-title">Tambah Charges</h5>
                    <h5 v-show="editMode" class="modal-title">Ubah Charges</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" @submit.prevent="editMode ? updateMetode() : tambahCharges()" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nama Charges</label>
                            <input type="text" class="form-control" v-model="form.nama_charge" :class="{'is-invalid' : form.errors.has('form.nama_charge')}" name="nama_charge">
                            <has-error :form="form" field="nama_charge"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Persen (%)</label>
                            <input type="text" v-model="form.jumlah_persen" :class="{'is-invalid' : form.errors.has('form.jumlah_persen')}" class="form-control"></input>
                            <has-error :form="form" field="jumlah_persen"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Nominal Rupiah</label>
                            <input type="text" v-model="form.jumlah_rupiah" :class="{'is-invalid' : form.errors.has('form.jumlah_rupiah')}" class="form-control"></input>
                            <has-error :form="form" field="jumlah_rupiah"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">keterangan</label>
                            <input type="text" v-model="form.keterangan" :class="{'is-invalid' : form.errors.has('form.keterangan')}" class="form-control"></input>
                            <has-error :form="form" field="keterangan"></has-error>
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
                'aksi', 'nama_charge', 'jumlah_persen', 'jumlah_rupiah', 'keterangan',
            ],
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    jumlah_persen: 'No Rekening',
                    nama_charge: 'Bank',
                    jumlah_rupiah: 'Atas Nama',
                },
                sortable: ['nama_charge', 'jumlah_persen', 'jumlah_rupiah',],
                filterable: ['nama_charge', 'jumlah_persen', 'jumlah_rupiah',],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            charges: [],
            form: new Form({
              id: '',
              nama_charge: '',
              jumlah_persen: '',
              jumlah_rupiah: '',
              keterangan: '',
            }),
        }
    },
    methods: {
      hapusMetode(id){
        swal({
            title: 'Anda yakin?',
            text: "Operasi ini tidak dapat dibatalkan!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus denda!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                this.$Progress.start();
                this.form.delete('/api/admin/charges/' + id).then(() => {
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
      updateMetode(){
        this.$Progress.start();
        this.form.post('/api/admin/charges/' + this.form.id)
            .then(() => {
                $('#modalTambah').modal('hide');
                swal(
                    'Sukses!',
                    'Denda berhasil dirubah.',
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
      editModal(metode){
        this.form.reset();
        this.editMode = true;
        $('#modalTambah').modal('show');
        this.form.fill(metode);
      },
      modalBaru(){
        this.form.reset();
        $('#modalTambah').modal('show');
      },
      tambahCharges(){
        this.$Progress.start();
        this.form.post('/api/admin/charges')
            .then(() => {
                Fire.$emit('AfterCreate');
                this.form.reset();
                $('#modalTambah').modal('hide');
                swal(
                    'Sukses!',
                    'Denda berhasil ditambah.',
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
      dataCharges(){
        axios.get('/api/admin/charges').then(({
            data
        }) => (this.charges = data));
      }
    },
    created() {
        this.$Progress.start();
        this.dataCharges();
        Fire.$on('AfterCreate', () => {
            this.dataCharges('');
        });
        this.$Progress.finish();
    }
}
</script>
