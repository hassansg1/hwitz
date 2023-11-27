<nav class="sb-topnav navbar navbar-expand">
  <a class="navbar-brand logo order-2 order-lg-1" href="{{url('/dashboard')}}">Urban Sky</a><button class="btn btn-link btn-sm order-1 order-lg-2" id="sidebarToggle" href="#"><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
  x="0px" y="0px" width="20px" height="20px" viewBox="0 0 24.75 24.75"
  style="enable-background:new 0 0 24.75 24.75;" xml:space="preserve">
  <path
    d="M0,3.875c0-1.104,0.896-2,2-2h20.75c1.104,0,2,0.896,2,2s-0.896,2-2,2H2C0.896,5.875,0,4.979,0,3.875z M22.75,10.375H2 c-1.104,0-2,0.896-2,2c0,1.104,0.896,2,2,2h20.75c1.104,0,2-0.896,2-2C24.75,11.271,23.855,10.375,22.75,10.375z M22.75,18.875H2 c-1.104,0-2,0.896-2,2s0.896,2,2,2h20.75c1.104,0,2-0.896,2-2S23.855,18.875,22.75,18.875z" />
  </svg></button>
  <!-- Navbar-->
  <ul class="navbar-nav ml-auto align-items-center order-3">
    <li class="nav-item dropdown userLink d-none d-lg-flex">
      <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: none;">
        <img src="{{Auth::user()->profile_picture ? Auth::user()->profile_picture : asset('/images/user.png') }}" alt="" class="userToppic rounded-circle" >

      {{Auth::user()->firstname}}&nbsp;{{Auth::user()->lastname}}</a>
      <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
        <li class="userHeader">

          <img class="rounded-circle" src="{{Auth::user()->profile_picture ? Auth::user()->profile_picture : asset('/images/user.png') }}" alt="" />
          <p>{{Auth::user()->firstname}}&nbsp;{{Auth::user()->lastname}} <small class="d-block mt-2"></small></p>
        </li>
        <li class="userFooter d-flex justify-content-between">
          <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#">
        <svg version="1.1" id="Capa_1" width="20px" height="20px" xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"
          style="enable-background:new 0 0 512 512;" xml:space="preserve">
          <path d="M500.6,212.6l-59.9-14.7c-3.3-10.5-7.5-20.7-12.6-30.6l30.6-51c3.6-6,2.7-13.5-2.1-18.3L414,55.4
            c-4.8-4.8-12.3-5.7-18.3-2.1l-51,30.6c-9.9-5.1-20.1-9.3-30.6-12.6l-14.4-59.9C297.9,4.8,291.9,0,285,0h-60
            c-6.9,0-12.9,4.8-14.7,11.4l-14.4,59.9c-10.5,3.3-20.7,7.5-30.6,12.6l-51-30.6c-6-3.6-13.5-2.7-18.3,2.1L53.4,98
            c-4.8,4.8-5.7,12.3-2.1,18.3l30.6,51c-5.1,9.9-9.3,20.1-12.6,30.6l-57.9,14.7C4.8,214.1,0,220.1,0,227v60
            c0,6.9,4.8,12.9,11.4,14.4l57.9,14.7c3.3,10.5,7.5,20.7,12.6,30.6l-30.6,51c-3.6,6-2.7,13.5,2.1,18.3L96,458.6
            c4.8,4.8,12.3,5.7,18.3,2.1l51-30.6c9.9,5.1,20.1,9.3,30.6,12.6l14.4,57.9c1.8,6.6,7.8,11.4,14.7,11.4h60
            c6.9,0,12.9-4.8,14.7-11.4l14.4-57.9c10.5-3.3,20.7-7.5,30.6-12.6l51,30.6c6,3.6,13.5,2.7,18.3-2.1l42.6-42.6
            c4.8-4.8,5.7-12.3,2.1-18.3l-30.6-51c5.1-9.9,9.3-20.1,12.6-30.6l59.9-14.7c6.6-1.5,11.4-7.5,11.4-14.4v-60
            C512,220.1,507.2,214.1,500.6,212.6z M255,332c-41.4,0-75-33.6-75-75c0-41.4,33.6-75,75-75c41.4,0,75,33.6,75,75
            C330,298.4,296.4,332,255,332z" />
          </svg>
        </a>
      </li>
    </ul>
</nav>
