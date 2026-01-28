@extends('layouts.master', ['group' => 'valutazione', 'tab' => 'mie'])
@section('content')
    <div class="container-fluid">
        @include('layouts.message')
        <section class="box-typical scrollable">
            <header class="box-typical-header">
                <div class="tbl-row">
                    <div class="tbl-cell tbl-cell-title">
                        <h3>Report di media</h3>
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
                            <th>Classi interessate</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $docente->cognome.' '.$docente->nome }}</td>
                            <td>@foreach($valutazioni as $valutazione) {{ $valutazione->docente_classe->materia.'; ' }} @endforeach </td>
                            <td>{{ '-' }}{{-- $valutazione->allievi->first()->professione --}}</td>
                            <td>@foreach($valutazioni as $valutazione) {{ $valutazione->classe->nome.'; ' }} @endforeach </td>
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
                            @foreach($categorie as $categoria)
                                <th>{{ $categoria->abb }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>MEDIA ALLIEVI</td>
                            @foreach($categorie as $categoria)
                                <td>{{ $valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('media_pif') }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>DOCENTE</td>
                            @foreach($categorie as $categoria)
                                <td>{{ $valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('risposta') }}</td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </section>
                <p style="padding-left:10px;font-weight:bold">Legenda</p>
                <p style="padding-left:10px">@foreach($categorie as $categoria){{ $categoria->abb }} = {{ $categoria->nome }}&nbsp;&nbsp;&nbsp;@endforeach</p>
                <p style="padding-left:10px;font-weight:bold">Riassuntivo in forma grafica</p>
                <section style="width:100%">
                    <canvas id="graph" width="350" height="350" style="max-width:750px !important; max-height:750px !important;margin:auto"></canvas>
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
                labels: [@foreach($categorie as $categoria)"{{ $categoria->abb }}",@endforeach],
                datasets: [
                    {
                        label: 'DOCENTE',
                        data: [@foreach($categorie as $categoria){{ $valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('risposta') }},@endforeach],
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
                        data: [@foreach($categorie as $categoria){{ $valutazione->risposte->where('domanda.categoria.id', $categoria->id)->sum('media_pif') }},@endforeach],
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
                        max: {{ $valutazione->sondaggio->opzioni * $valutazione->risposte->groupBy('domanda.categoria.id')->max()->count() }},
                    }
                }
            },
        });
    </script>
@endsection
