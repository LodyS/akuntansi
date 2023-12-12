@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting Perubahan Ekuitas</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <table class="table table-hover" id="laporan-neraca-saldo">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Jenis Perubahan Ekuitas</th>
                    <th>Induk</th>
                    <th>Level</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                </tr>
                @php ($i=1)
                @foreach ($data as $rekap)
                <tr>
                    <td>{{ $i}}</td>
                    <td>{{ $rekap->kode }}</td>
                    <td>{{ $rekap->nama }}</td>
                    <td>{{ $rekap->induk }}</td>
                    <td>{{ $rekap->level }}</td>
                    <td>{{ $rekap->jenis }}</td>
                    @if ($rekap->level ==0)
                    <td></td>
                    @else
                    <td><a href="detail/{{$rekap->id}}" class="btn btn-xs btn-outline-primary">Detail</a>
                    @endif
                </tr>
                @php($i++)
                @endforeach
            </table>
            {{ $data->links() }}
        </div>
    </div>
</div>

@endsection

@push('js')
<script type="text/javascript">
$(".select").select2({
    dropdownParent : $("#laporan"),
    width : '100%'
});

</script>
@endpush
