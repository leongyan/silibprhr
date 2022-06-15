<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini"> <img src="{{ asset('white') }}/img/logo-text.png" alt="{{ __('Profile Photo') }}"></a>
            <a href="#" class="simple-text logo-normal">{{ _('HRD MS') }}</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ _('Dashboard') }}</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#sgn-menus" 
                    aria-expanded="{{ str_contains($pageSlug, 'sgn') ? 'true' : 'false' }}">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Sili Gadai Nusantara') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ str_contains($pageSlug, 'sgn') ? ' show' : '' }}" id="sgn-menus">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'sgn-karyawan') class="active " @endif>
                            <a href="{{ route('sgn.karyawan')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ _('Karyawan') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'sgn-pk') class="active " @endif>
                            <a href="{{ route('sgn.pk')  }}">
                                <i class="tim-icons icon-link-72"></i>
                                <p>{{ _('Perjanjian Kerja') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'sgn-kehadiran') class="active " @endif>
                            <a href="{{ route('sgn.kehadiran')  }}">
                                <i class="tim-icons icon-calendar-60"></i>
                                <p>{{ _('Kehadiran') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'sgn-gajian') class="active " @endif>
                            <a href="{{ route('sgn.gajian')  }}">
                                <i class="tim-icons icon-credit-card"></i>
                                <p>{{ _('Gajian') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'sgn-hrlibur') class="active " @endif>
                            <a href="{{ route('sgn.hrlibur')  }}">
                                <i class="tim-icons icon-settings"></i>
                                <p>{{ _('Hari Libur') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'sgn-lain') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ _('Lain-lain') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#scb-menus" 
                    aria-expanded="{{ str_contains($pageSlug, 'scb') ? 'true' : 'false' }}">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Sili Corp Bank') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ str_contains($pageSlug, 'scb') ? ' show' : '' }}" id="scb-menus">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'scb-karyawan') class="active " @endif>
                            <a href="{{ route('scb.karyawan')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ _('Karyawan') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'scb-pk') class="active " @endif>
                            <a href="{{ route('scb.pk')  }}">
                                <i class="tim-icons icon-link-72"></i>
                                <p>{{ _('Perjanjian Kerja') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'scb-kehadiran') class="active " @endif>
                            <a href="{{ route('scb.kehadiran')  }}">
                                <i class="tim-icons icon-calendar-60"></i>
                                <p>{{ _('Kehadiran') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'scb-gajian') class="active " @endif>
                            <a href="{{ route('scb.gajian')  }}">
                                <i class="tim-icons icon-credit-card"></i>
                                <p>{{ _('Gajian') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'scb-hrlibur') class="active " @endif>
                            <a href="{{ route('scb.hrlibur')  }}">
                                <i class="tim-icons icon-settings"></i>
                                <p>{{ _('Hari Libur') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'scb-lain') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ _('Lain-lain') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li @if ($pageSlug == 'icons') class="active " @endif>
                <a href="{{ route('pages.icons') }}">
                    <i class="tim-icons icon-atom"></i>
                    <p>{{ _('Icons') }}</p>
                </a>
            </li>
{{--             <li @if ($pageSlug == 'maps') class="active " @endif>
                <a href="{{ route('pages.maps') }}">
                    <i class="tim-icons icon-pin"></i>
                    <p>{{ _('Maps') }}</p>
                </a>
            </li> --}}
            <li @if ($pageSlug == 'notifications') class="active " @endif>
                <a href="{{ route('pages.notifications') }}">
                    <i class="tim-icons icon-bell-55"></i>
                    <p>{{ _('Notifications') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'tables') class="active " @endif>
                <a href="{{ route('pages.tables') }}">
                    <i class="tim-icons icon-puzzle-10"></i>
                    <p>{{ _('Table List') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'typography') class="active " @endif>
                <a href="{{ route('pages.typography') }}">
                    <i class="tim-icons icon-align-center"></i>
                    <p>{{ _('Typography') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'rtl') class="active " @endif>
                <a href="{{ route('pages.rtl') }}">
                    <i class="tim-icons icon-world"></i>
                    <p>{{ _('RTL Support') }}</p>
                </a>
            </li>
            {{-- <li class=" {{ $pageSlug == 'backup' ? 'active' : '' }} bg-info">
                <form action="backup" method="post">
                    @csrf
                    <button class="btn btn-link">backup</button>
                </form>
            </li> --}}
        </ul>
    </div>
</div>
