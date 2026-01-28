		<header class="site-header">
		    <div class="container-fluid">
		        <a href="{{ route('home') }}" class="site-logo">
		            <img class="hidden-md-down" src="{{ asset('assets/img/logo-cpt.png') }}" alt="CPT">
					<span class="title">TQM</span>
		        </a>

		        <button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
		            <span>toggle menu</span>
		        </button>
		        <button class="hamburger hamburger--htla">
		            <span>toggle menu</span>
		        </button>

		        <div class="site-header-content">
		            <div class="site-header-content-in">
		                <div class="site-header-shown">
                            @amministratori
		                    <div class="dropdown dropdown-notification notif">
		                        <a href="{{ route('pannello.profilo.edit') }}" class="header-icon">
		                            <i class="glyphicon glyphicon-user"></i>
		                        </a>
		                    </div>
                            @endamministratori

							@auth
		                    <div class="dropdown user-menu">
								<a href="{{ route('logout') }}" class="header-icon"
									onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									<i class="glyphicon glyphicon-off"></i>
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
		                    </div>
							@else
							<a class="btn btn-login" href="{{ route('login') }}">Accedi</a>
							@endauth
		                </div><!--.site-header-shown-->
		            </div><!--site-header-content-in-->
		        </div><!--.site-header-content-->
		    </div><!--.container-fluid-->
		</header><!--.site-header-->

		<div class="mobile-menu-left-overlay"></div>
		<nav class="side-menu side-menu-compact">
		    <ul class="side-menu-list">
		        <li class="blue @group('home')">
		            <a href="{{ route('home') }}">
		                <i class="font-icon font-icon-home"></i>
		                <span class="lbl">Home</span>
		            </a>
		        </li>
				@auth
				<li class="orange-red @group('valutazione')">
                    @amministratori
                    <a href="{{ route('valutazione.gestione.index') }}">
                    @else
					<a href="{{ route('valutazione.mie') }}">
                    @endamministratori
						<i class="glyphicon glyphicon-thumbs-up"></i>
						<span class="lbl">Qualifiche</span>
					</a>
				</li>
				@endauth
				@amministratori
		        <li class="purple @group('sondaggio')">
		            <a href="{{ route('sondaggio.home') }}">
		                <i class="glyphicon glyphicon-list-alt"></i>
		                <span class="lbl">Sondaggi</span>
		            </a>
		        </li>
				@admin
		        <li class="green @group('pannello')">
		            <a href="{{ route('pannello.home') }}">
		                <i class="font-icon font-icon-speed"></i>
		                <span class="lbl">Pannello Gestionale</span>
		            </a>
		        </li>
				@endadmin
				@endamministratori
				{{--
				@auth
		        <li class="orange-red{{ $opened['manuale'] }}">
		            <a href="{{ route('manuale') }}">
		                <i class="font-icon font-icon-help"></i>
		                <span class="lbl">Manuale</span>
		            </a>
		        </li>
				@endauth
				--}}
		        <li class="grey @group('info')">
		            <a href="{{ route('info') }}">
		                <i class="glyphicon glyphicon-info-sign"></i>
		                <span class="lbl">Informazioni</span>
		            </a>
		        </li>

		    </ul>
		</nav>

		@if($group === 'valutazione')
			<nav class="side-menu-addl">
				<header class="side-menu-addl-title">
					<div class="caption">Qualifica</div>
				</header>
				<ul class="side-menu-addl-list orange-red">
                    @if(auth()->guard('docenti')->check() || auth()->guard('allievi')->check())
					<li class="@tab('mie')">
						<a href="{{ route('valutazione.mie') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Mie qualifiche</span>
		                </span>
						</a>
					</li>
                    @endif
					@if(auth()->guard('docenti')->check() || auth()->guard('admin')->check() || auth()->guard('segretarie')->check())
                    @docente
					<li class="divider"></li>
                    @enddocente
					<li class="@tab('gestione.semestri.index') @tab('gestione.semestri.show')">
						<a href="{{ route('valutazione.gestione.semestri.index') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Gestisci semestri</span>
		                </span>
						</a>
					</li>
					@amministratori
					<li class="@tab('gestione.index')">
						<a href="{{ route('valutazione.gestione.index') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Gestisci qualifiche</span>
		                </span>
						</a>
					</li>
					<li class="@tab('gestione.archivio.index')">
						<a href="{{ route('valutazione.gestione.archivio.index') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Archivio qualifiche</span>
		                </span>
						</a>
					</li>
					@endamministratori
                    @endif
				</ul>
			</nav>
		@endif

        @if($group === 'sondaggio')
		<nav class="side-menu-addl">
		    <header class="side-menu-addl-title">
		        <div class="caption">Sondaggio</div>
		    </header>
		    <ul class="side-menu-addl-list purple">
                <li class="@tab('modello.lista')">
                    <a href="{{ route('sondaggio.modello.lista') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Lista modelli sondaggio</span>
		                </span>
                    </a>
                </li>
                <li class="@tab('modello.crea')">
                    <a href="{{ route('sondaggio.modello.crea') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Crea modello sondaggio</span>
		                </span>
                    </a>
                </li>
                <li class="@tab('inizia')">
                    <a href="{{ route('sondaggio.inizia') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Inizia sondaggio</span>
		                </span>
                    </a>
                </li>
		    </ul>
		</nav>
        @endif

        @if($group === 'pannello')
        <nav class="side-menu-addl">
            <header class="side-menu-addl-title">
                <div class="caption">Pannello Gestionale</div>
            </header>
            <ul class="side-menu-addl-list green">
				@admin
				<li class="@tab('file')">
					<a href="{{ route('pannello.file') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Gestione file</span>
		                </span>
					</a>
				</li>
				<li class="divider"></li>
                <li class="@tab('amministratori.index')">
                    <a href="{{ route('pannello.amministratori.index') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Amministratori</span>
                            {{--
                                <span class="tbl-cell tbl-cell-num">0</span>
                            --}}
		                </span>
                    </a>
                </li>
				<li class="@tab('segretarie.index')">
					<a href="{{ route('pannello.segretarie.index') }}">
		                <span class="tbl-row">
		                    <span class="tbl-cell tbl-cell-caption">Segretarie</span>
		                </span>
					</a>
				</li>
				@endadmin
            </ul>
        </nav>
        @endif
