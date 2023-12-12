<link href="{{ asset('css/app.css') }}" rel="stylesheet">
      <h4 class="modal-title" id="formModalLabel">Tambah Saldo Awal</h4>
    </div>
    <form action="{{ url('updateData') }}" method="post">
                            {{ @csrf_field() }}

   <table class="table table-hover">
        <tr>
            <th>No Akun</th>
            <th>Nama Akun</th>
            <th>Debit</th>
            <th>Kredit</th>
        </tr>
         @foreach ($tambah as $t)
        <tr>
           <td>{{ $t->id_perkiraan }}</td>
           <td>{{ $t->nama }}</td>
           <td><input type="text" class="form-control" name="debet" value="{{ $t->debet}}"></td>
           <td><input type="text" class="form-control" name="debet" value="{{ $t->kredit}}"></td>
        </tr>
        @endforeach
    </table>


