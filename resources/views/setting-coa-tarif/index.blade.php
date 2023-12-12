@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Rekening COA Tarif</h1>

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
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true">Setting Tarif Berdasarkan Kunjungan</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="rawat-jalan" class="dropdown-item" >Rawat Jalan</a>
                <a href="rawat-inap" class="dropdown-item" >Rawat Inap</a>
                </div>
                </li>
            </ul>
        </div>

      

        </div>
    </div>
</div>
@endsection