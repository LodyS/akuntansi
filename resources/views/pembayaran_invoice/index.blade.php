@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Pembayaran Invoice</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
    <!-- <a class="btn btn-block btn-primary data-modal" id="data-modal" href="#" onclick="show_modal('{{ route('pembayaran-invoice.create') }}')" >Tambah</a>-->
     </div>
   </div>
   <div class="page-content">
     <!-- Panel Table Tools -->
     <div class="panel">
       <header class="panel-heading">
         <div class="form-group col-md-12">
           <div class="form-group">
         <!-- <h3 class="panel-title">Table Tools</h3> -->

           </div>
         </div>
       </header>
       <div class="panel-body">
       <form name="formCari" action="" method="post">
       
        <div class="form-group row">
          <label class="col-md-3">Customer</label>
            <div class="col-md-7">
            <input type="text" name="customer" class="form-control">
          </div>
        </div>

      <div class="form-group row">
        <label class="col-md-3">Number</label>
          <div class="col-md-7">
          <input type="text" name="number" class="form-control">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-3">Invoice Date :</label>
          <div class="col-md-7">
          <input type="date" name="invoice_date" class="form-control">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-3">Due Date :</label>
          <div class="col-md-7">
          <input type="date" name="due_date"  class="form-control">
        </div>
      </div>

      <button type="button" id="submit" align="right" class="btn btn-primary">Cari</button>
    </form><br/><br/>

         <table class="table table-hover dataTable table-striped w-full" id="pembayaran-invoice-table">
           <thead>
               <tr>
                 <th>No</th>
                                           <th>Customer</th>
                                    <th>Number</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Payment</th>
                                    <th>Total</th>
                                    <th>Pembayaran</th>
                                    <th>Sisa Tagihan</th>
                                   
                                 
                       <th>Action</th>
               </tr>
           </thead>
         </table>
       </div>
     </div>
     <!-- End Panel Table Tools -->
 </div>
 <div class="modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
 </div>

@endsection

@push('js')
<script type="text/javascript">
$(function() {
  $('.trash-ck').click(function(){
    if ($('.trash-ck').prop('checked')) {
      document.location = '{{ url("pembayaran-invoice?status=trash") }}';
    } else {
      document.location = '{{ url("pembayaran-invoice") }}';
    }
  });
    const table = $('#pembayaran-invoice-table').DataTable({
      stateSave: true,
    processing : true,
    serverSide : true,
    searching : false,
    pageLength:20,
        ajax : {
                  url:"{{ url('pembayaran-invoice/load-data') }}",
                data: function (d) {
                  const form = document.forms.namedItem("formCari")
                  d.customer = form.customer.value;
                  d.number = form.number.value;
                  d.invoice_date = form.invoice_date.value;
                  d.due_date = form.due_date.value;

                }
      },
        columns: [
          { data: 'nomor', name: 'nomor',searchable:false,orderable:false },
                      { data: 'pelanggan', name: 'pelanggan' },
                      { data: 'number', name: 'number' },
                      { data: 'invoice_date', name: 'invoice_date' },
                      { data: 'due_date', name: 'due_date' },
                      { data: 'payment', name: 'payment' },
                      { data: 'total', name: 'total' },
                      { data: 'pembayaran', name: 'pembayaran' },
                      { data: 'sisa_tagihan', name: 'sisa_tagihan' },
                     
                   
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
    $('#submit').click(function(){
        table.ajax.reload();
    });
});
</script>
@endpush
