<template>
  <li class="nav-item dropdown d-md-down-none">
      <a class="nav-link" data-toggle="dropdown"  id="markasread" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-bell"></i>
          <span class="badge badge-pill badge-danger">{{unreads.length}}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
            <div class="dropdown-header text-center">
                <strong>{{unreads.length }} Notifikasi Baru</strong>
            </div>
            <notification-item v-for="unread in unreads" :key="unread.id">

            </notification-item>
            <!-- @foreach ($user->unreadNotifications as $notification)
              <a class="dropdown-item" href="#">
                  <i class="fa fa-briefcase text-success"></i>Reservasi Baru AN {{$notification->data['pelanggan']['nama']}}</a>
            @endforeach
          @else
            <a href="" class="dropdown-item">
                Belum ada notifikasi
            </a>
          @endif -->


      </div>
  </li>
</template>

<script>
    export default {
        props: ['unreads','userId'],
        mounted() {
            console.log('Component mounted.');
            Echo.private('App.Admin.' + this.userId).notification((notification) => {
              console.log(notification)
            });

        }
    }
</script>
