<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
      <div class="logo-wrapper">
      <a href="{{ route('/')}}">
        <img style="max-width: 80% !important;" class="img-fluid for-light" src="{{ asset('storage/image/logo/company/DbkJ7w7-logo.png') }}" alt="">
        <img style="max-width: 80% !important;" class="img-fluid for-dark" src="{{ asset('storage/image/logo/company/DbkJ7w7-logo.png') }}" alt="">
      </a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <!-- <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div> -->
      </div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn">
              <div class="mobile-back text-end"><span>Retour</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            @auth
            @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super' || Auth::user()->role === 'staff' || Auth::user()->role === 'partenaire'))
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ url('home')}}" >
                <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                </svg>
                <svg class="fill-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use>
                </svg><span>Tableau de bord</span></a>
            </li>
            @endif
            @endauth
                @auth
                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super'))
                        <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                                <!-- <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use>
                                </svg> -->
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use>
                                </svg><span>Utilisateurs</span></a>
                            <ul class="sidebar-submenu">
                                <li><a href="{{ url('admin/user') }}">Utilisateurs</a></li>
                                <li><a href="{{ url('admin/user_partenaire') }}">Utilisateurs partenaires</a></li>
                                <li><a href="{{ url('admin/user_delete') }}">Utilisateurs supprimés</a></li>
                                
                            </ul>
                            
                        </li>
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="{{ url('admin/partenaire') }}" >
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-social') }}"></use>
                                </svg>
                                <span>Partenaires</span>
                            </a>
                        </li>
                    @endif
                @endauth
                @auth
                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super'))
                      <li class="sidebar-list">
                          <a class="sidebar-link sidebar-title" href="{{ url('admin/compagnie') }}" >
                              <svg class="fill-icon">
                                  <use href="{{ asset('assets/svg/icon-sprite.svg#fill-social') }}"></use>
                              </svg>
                              <span>Compagnies</span>
                          </a>
                      </li>
                      <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="{{url('admin/mode_paiement')}}" >
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-social') }}"></use>
                                </svg>
                                <span>Mode de paiement</span>
                            </a>
                        </li>
                    @endif
                @endauth
                
                <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"> -->
                @auth
                @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super' || Auth::user()->role === 'staff' || Auth::user()->role === 'partenaire'))
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            <!-- <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use>
                            </svg> -->
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use>
                            </svg><span>Comparateur auto</span></a>
                        <ul class="sidebar-submenu">
                        @auth
                            @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super'))
                                <li><a href="{{ url('admin/compagnie/garantie') }}">Garanties automobile</a></li>
                                <li><a href="{{ url('admin/package') }}">Packages automobile</a></li>
                                <li><a href="{{ url('admin/bareme') }}">Baremes Assurances auto</a></li>
                                <li><a href="{{ url('admin/compagnie/produit') }}">Toutes les garanties automobiles</a></li>
                                <!-- <li><a href="{{url('admin/suivis')}}">Suivis des assurances </a></li> -->
                            @endif
                        @endauth
                        @auth
                            @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super'))
                                <li><a href="{{ url('admin/compagnie/garantie') }}">Garanties automobile</a></li>
                                <li><a href="{{ url('admin/package') }}">Packages automobile</a></li>
                                <li><a href="{{ url('admin/bareme') }}">Baremes Assurances auto</a></li>
                                <li><a href="{{ url('admin/compagnie/produit') }}">Toutes les garanties automobiles</a></li>
                                <!-- <li><a href="{{ url('admin/simulation') }}">Simulation </a></li> -->
                            @endif
                        @endauth
                          
                                @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super'))
                                    <li><a href="{{ url('admin/compagnie/garantie') }}">Garanties automobile</a></li>
                                    <li><a href="{{ url('admin/package') }}">Packages automobile</a></li>
                                    <li><a href="{{ url('admin/bareme') }}">Baremes Assurances auto</a></li>
                                    <li><a href="{{ url('admin/compagnie/produit') }}">Toutes les garanties automobiles</a></li>
                                    <!-- <li><a href="{{ url('admin/simulation') }}">Simulation </a></li> -->
                                @endif
                           
                        
                            <li><a href="{{ url('admin/simulateur/pack') }}">Simulateur automobile</a></li>                           
                        </ul>
                    </li>

                   
                @endif
                @endauth
                @auth
                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super' || Auth::user()->role === 'staff' ))
                    <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="{{ url('historique')}}" >
                                <span>Historiques simulations</span>
                            </a>
                        </li>
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="{{ url('admin/suivis')}}" >
                                <span>Suivis des assurances</span>
                            </a>
                        </li>
                    @endif
                @endauth
                @auth
                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super' || Auth::user()->role === 'social'))
                    <li class="sidebar-list">
                      <a class="sidebar-link sidebar-title" href="{{ url('admin/sponsoring')}}" >
                      <span>Suivis du sponsoring</span>
                      </a>
                    </li>
                    <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                    </li> -->
                    <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                    </li>-->
                    @endif
                @endauth
                @auth
                  @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super'))
                     <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#">
                            
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-email') }}"></use>
                            </svg><span>Comparateur voyage</span></a>
                        <ul class="sidebar-submenu">
                            <!-- <li><a href="">produits voyage</a></li>  -->
                            <li><a href="{{ url('admin/simulation_voyage') }}">Simulation voyage</a></li>
                        </ul>
                    </li>
                  @endif
                @endauth    
                  </ul>
                </div>
                <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
              </nav>
            </div>
          </div>

  <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title"  href="{{ url('admin/bareme') }}"  > -->
                <!-- <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-social') }}"></use>
                </svg> -->
                <!-- <svg class="fill-icon"> -->
                  <!-- <use href="{{ asset('assets/svg/icon-sprite.svg#fill-social') }}"></use> -->
                <!-- </svg><span>Baremes Assurances</span></a></li> -->
                <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ url('admin/compagnie/garantie') }}" > -->
                <!-- <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                </svg> -->
                <!-- <svg class="fill-icon"> -->
                  <!-- <use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"></use> -->
                <!-- </svg><span>Garanties</span></a></li> -->
                <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ url('admin/compagnie/produit') }}" > -->
                <!-- <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                </svg> -->
                <!-- <svg class="fill-icon"> -->
                  <!-- <use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"></use> -->
                <!-- </svg><span>produits</span></a></li> -->
                <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ url('admin/package') }}" > -->
                <!-- <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                </svg> -->
                <!-- <svg class="fill-icon"> -->
                  <!-- <use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"></use> -->
                <!-- </svg><span>Packages</span></a></li> -->
                <!-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="{{ url('admin/simulation') }}" > -->
                <!-- <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                </svg> -->
                <!-- <svg class="fill-icon"> -->
                  <!-- <use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"></use> -->
                <!-- </svg><span>Simulation</span></a></li> -->