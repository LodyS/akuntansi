@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting COA Invoice</h1>
        <div class="page-header-actions">
    </div>
</div>

   <div class="page-content">
        <div class="panel">
            <header class="panel-heading">
                <div class="form-group col-md-12">
                    <div class="form-group">
                </div>
            </div>
       </header>

        <div class="panel-body">
            <table class="table table-hover">
                <tr>
                <th>No</th>
                <th>Nama</th>
                <th>COA</th>
                <th>Aksi</th>
                </tr>


                @php ($i=1)
                <tr>
                    @foreach ($setting_coa as $setting)
                    <td>{{ $i }}</td>
                    <td>{{ $setting->nama }}</td>
                    <td>{{ $setting->coa }}</td>
                    <td><a href="/setting-coa-invoice/{{$setting->id}}/edit" class="btn btn-outline-success">Edit</a></td>
                </tr>
                    @php($i++)
                @endforeach
                </thead>
            </table>

            </div>
        </div>
    </form>
</div>
@endsection
