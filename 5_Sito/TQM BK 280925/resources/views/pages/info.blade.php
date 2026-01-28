@extends('layouts.master', ['group' => 'info'])

@section('content')
		    <div class="container-fluid">
		        <div class="row">
		            <div class="col-xl-6">
		                <section class="box-typical box-typical-dashboard panel panel-default">
		                    <header class="box-typical-header panel-heading">
		                        <h3 class="panel-title">Responsabili</h3>
		                    </header>
		                    <div class="box-typical-body panel-body">
		                        <div class="contact-row-list">
		                            <article class="contact-row">
		                                <div class="user-card-row">
		                                    <div class="tbl-row">
		                                        <div class="tbl-cell">
		                                            <p class="user-card-row-name">Alessandro Narciso</p>
													<p class="user-card-row-name">Ex allievo SAMT sezione informatica</p>
		                                        </div>
		                                        <div class="tbl-cell tbl-cell-status">Sviluppatore</div>
		                                    </div>
		                                </div>
		                            </article>
		                            <article class="contact-row">
		                                <div class="user-card-row">
		                                    <div class="tbl-row">
		                                        <div class="tbl-cell">
		                                            <p class="user-card-row-name">CPT Trevano</p>
		                                            <p class="user-card-row-mail"><a href="mailto:decs-cpt.trevano@edu.ti.ch">decs-cpt.trevano@edu.ti.ch</a></p>
		                                        </div>
		                                        <div class="tbl-cell tbl-cell-status">Committente</div>
		                                    </div>
		                                </div>
		                            </article>
									<article class="contact-row">
										<div class="user-card-row">
											<div class="tbl-row">
												<div class="tbl-cell">
													<p class="user-card-row-name">Cecilia Beti</p>
													<p class="user-card-row-mail"><a href="mailto:cecilia.beti@edu.ti.ch">cecilia.beti@edu.ti.ch</a></p>
												</div>
												<div class="tbl-cell tbl-cell-status">Direttrice</div>
											</div>
										</div>
									</article>
		                        </div>
		                    </div><!--.box-typical-body-->
		                </section><!--.box-typical-dashboard-->
		            </div><!--.col-->
		        </div>
		    </div><!--.container-fluid-->
@endsection
