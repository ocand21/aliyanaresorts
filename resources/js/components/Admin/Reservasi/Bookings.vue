<template>
<div id="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item">Reservasi</li>
        <li class="breadcrumb-item active">Booking</li>``
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
                                <strong>Daftar Booking</strong>

                            </div>
                            <div class="card-body table-responsive">
                                <!-- {{ moment().format('YYYY-MM-YYYY') }} -->
                                <div class="col-md-12">
                                    <router-link style="margin-bottom: 10px" to="/admin/booking/room" href="#" @click="modal()" class="btn btn-success btn-sm text-right"><span class="fa fa-plus"></span> Tambah Booking</router-link>
                                    <div class="radio">
                                        <label class="radio-inline"><input type="radio" v-model="filter.inhouse" @click="filterBooking(2)" value="3" name="optradio"> In House</label>
                                        <label class="radio-inline"><input type="radio" v-model="filter.rsv_today" @click="filterBooking('rsv_today')" value="rsv_today" name="optradio">Rsv Today</label>
                                        <label class="radio-inline"><input type="radio" v-model="filter.all_rsv" @click="filterBooking('all_rsv')" value="all_rsv" name="optradio">ALL RSV</label>
                                        <label class="radio-inline"><input type="radio" v-model="filter.expected_arrv" @click="filterBooking('expected_arrv')" value="expected_arrv" name="optradio">Expected Arv</label>
                                        <label class="radio-inline"><input type="radio" v-model="filter.expected_dep" @click="filterBooking('expected_dep')" value="expected_dep" name="optradio">Expected Dep</label>

                                    </div>
                                    <form action="" @submit.prevent="filterTgl()" method="POST">
                                        <label for="">Filter Tanggal</label>
                                        <date-picker name="tgl_awal" v-model="formFilter.tgl_awal" :lang="lang" value-type="format"  :class="{ 'is-invalid': formFilter.errors.has('tgl_awal') }"></date-picker>
                                        <date-picker name="tgl_akhir" v-model="formFilter.tgl_akhir" :lang="lang" value-type="format"  :class="{ 'is-invalid': formFilter.errors.has('tgl_akhir') }"></date-picker>
                                        <button type="submit" class="btn btn-success btn-sm" name="button">Filter</button>
                                        <button type="button" @click="dataBooking()" class="btn btn-danger btn-sm" name="button">Reset</button>
                                    </form>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <v-client-table :data="bookings" :columns="columns" :options="options">
                                        <div slot="aksi" slot-scope="{row}">
                                            <a href="#" @click="detilModal(row.kode_booking)" style="margin-bottom: 5px" class="btn btn-primary btn-sm"><span class="fa fa-search"></span></a>
                                            <a v-if="row.status === 'Waiting Payment' && row.status === 'Reserved'" href="#" @click="cancelModal(row)" class="btn btn-danger btn-sm"><span class="fa fa-window-close"></span></a>
                                            <a href="#" @click="editReservasi(row.kode_booking)" class="btn btn-success btn-sm"><span class="fa fa-edit"></span></a>
                                        </div>
                                        <p slot="kode_booking" slot-scope="{row}" class="text-uppercase">{{row.kode_booking}}</p>
                                        <!-- <p slot="tgl_checkin" slot-scope="{row}">{{row.tgl_checkin | myDate}}</p> -->
                                        <!-- <p slot="tgl_checkout" slot-scope="{row}">{{row.tgl_checkout | myDate}}</p> -->
                                        <p slot="total_tagihan" class="red" slot-scope="{row}">Rp. {{row.total_tagihan | currency}}</p>
                                        <p slot="terbayarkan" class="red" slot-scope="{row}">Rp. {{row.terbayarkan | currency}}</p>
                                        <p slot="kekurangan" class="red" slot-scope="{row}">Rp. {{row.kekurangan | currency}}</p>
                                        <p slot="jml_kamar" class="text-right" slot-scope="{row}">{{row.jml_kamar}} Kamar</p>
                                        <div slot="tgl_checkin" slot-scope="{row}">
                                          <a href="#" v-show="row.tgl_checkin == moment().format('YYYY-MM-DD') && row.status == 'Reserved'" @click.prevent="checkinRoute(row.kode_booking)" class="btn btn-sm badge badge-success text-uppercase">CHECKIN NOW</a>
                                          <p v-show="row.tgl_checkin != moment().format('YYYY-MM-DD') || row.status == 'Inhouse' || row.status == 'Waiting Payment'">{{row.tgl_checkin | myDate}}</p>
                                        </div>
                                        <div slot="tgl_checkout" slot-scope="{row}">
                                          <a href="#" v-if="row.tgl_checkout === moment().format('YYYY-MM-DD') && row.status === 'Inhouse'" @click.prevent="checkoutRoute(row.kode_booking)" class="btn btn-sm badge badge-danger text-uppercase">Checkout NOW</a>
                                          <p v-if="row.tgl_checkout != moment().format('YYYY-MM-DD')">{{row.tgl_checkout | myDate}}</p>
                                        </div>
                                        <p slot="created_at" slot-scope="{row}">{{row.created_at | myDate}}</p>
                                        <div slot="status" class="text-center" slot-scope="{row}">
                                            <a v-if="row.status === 'Waiting Payment'" @click.prevent="pembayaranModal(row.kode_booking)" href="" class="btn btn-sm badge badge-warning text-uppercase">Waiting Payment</a>
                                            <a v-if="row.status === 'Reserved'" href="" @click.prevent="" class="btn btn-sm badge badge-primary text-uppercase">Reserved</a>
                                            <a v-if="row.status === 'Inhouse'" href="#" @click.prevent="" class="btn btn-sm badge badge-info text-uppercase">Inhouse</a>
                                            <!-- <a v-if="row.status === 'Checkout'" href="#" @click.prevent="" class="btn btn-sm badge badge-danger text-uppercase">Checkout</a> -->
                                        </div>
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

    <div class="modal fade bd-example-modal-lg" id="modalDetil" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase">Detil Booking {{booking.kode_booking}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-success btn-sm" name="button" @click="pembayaranModal(booking.kode_booking)"><i class="fa fa-money-bill"></i> Pembayaran</button>
                                <button type="button" class="btn btn-success btn-sm" name="button" @click="detilTagihan(booking.kode_booking)"><i class="fa fa-money-bill"></i> Detil Tagihan</button>
                                <button type="button" class="btn btn-success btn-sm" name="button" @click="cetakInvoice(booking.kode_booking)"><i class="fa fa-print"></i> Cetak Invoice</button>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-3">
                                <p>Kode Booking</p>
                            </div>
                            <div class="col-md-9">
                                <p class="text-uppercase">{{booking.kode_booking}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Nama Pelanggan</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.nama}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Email Pelanggan</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.email}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>No Telepon</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.no_telepon}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Alamat Pelanggan</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.alamat}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Diinputkan Oleh</p>
                            </div>
                            <div class="col-md-9 blue">
                                <p>{{booking.created_by}}</p>
                            </div>

                            <div class="col-md-3">
                                <p>Tgl Input</p>
                            </div>
                            <div class="col-md-9 blue">
                                <p>{{booking.created_at}}</p>
                            </div>
                        </div>
                        <hr class="green">
                        <div class="row">
                            <div class="col-md-3">
                                <p>Tanggal Check-in</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.tgl_checkin | myDate}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Tanggal Check-out</p>
                            </div>
                            <div class="col-md-9">
                                <p>{{booking.tgl_checkout | myDate}}</p>
                            </div>

                            <div class="col-md-3">
                                <p>Total</p>
                            </div>
                            <div class="col-md-9">
                                <p class="red">Rp. {{booking.total | currency}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Terbayarkan</p>
                            </div>
                            <div class="col-md-9">
                                <p class="red">Rp. {{booking.terbayarkan | currency}}</p>
                            </div>
                            <div class="col-md-3">
                                <p>Kekurangan</p>
                            </div>
                            <div class="col-md-9">
                                <p class="red">Rp. {{booking.hutang | currency}}</p>
                            </div>

                            <div class="col-md-3">
                                <p>Metode Pembayaran</p>
                            </div>
                            <div class="col-md-9">
                                <p class="blue">{{booking.bank}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipe Kamar</th>
                                        <th>Kapasitas</th>
                                        <th>Jumlah Tamu</th>
                                        <!-- <th>Deskripsi</th> -->
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="kamar in dataKamar" :key="kamar.no_room">
                                        <td>{{kamar.tipe}}</td>
                                        <td>{{kamar.kapasitas}} Orang</td>
                                        <td>{{kamar.jml_kamar}} Kamar</td>
                                        <td>
                                            <p>Rp. {{kamar.harga | currency}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalPayment" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase">Pembayaran Booking {{booking.kode_booking}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="cash-tab" data-toggle="tab" href="#cash" role="tab" aria-controls="cash" aria-selected="true">Cash</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="transfer-tab" data-toggle="tab" href="#transfer" role="tab" aria-controls="transfer" aria-selected="false">Transfer</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="credit-tab" data-toggle="tab" href="#credit" role="tab" aria-controls="credit" aria-selected="false">Credit Card</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTab1Content">
                            <div class="tab-pane fade active show" id="cash" role="tabpanel" aria-labelledby="cash-tab">
                                <form class="" @submit.prevent="postCash()" method="post">
                                    <div class="form-group">
                                        <label for="">Kode Booking</label>
                                        <input type="text" name="kode_booking" v-model="cash.kode_booking" :class="{'is-invalid' : cash.errors.has('kode_booking')}" class="form-control text-uppercase" readonly value="">
                                        <has-error :form="cash" field="kode_booking"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Total Tagihan</label>
                                        <money v-model="cash.total_tagihan" v-bind="money" type="text" name="total_tagihan" readonly class="form-control" :class="{ 'is-invalid': cash.errors.has('total_tagihan') }" id="total"></money>
                                        <has-error :form="cash" field="total_tagihan"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jumlah Pembayaran</label>
                                        <money v-model="cash.jml_bayar" v-bind="money" type="text" name="jml_bayar" class="form-control" :class="{ 'is-invalid': cash.errors.has('jml_bayar') }" id="total"></money>
                                        <has-error :form="cash" field="jml_bayar"></has-error>
                                    </div>
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-success btn-sm" name="button">Simpan</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="transfer" role="tabpanel" aria-labelledby="transfer-tab">
                                <form class="" @submit.prevent="postTransfer()" method="post">
                                    <div class="form-group">
                                        <label for="">Kode Booking</label>
                                        <input type="text" name="kode_booking" v-model="transfer.kode_booking" :class="{'is-invalid' : transfer.errors.has('transfer.kode_booking')}" class="form-control text-uppercase" readonly value="">
                                        <has-error :form="transfer" field="kode_booking"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Total Tagihan</label>
                                        <money v-model="transfer.total_tagihan" v-bind="money" type="text" name="total_tagihan" readonly class="form-control" :class="{ 'is-invalid': transfer.errors.has('total_tagihan') }" id="total"></money>
                                        <has-error :form="transfer" field="total_tagihan"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Transfer Ke</label>
                                        <select class="form-control" name="id_metode" v-model="transfer.id_metode">
                                            <option v-for="mtd in metode" :key="mtd.id" :value="mtd.id">{{mtd.bank}} - {{mtd.no_rekening}} AN {{mtd.atas_nama}}</option>
                                        </select>
                                        <has-error :form="transfer" field="jml_bayar"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Nama Pemilik Rekening</label>
                                        <input type="text" name="nama_pemilik_rekening" v-model="transfer.nama_pemilik_rekening" :class="{'is-invalid' : transfer.errors.has('transfer.nama_pemilik_rekening')}" class="form-control" value="">
                                        <has-error :form="transfer" field="nama_pemilik_rekening"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Nomor Rekening</label>
                                        <input type="text" name="no_rekening" v-model="transfer.no_rekening" :class="{'is-invalid' : transfer.errors.has('transfer.no_rekening')}" class="form-control" value="">
                                        <has-error :form="transfer" field="no_rekening"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Transfer</label>
                                        <date-picker name="tgl_transfer" v-model="transfer.tgl_transfer" :lang="lang" value-type="format" :class="{ 'is-invalid': transfer.errors.has('tgl_transfer') }"></date-picker>
                                        <has-error :form="transfer" field="tgl_transfer"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jumlah Pembayaran</label>
                                        <money v-model="transfer.jml_bayar" v-bind="money" type="text" name="jml_bayar" class="form-control" :class="{ 'is-invalid': transfer.errors.has('jml_bayar') }" id="total"></money>
                                        <has-error :form="transfer" field="jml_bayar"></has-error>
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-success btn-sm" name="button">Simpan</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="credit" role="tabpanel" aria-labelledby="credit-tab">
                              <form action="" @submit.prevent="postCredit()">
                                <div class="form-group">
                                  <label for="">Kode Booking</label>
                                  <input type="text" class="form-control text-uppercase" v-model="formCard.kode_booking" name="kode_booking" readonly :class="{ 'is-invalid': formCard.errors.has('kode_booking') }">
                                  <has-error :form="formCard" field="kode_booking"></has-error>
                                </div>
                                <div class="form-group">
                                  <label for="">Card Type</label>
                                  <select class="form-control" name="card_id" v-model="formCard.card_id">
                                    <option v-for="kartu in masterKartu" :value="kartu.id">{{kartu.nama_kartu}}</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="">Card No</label>
                                  <input type="text" class="form-control" v-model="formCard.card_no" name="card_no" :class="{ 'is-invalid': formCard.errors.has('card_no') }">
                                  <has-error :form="formCard" field="card_no"></has-error>
                                </div>
                                <div class="form-group">
                                  <label for="">Card Name</label>
                                  <input type="text" class="form-control" v-model="formCard.card_name" name="card_name" :class="{ 'is-invalid': formCard.errors.has('card_name') }">
                                  <has-error :form="formCard" field="card_name"></has-error>
                                </div>
                                <div class="form-group">
                                  <label for="">Expired Date</label>
                                  <date-picker name="expired_date" v-model="formCard.expired_date" :lang="lang" value-type="format" :class="{ 'is-invalid': formCard.errors.has('expired_date') }"></date-picker>
                                  <has-error :form="formCard" field="expired_date"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="">Total Tagihan</label>
                                    <money v-model="formCard.total_tagihan" v-bind="money" type="text" name="total_tagihan" readonly class="form-control" :class="{ 'is-invalid': formCard.errors.has('total_tagihan') }" id="total"></money>
                                    <has-error :form="formCard" field="total_tagihan"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="">Jumlah Pembayaran</label>
                                    <money v-model="formCard.jml_bayar" v-bind="money" type="text" name="jml_bayar" class="form-control" :class="{ 'is-invalid': formCard.errors.has('jml_bayar') }" id="total"></money>
                                    <has-error :form="formCard" field="jml_bayar"></has-error>
                                </div>


                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-success btn-sm" name="button">Simpan</button>
                                </div>

                              </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" name="button" @click="tutupModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade bd-example-modal-lg" id="modalCancel" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" @submit.prevent="cancelBooking()" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Kode Booking</label>
                            <input type="text" readonly class="form-control" v-model="form.kode_booking" :class="{'is-invalid' : form.errors.has('form.kode_booking')}" name="kode_booking">
                            <has-error :form="form" field="kode_booking"></has-error>
                            <input type="hidden" v-model="form.id_pelanggan" name="" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Pelanggan</label>
                            <input type="text" v-model="form.nama" readonly :class="{'is-invalid' : form.errors.has('form.nama')}" class="form-control"></input>
                            <has-error :form="form" field="nama"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Alasan</label>
                            <input type="text" v-model="form.alasan" :class="{'is-invalid' : form.errors.has('form.alasan')}" class="form-control"></input>
                            <has-error :form="form" field="alasan"></has-error>
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

    <div class="modal fade bd-example-modal-lg" id="modalEdit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="" @submit.prevent="updateBooking()" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Kode Booking</label>
                            <input type="text" readonly class="form-control text-uppercase" v-model="formEdit.kode_booking" :class="{'is-invalid' : formEdit.errors.has('formEdit.kode_booking')}" name="kode_booking">
                            <input type="hidden" readonly class="form-control text-uppercase" v-model="formEdit.user_id" :class="{'is-invalid' : formEdit.errors.has('formEdit.user_id')}" name="user_id">
                            <has-error :form="formEdit" field="kode_booking"></has-error>
                            <input type="hidden" v-model="formEdit.id_pelanggan" name="" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Lengkap</label>
                            <input type="text" v-model="formEdit.nama" :class="{'is-invalid' : formEdit.errors.has('formEdit.nama')}" class="form-control"></input>
                            <has-error :form="formEdit" field="nama"></has-error>
                        </div>
                        <div class="row">
                          <div class="col-lg-3">
                            <div class="form-group">
                                <label for="">Tipe Identitas</label>
                                <input type="text" v-model="formEdit.tipe_identitas" :class="{'is-invalid' : formEdit.errors.has('formEdit.tipe_identitas')}" class="form-control"></input>
                                <has-error :form="formEdit" field="tipe_identitas"></has-error>
                            </div>
                          </div>
                          <div class="col-lg-9">
                            <div class="form-group">
                                <label for="">No Identitas</label>
                                <input type="text" v-model="formEdit.no_identitas" :class="{'is-invalid' : formEdit.errors.has('formEdit.no_identitas')}" class="form-control"></input>
                                <has-error :form="formEdit" field="no_identitas"></has-error>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label for="">No Telepon</label>
                            <input type="text" v-model="formEdit.no_telepon" :class="{'is-invalid' : formEdit.errors.has('formEdit.no_telepon')}" class="form-control"></input>
                            <has-error :form="formEdit" field="no_telepon"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" v-model="formEdit.email" :class="{'is-invalid' : formEdit.errors.has('formEdit.email')}" class="form-control"></input>
                            <has-error :form="formEdit" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <input type="text" v-model="formEdit.alamat" :class="{'is-invalid' : formEdit.errors.has('formEdit.alamat')}" class="form-control"></input>
                            <has-error :form="formEdit" field="alamat"></has-error>
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
import DatePicker from 'vue2-datepicker'
import moment from 'moment'
import Editor from '@tinymce/tinymce-vue';
export default {
    components: {
        'editor': Editor,
        DatePicker,
    },
    data() {
        return {
            formCard: new Form({
              kode_booking: '',
              card_id: '',
              card_no: '',
              card_name: '',
              expired_date: '',
              total_tagihan: '',
              jml_bayar: '',
            }),
            editMode: true,
            columns: [
                'aksi', 'status', 'kode_booking', 'tipe', 'jml_kamar', 'nama', 'tgl_checkin', 'tgl_checkout', 'total_tagihan', 'terbayarkan', 'kekurangan', 'tipe_booking',
            ],
            lang: {
                days: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                pickers: ['next 7 days', 'next 30 days', 'previous 7 days', 'previous 30 days'],
                placeholder: {
                    date: 'Pilih Tanggal',
                }
            },
            optionsDate: {
                format: 'YYYY-mm-dd'
            },
            money: {
                decimal: ',',
                thousands: '.',
                prefix: 'Rp ',
                suffix: '',
                precision: 0,
                masked: false
            },
            options: {
                texts: {
                    filterPlaceholder: "Cari data",
                    filter: "Pencarian : ",
                    filterBy: "Cari {column}",
                    count: "Menampilkan {from} ke {to} dari {count} data|{count} data|Satu data",
                },
                headings: {
                    nama: 'Nama Lengkap',
                    kode_booking: 'Kode Booking',
                    no_telepon: 'No Telepon',
                    tgl_checkin: 'Tgl Checkin',
                    tgl_checkout: 'Tgl Checkout',
                    status: 'Status',
                    tipe_booking: 'Tipe Booking',
                    created_at: 'Tgl Reservasi'
                },
                sortable: ['kode_booking', 'tipe', 'jml_kamar', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'total_tagihan', 'terbayarkan', 'kekurangan', 'status', 'tipe_booking'],
                filterable: ['kode_booking', 'tipe', 'jml_kamar', 'nama', 'no_telepon', 'tgl_checkin', 'tgl_checkout', 'total_tagihan', 'terbayarkan', 'kekurangan', 'status', 'tipe_booking'],
                columnsDisplay: {},
                filterByColumn: true,
                pagination: {
                    dropdown: false
                },
                columnsClasses: {
                    aksi: 'text-center',
                },
            },
            bookings: [],
            booking: [],
            dataKamar: [],
            formFilter: new Form({
                tgl_awal: '',
                tgl_akhir: '',
            }),
            form: new Form({
                kode_booking: '',
                id_pelanggan: '',
                alasan: '',
                nama: '',
            }),
            filter: new Form({
                inhouse: '',
                rsv_today: '',
                all_rsv: '',
                expected_arrv: '',
                expected_dep: '',
            }),
            cash: new Form({
                kode_booking: '',
                total_tagihan: '',
                jml_bayar: '',
            }),
            transfer: new Form({
                kode_booking: '',
                total_tagihan: '',
                jml_bayar: '',
                id_metode: '',
                nama_pemilik_rekening: '',
                no_rekening: '',
                tgl_transfer: '',
            }),
            formEdit: new Form({
              user_id: '',
              kode_booking: '',
              nama: '',
              tipe_identitas: '',
              no_identitas: '',
              no_telepon: '',
              email: '',
              alamat: '',
            }),
            metode: [],
            masterKartu: [],
        }
    },
    methods: {
        updateBooking(){
          swal({
              title: 'Anda yakin?',
              text: "Operasi ini tidak dapat dibatalkan!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, ubah data!',
              cancelButtonText: 'Batal'
          }).then((result) => {
              if (result.value) {
                  this.$Progress.start();
                  this.formEdit.post('/api/admin/booking/update').then(() => {
                      swal(
                          'Berhasil!',
                          'Data berhasil diubah.',
                          'success'
                      )
                      Fire.$emit('AfterCreate')
                      $('#modalEdit').modal('hide');
                      this.formEdit.reset();
                      this.$Progress.finish();
                  }).catch(() => {
                      swal("Gagal!", "Terjadi kesalahan.",
                          "warning");
                  });
              }

          })
        },
        editReservasi(kode_booking){
          $('#modalEdit').modal('show');
          axios.get('/api/admin/booking/detil/' + kode_booking).then(({
              data
          }) => (this.formEdit.fill(data)));
        },
        postCredit(){
          swal({
              title: 'Anda yakin?',
              text: "Operasi ini tidak dapat dibatalkan!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, proses pembayaran!',
              cancelButtonText: 'Batal'
          }).then((result) => {
              if (result.value) {
                  this.$Progress.start();
                  this.formCard.post('/api/admin/payment/credit-card').then(() => {
                      swal(
                          'Berhasil!',
                          'Pembayaran Credit Berhasil.',
                          'success'
                      )
                      this.tutupModal();
                      this.transfer.reset();
                      Fire.$emit('AfterCreate')
                      this.$Progress.finish();
                  }).catch(() => {
                      swal("Gagal!", "Terjadi kesalahan.",
                          "warning");
                  });
              }

          })
        },
        loadKartu(){
          axios.get('/api/admin/master-kartu').then(({
              data
          }) => (this.masterKartu = data));
        },
        checkoutRoute(kode_booking){
          this.$router.push({
              name: 'form-checkout',
              params: {
                  kode_booking: kode_booking
              }
          })
        },
        moment: function() {
          return moment();
        },
        filterTgl() {
            axios.get('/api/admin/booking/' + this.formFilter.tgl_awal + '/' + this.formFilter.tgl_akhir).then(({
                data
            }) => (this.bookings = data));
        },
        checkinRoute(kode_booking) {
            this.$router.push({
                name: 'form-checkin',
                params: {
                    kode_booking: kode_booking
                }
            })
        },
        postTransfer() {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, proses pembayaran!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    this.transfer.post('/api/admin/payment/transfer').then(() => {
                        swal(
                            'Berhasil!',
                            'Pembayaran Transfer Berhasil.',
                            'success'
                        )
                        this.tutupModal();
                        this.transfer.reset();
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish();
                    }).catch(() => {
                        swal("Gagal!", "Terjadi kesalahan.",
                            "warning");
                    });
                }

            })
        },
        tutupModal() {
            $('#modalPayment').modal('hide');
            $('#modalDetil').modal('hide');
        },
        postCash() {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, proses pembayaran!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    this.cash.post('/api/admin/payment/cash').then(() => {
                        swal(
                            'Berhasil!',
                            'Pembayaran Cash Berhasil.',
                            'success'
                        )
                        this.tutupModal();
                        this.cash.reset();
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish();
                    }).catch(() => {
                        swal("Gagal!", "Terjadi kesalahan.",
                            "warning");
                    });
                }

            })
        },
        metodePembayaran() {
            axios.get('/api/admin/payment/metode').then(({
                data
            }) => (this.metode = data));
        },
        pembayaranModal(kode_booking) {
            $('#modalPayment').modal('show');
            // $('#modalDetil').modal('hide');
            axios.get('/api/admin/booking/detil/' + kode_booking).then(({
                data
            }) => (this.cash.fill(data)));
            axios.get('/api/admin/booking/detil/' + kode_booking).then(({
                data
            }) => (this.transfer.fill(data)));
            axios.get('/api/admin/booking/detil/' + kode_booking).then(({
                data
            }) => (this.formCard.fill(data)));
            this.metodePembayaran();
            this.loadKartu();
        },
        detilTagihan(kode_booking) {
            $('#modalDetil').modal('hide');
            this.$router.push({
                name: 'detil-tagihan',
                params: {
                    kode_booking: kode_booking
                }
            })
        },
        cetakInvoice(kode_booking) {
            window.open('/booking/invoice/' + kode_booking, '_blank');
        },
        detilKamar() {
            axios.get('/api/admin/booking/detil/kamar/' + this.$route.params.kode_booking).then(({
                data
            }) => (this.dataKamar = data));
        },
        detilModal(booking) {
            $('#modalDetil').modal('show');
            axios.get('/api/admin/booking/detil/kamar/' + booking).then(({
                data
            }) => (this.dataKamar = data));
            axios.get('/api/admin/booking/detil/' + booking).then(({
                data
            }) => (this.booking = data));
        },
        filterBooking(filter) {
            axios.get('/api/admin/booking/filter/' + filter).then(({
                data
            }) => (this.bookings = data));
        },
        cancelModal(booking) {
            this.form.reset();
            $('#modalCancel').modal('show');
            this.form.fill(booking);
        },
        cancelBooking() {
            swal({
                title: 'Anda yakin?',
                text: "Operasi ini tidak dapat dibatalkan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batalkan booking!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    this.form.post('/api/admin/booking/cancel').then(() => {
                        swal(
                            'Dibatalkan!',
                            'Booking dibatalkan.',
                            'success'
                        )
                        Fire.$emit('AfterCreate')
                        $('#modalCancel').modal('hide');
                        this.$Progress.finish();
                    }).catch(() => {
                        swal("Gagal!", "Terjadi kesalahan.",
                            "warning");
                    });
                }

            })
        },
        detilBooking(kode_booking) {
            this.$router.push({
                name: 'detil-booking',
                params: {
                    kode_booking: kode_booking
                }
            })
        },
        dataBooking() {
            this.formFilter.reset()
            axios.get('/api/admin/booking').then(({
                data
            }) => (this.bookings = data));
        }
    },
    created() {
        this.$Progress.start();
        this.dataBooking();
        Fire.$on('AfterCreate', () => {
            this.dataBooking('');
        });
        this.$Progress.finish();
    }
}
</script>
