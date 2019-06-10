@extends('layouts.public')

@section('content')
  <div class="row mb-5">
      <div class="col-md-12">
          <div class="block-32">
              <form action="#">
                  <div class="row">
                      <div class="col-md-6 mb-3 mb-lg-0 col-lg-3">
                          <label for="checkin">Check In</label>
                          <div class="field-icon-wrap">
                              <div class="icon"><span class="icon-calendar"></span></div>
                              <input type="text" id="checkin_date" class="form-control">
                          </div>
                      </div>
                      <div class="col-md-6 mb-3 mb-lg-0 col-lg-3">
                          <label for="checkin">Check Out</label>
                          <div class="field-icon-wrap">
                              <div class="icon"><span class="icon-calendar"></span></div>
                              <input type="text" id="checkout_date" class="form-control">
                          </div>
                      </div>
                      <div class="col-md-6 mb-3 mb-md-0 col-lg-3">
                          <div class="row">
                              <div class="col-md-6 mb-3 mb-md-0">
                                  <label for="checkin">Adults</label>
                                  <div class="field-icon-wrap">
                                      <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                      <select name="" id="" class="form-control">
                                          <option value="">1</option>
                                          <option value="">2</option>
                                          <option value="">3</option>
                                          <option value="">4+</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-md-6 mb-3 mb-md-0">
                                  <label for="checkin">Children</label>
                                  <div class="field-icon-wrap">
                                      <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                      <select name="" id="" class="form-control">
                                          <option value="">1</option>
                                          <option value="">2</option>
                                          <option value="">3</option>
                                          <option value="">4+</option>
                                      </select>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-6 col-lg-3 align-self-end">
                          <button class="btn btn-primary btn-block">Check Availabilty</button>
                      </div>
                  </div>
              </form>
          </div>
      </div>
  </div>

  <div class="row site-section">
      <div class="col-md-12">
          <div class="row mb-5">
              <div class="col-md-7 section-heading">
                  <span class="subheading-sm">Services</span>
                  <h2 class="heading">Facilities &amp; Services</h2>
              </div>
          </div>
      </div>
      <div class="col-md-6 col-lg-4">
          <div class="media block-6">
              <div class="icon"><span class="flaticon-double-bed"></span></div>
              <div class="media-body">
                  <h3 class="heading">Luxury Rooms</h3>
                  <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
          </div>
      </div>
      <div class="col-md-6 col-lg-4">
          <div class="media block-6">
              <div class="icon"><span class="flaticon-wifi"></span></div>
              <div class="media-body">
                  <h3 class="heading">Fast &amp; Free Wifi</h3>
                  <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
          </div>
      </div>
      <div class="col-md-6 col-lg-4">
          <div class="media block-6">
              <div class="icon"><span class="flaticon-customer-service"></span></div>
              <div class="media-body">
                  <h3 class="heading">Call Us 24/7</h3>
                  <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
          </div>
      </div>

      <div class="col-md-6 col-lg-4">
          <div class="media block-6">
              <div class="icon"><span class="flaticon-taxi"></span></div>
              <div class="media-body">
                  <h3 class="heading">Travel Accomodation</h3>
                  <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
          </div>
      </div>
      <div class="col-md-6 col-lg-4">
          <div class="media block-6">
              <div class="icon"><span class="flaticon-credit-card"></span></div>
              <div class="media-body">
                  <h3 class="heading">Accepts Credit Card</h3>
                  <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
          </div>
      </div>
      <div class="col-md-6 col-lg-4">
          <div class="media block-6">
              <div class="icon"><span class="flaticon-dinner"></span></div>
              <div class="media-body">
                  <h3 class="heading">Restaurant</h3>
                  <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
              </div>
          </div>
      </div>

  </div>

@endsection
