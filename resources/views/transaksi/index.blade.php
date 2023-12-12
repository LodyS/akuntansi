@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Saldo Awal</h1>
        @include('layouts.inc.breadcrumb')
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
    <form action="{{ url()->current() }}">
    <div class="form-group row justify-content-end">
        <div class="input-group col-4 ">
            <input type="text" class="form-control" name="keyword" placeholder="Cari..." value="{{ request('keyword') }}">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary waves-effect waves-classic"><i class="icon md-search" aria-hidden="true"></i></button>
            </span>
        </div>
    </div>
    </form>

    <table class="table table-hover dataTable table-striped w-full" id="transaksi-table">

        <tr>
            <th>ID Perkiraan</th>
            <th>Nama Perkiraan </th>
            <th>Debet</th>
            <th>Kredit</th>
            <th>Aksi</th>
        </tr>

    <form action="{{ url('insert') }}" method="post">{{ @csrf_field() }}

        @foreach ($perkiraan as $kira)
            <tr>
                <td>{{ $kira->id }}</td>
                <td>{{ $kira->perkiraan }}</td>
                <td>Rp. {{ number_format($kira->debet,2, ",", ".") }}</td>
                <td>Rp. {{ number_format($kira->kredit,2, ",", ".") }}</td>
                <td><a href="transaksi/edit/{{ $kira->id }}" class="btn btn-success">Edit</a>
            </tr>
        @endforeach
    </table>
    {!! $perkiraan->appends(Request::except('page'))->render() !!}
    {{-- {{ $perkiraan->links() }} --}}

        </div>
    </div>
 </div>
@endsection
