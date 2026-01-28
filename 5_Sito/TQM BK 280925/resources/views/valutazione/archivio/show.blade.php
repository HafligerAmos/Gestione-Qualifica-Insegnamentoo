@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'mie'])
@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <section class="box-typical scrollable">
            <header class="box-typical-header">
                <div class="tbl-row">
                    <div class="tbl-cell tbl-cell-title">
                        <h3>Report</h3>
                    </div>
                    <div class="tbl-cell tbl-cell-action-bordered">
                        <button class="btn btn-primary" disabled>Stampa</button>
                    </div>
                </div>
            </header>
            <div class="box-typical-body">
                <section>
                    <table id="docente" class="row-border order-column display table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Cognome Nome</th>
                            <th>Materia insegnata</th>
                            <th>Professione</th>
                            <th>Classe interessata</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $valutazione->cognome_docente.' '.$valutazione->nome_docente }}</td>
                            <td>{{ $valutazione->materia }}</td>
                            <td>{{ $valutazione->professione }}</td>
                            <td>{{ $valutazione->nome_classe }}</td>
                        </tr>
                        </tbody>
                    </table>
                </section>
                <p style="padding-left:10px;font-weight:bold">Riassuntivo in forma tabellare</p>
                <section>
                    <table id="media" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th></th>
                            @foreach($valutazione->risposte as $risposta)
                                <th>{{ substr($risposta->nome_categoria, 0, 2) }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>MEDIA ALLIEVI</td>
                            @foreach($valutazione->risposte as $risposta)
                                <td>{{ $risposta->media_pif }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>DOCENTE</td>
                            @foreach($valutazione->risposte as $risposta)
                                <td>{{ $risposta->docente }}</td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </section>
                <p style="padding-left:10px;font-weight:bold">Legenda</p>
                <p style="padding-left:10px">@foreach($valutazione->risposte as $risposta){{ substr($risposta->nome_categoria, 0, 2) }} = {{ $risposta->nome_categoria }}&nbsp;&nbsp;&nbsp;@endforeach</p>
                <p style="padding-left:10px;font-weight:bold">Riassuntivo in forma grafica</p>
                <section style="width:100%;margin-bottom:20px">
                    <canvas id="graph" width="350" height="350" style="max-width:600px !important; max-height:600px !important;margin:auto"></canvas>
                </section>
            </div><!--.box-typical-body-->
        </section><!--.box-typical-->
    </div>
@endsection

@section('foot')
    <script type="text/javascript" src="{{ asset('assets/js/lib/chartjs/chart.min.js') }}"></script>
    <script>
        new Chart($("#graph"), {
            type: 'radar',
            data: {
                labels: [@foreach($valutazione->risposte as $risposta)"{{ substr($risposta->nome_categoria, 0, 2) }}",@endforeach],
                datasets: [
                    {
                        label: 'DOCENTE',
                        data: [@foreach($valutazione->risposte as $risposta){{ $risposta->docente }},@endforeach],
                        borderColor: "rgb(255, 99, 132)",
                        fill: true,
                        backgroundColor:"rgba(255, 99, 132, 0.2)",
                        borderColor: "rgb(255, 99, 132)",
                        pointBackgroundColor:"rgb(255, 99, 132)",
                        pointBorderColor:"rgb(255, 99, 132)",
                        pointHoverBackgroundColor:"#fff",
                        pointHoverBorderColor:"rgb(255, 99, 132)",
                        borderWidth: 3
                    },
                    {
                        label: 'MEDIA ALLIEVI',
                        data: [@foreach($valutazione->risposte as $risposta){{ $risposta->media_pif }},@endforeach],
                        fill: true,
                        backgroundColor:"rgba(54, 162, 235, 0.1)",
                        borderColor: "rgb(54, 162, 235)",
                        pointBackgroundColor:"rgb(54, 162, 235)",
                        pointBorderColor:"rgb(54, 162, 235)",
                        pointHoverBackgroundColor:"#fff",
                        pointHoverBorderColor:"rgb(54, 162, 235)",
                        pointBorderWidth: 4
                    },
                ]
            },
            options:{
                elements:{
                    line:{
                        tension:0,
                        borderWidth:3
                    }
                },
                scale: {
                    ticks: {
                        beginAtZero: true,
                        max: {{ $valutazione->opzioni * $valutazione->risposte->groupBy('nome_categoria')->count() }},
                    },
					pointLabels: {
			      		fontSize: 16
				    }
                }
            },
        });
    </script>
@endsection
