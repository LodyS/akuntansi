
 <div class="table-responsive">
    <div class="text-left">
  <a class="btn btn-block btn-primary data-modal btn-sm col-1" id="data-modal" href="#" onclick="show_modal('{{ route('config-id.create') }}')" >Tambah</a>
</div>
<br/>
 <table class="table table-hover dataTable table-striped w-full" id="config-id-table">
   <thead>
     <tr>
       <th>No</th>
       <th>Config Name</th>
       <th>Table Source</th>
       <th>Config Value</th>
       <th>Description</th>
       <th>Action</th>
     </tr>
   </thead>
 </table>
</div>
  <div class="modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
 </div>

@push('js')
<script type="text/javascript">
$(function() {
  $('.trash-ck').click(function(){
    if ($('.trash-ck').prop('checked')) {
      document.location = '{{ url("config-id?status=trash") }}';
    } else {
      document.location = '{{ url("config-id") }}';
    }
  });
    $('#config-id-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
        ajax : {
                  url:"{{ url('config-id/load-data') }}",
                data: function (d) {

                }
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                                      { data: 'config_name', name: 'config_name' },
                      { data: 'table_source', name: 'table_source' },
                      { data: 'config_value', name: 'config_value' },
                      { data: 'description', name: 'description' },
                      { data: 'action', name: 'action', orderable: false, searchable: false },
      ],
      language: {
            lengthMenu : '{{ "Menampilkan _MENU_ data" }}',
            zeroRecords : '{{ "Data tidak ditemukan" }}' ,
            info : '{{ "_PAGE_ dari _PAGES_ halaman" }}',
            infoEmpty : '{{ "Data tidak ditemukan" }}',
            infoFiltered : '{{ "(Penyaringan dari _MAX_ data)" }}',
            loadingRecords : '{{ "Memuat data dari server" }}' ,
            processing :    '{{ "Memuat data data" }}',
            search :        '{{ "Pencarian:" }}',
            paginate : {
                first :     '{{ "<" }}' ,
                last :      '{{ ">" }}' ,
                next :      '{{ ">>" }}',
                previous :  '{{ "<<" }}'
            }
        },
        aoColumnDefs: [{
          bSortable: false,
          aTargets: [-1]
        }],
        iDisplayLength: 5,
        aLengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        // sDom: '<"dt-panelmenu clearfix"Bfr>t<"dt-panelfooter clearfix"ip>',
        // buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
    });
});
</script>
@endpush