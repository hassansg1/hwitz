<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
     {{--
      <!-- Profile detail-->
      <div class="profileDetail mt-3">
        <a href="{{url('accounts/user_profile')}}">
          <div class="image">
            <img class="image rounded-circle" src="{{Auth::user()->profile_picture ? Auth::user()->profile_picture : asset('/images/user.png') }}" alt="" />
          </div>
          <h3>{{Auth::user()->firstname}}&nbsp;{{Auth::user()->lastname}} </h3>
        </a>
      </div>
      <!-- //Profile detail-->
      <!-- Profile Link -->
      <ul class="profileLink" id="accordion">
        <li class="{{ (request()->is('onboarding/*')) ? 'active' : '' }}">
          <a href="#">Owners</a>
        </li>
        <li class="{{ (request()->is('dashboard')) ? 'active' : '' }}">
          <a href="#">Buildings</a>
        </li>
        <li class="{{ (request()->is('accounts/zoiper_details')) ? 'active' : '' }}">
          <a href="#">Residents</a>
        </li>
      </ul>
      <!-- Profile Link -->
    --}}
    </div>
  </nav>
</div>
