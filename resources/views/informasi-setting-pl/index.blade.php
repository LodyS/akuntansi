@extends('layouts.app')

@section('content')

<div class="container pt-70">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">

                    <!-- Akhir laporan keuangan -->
                   <h3 align="center">Cost Center dan Rekening yang harus di Setting PNL</h3>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 pb-30">
                                <table class="table table-hover">

                                    <h4>Cost Centre</h4>

                                    <tr>
                                        <td>No</td>
                                        <td>Unit</td>
                                        <td>Code Cost Centre</td>
                                    </tr>
                                    @foreach($surplusUnit as $key=> $unit)
                                    <tr>
                                        <td>{{ $key + $surplusUnit->firstItem() }}</td>
                                        <td>{{ $unit->nama }}</td>
                                        <td>{{ $unit->code_cost_centre }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                {{ $surplusUnit->appends(request()->toArray())->links() }}
                            </div>

                        <div class="col-md-6 pb-30">
                            <table class="table table-hover">

                                <h4>Rekening</h4>

                                <tr>
                                    <td>No</td>
                                    <td>Nama</td>
                                    <td>Kode Rekening</td>
                                </tr>
                                @foreach($surplusPerkiraan as $key=> $perkiraan)
                                <tr>
                                    <td>{{ $key + $surplusUnit->firstItem() }}</td>
                                    <td>{{ $perkiraan->nama }}</td>
                                    <td>{{ $perkiraan->kode_rekening }}</td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $surplusPerkiraan->appends(request()->toArray())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



