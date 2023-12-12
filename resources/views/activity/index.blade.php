@extends('layouts.app')

@section('content')
{{-- {{dd('aaaa')}} --}}
{{-- <div class="container"> --}}
  <style>
    .panel-heading h3 {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: normal;
    width: 75%;
    padding-top: 8px;
}

  .ajax-loader{
    position:fixed;
    top:0px;
    right:0px;
    width:100%;
    height:auto;
    background-color:#A9A9A9;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    opacity: 0.7;
    filter: alpha(opacity=40); /* For IE8 and earlier */
  }

  .ui-datepicker {
   background: #333;
   border: 1px solid #555;
   color: #EEE;
}

#custom-radio-buttons .radio-wrapper {display: inline-block;}
		#custom-radio-buttons .radio-wrapper input[name="custom-radio"] {display: none;}
		#custom-radio-buttons .radio-wrapper input[name="custom-radio"] + label {
			color: #292321;
			font-family: Arial, sans-serif;
			font-size: 14px;
		}
		#custom-radio-buttons .radio-wrapper input[name="custom-radio"] + label > span.outer {
			display: inline-block;
			width: 16px;
			height: 16px;
			margin: -1px 4px 0 0;
			border: 2px solid #cccccc;
			vertical-align: middle;
			cursor: pointer;
			position: relative;
			-moz-border-radius: 50%;
			border-radius: 50%;
			background-color: #f8f8f8;
		}
		#custom-radio-buttons .radio-wrapper input[name="custom-radio"] + label span.inner {
			display: block;
			position: absolute;
			display: none;
			width: 10px;
			height: 10px;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			margin: auto;
			vertical-align: middle;
			cursor: pointer;
			-moz-border-radius: 50%;
			border-radius: 50%;
			background-color: grey;
		}
		#custom-radio-buttons .radio-wrapper input[name="custom-radio"]:checked + label span.inner {background-color: #CC3300; display: block;}

  </style>
<div class="ajax-loader" style="display: none">
	<div class="col-md-12">
		<div class="progress progress-striped active">
			<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
				{{-- <span class="sr-only">40% Complete (success)</span> --}}
			</div>
		</div> 
		<div id="status" style="font-size:8pt;font-family: sans-serif;color: white">Loading...Please Wait</div>  
	</div>
</div>
<div class="page-header">
	<h1 class="page-title">User Activity Log</h1>
	@include('layouts.inc.breadcrumb')
	<div class="page-header-actions">

	</div>
</div>
   <div class="page-content">
     <!-- Panel Table Tools -->
     <div class="panel">
       <header class="panel-heading">
       	<br/>
       	<div class="row col-lg-12" style="margin-bottom:1em;font-family: sans-serif;">
       		<div class="col-lg-2">
       			<span>Filter by :</span>
       		</div>
       		<div class="col-lg-4">
       			<section id="custom-radio-buttons">	
       				<div class="radio-wrapper">
       					<input type="radio" id="radio1" name="custom-radio" value="all" checked/>
       					<label for="radio1">
       						<span class="outer">
       							<span class="inner animated"></span>
       						</span>
       						All
       					</label>
       				</div>
       				&nbsp&nbsp&nbsp&nbsp
       				<div class="radio-wrapper">
       					<input type="radio" id="radio2" name="custom-radio" value="by_date" />
       					<label for="radio2">
       						<span class="outer">
       							<span class="inner animated"></span>
       						</span>
       						By Date Range
       					</label>
       				</div>
       			</section>
       		</div>
       	</div>
       	<div class="row col-lg-12 date_range" style="margin-bottom:1em;font-family: sans-serif;display: none">
       		<div class="col-lg-2">
       			<span>Date Range :</span>
       		</div>
       		<div class="col-lg-2">
       			<input name="date_from"  type="datepicker" id="date_from" class="form-control form-control-sm datepicker"  value="{{date('d-m-Y')}}" type="text">
       		</div>
       		<div class="col-lg-2">
       			<input name="date_to"  type="datepicker" id="date_to" class="form-control form-control-sm datepicker" value="{{date('d-m-Y',strtotime('+7 days'))}}" type="text">
       		</div>
       	</div>

       	<div class="row col-lg-12" style="margin-bottom:1em;font-family: sans-serif;">
       		<div class="col-lg-6">
       			<div class="text-right">
       				<button class="btn btn-primary btn-sm" id="cari">Cari</button>
       				<button class="btn btn-outline-success btn-sm" id="reset">Reset</button>
       			</div>
       		</div>
       	</div>
       	<br/>
       </header>
       <div class="panel-body">
				<!-- DataTables functionality is initialized with .js-dataTable-full-pagination class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
				{{-- <table class="table table-bordered table-striped table-vcenter" id="table-1">
					<thead>
						<tr>
							<th>No</th>
							<th>Description</th>
							<th>User</th>
							<th>Properties</th>
							<th>Time</th>
						</tr>
					</thead>
					<tbody id="activity_log">
					</tbody>
				</table> --}}
				<div id="table_data" style="font-family: sans-serif;">
					@include('activity.index-data')
				</div>
			</div>
		</div>
		<!-- END Dynamic Table Full Pagination -->

	</div>
	<!-- END Content -->
<!-- END Page Content -->
@endsection
@push('js')
<script type="text/javascript">
	var formData = new FormData();
	$(document).ready(function(){
		$('.datepicker').datepicker({
    // placement: 'button',
    format: "dd-mm-yyyy",
    align: 'left',
    autoclose: true,
})

		$("#custom-radio-buttons .radio-wrapper input[type='radio'] + label").click(function(){
			$("#custom-radio-buttons .radio-wrapper input[type='radio'] + label span.inner").removeClass("bounceIn");
			$(this).find("span.inner").addClass("bounceIn");
		});

		$("input[type='radio']").bind('click change',function(){
			var radioValue = $("input[name='custom-radio']:checked").val();
			if(radioValue=='by_date')
			{
				$('.date_range').fadeIn();
			}
			else
			{
				$('.date_range').fadeOut();
			}
		})

		$(document).on('click', '.pagination a', function(event){
			event.preventDefault(); 
			var page = $(this).attr('href').split('page=')[1];
			fetch_data(page,$("input[name='custom-radio']:checked").val());
		});
	
		$(document).on('click','#cari',function(event){
			event.preventDefault();
			if($('.pagination a').length==0)
			{
				var page=1;
				fetch_data(page,$("input[name='custom-radio']:checked").val());
			} 
			else
			{
				var page = 1;
				fetch_data(page,$("input[name='custom-radio']:checked").val());
			}
		})

		$(document).on('click','#reset',function(event){
			event.preventDefault();
			$('#radio1').prop('checked', true);
			$('.date_range').fadeOut();
			$('#date_from').val('{{date('d-m-Y')}}');
			$('#date_to').val('{{date('d-m-Y',strtotime('+7 days'))}}');
			if($('.pagination a').length==0)
			{
				var page=1;
				fetch_data(page,$("input[name='custom-radio']:checked").val());
			} 
			else
			{
				var page = 1;
				fetch_data(page,$("input[name='custom-radio']:checked").val());
			}
		})
	})

function fetch_data(page,mode)
{
	var data_ajax='';
	if(mode=='all')
	{
		data_ajax={'show':mode};
	}
	else if(mode=='by_date')
	{
		data_ajax={ 'show':mode,'date_from':$('#date_from').val(),'date_to':$('#date_to').val() };
	}

	$('.ajax-loader').fadeIn();
    $("#status").html("Loading...Please Wait!");
	$.ajax({
		url:"{{url('activity/get-data')}}?page="+page,
		data:data_ajax,
		headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
		xhr: function () {
		var xhr = new window.XMLHttpRequest();
		xhr.upload.addEventListener("progress",
			uploadProgressHandler,
			false
			);
		xhr.addEventListener("load", loadHandler, false);
		xhr.addEventListener("error", errorHandler, false);
		xhr.addEventListener("abort", abortHandler, false);

		return xhr;
	},
		success:function(data)
		{
			$('#table_data').html(data);
		}
	});
}

function uploadProgressHandler(event) {
    // $("#loaded_n_total").html("Uploaded " + event.loaded + " bytes of " + event.total);
    var percent = (event.loaded / event.total) * 100;
    var progress = Math.round(percent);
    $("#percent").html(progress + "%");
    $(".progress-bar").css("width", progress + "%");
    $("#status").html(progress + "% uploaded... please wait");
}

function loadHandler(event) {
	$("#status").html('Load Completed');
	setTimeout(function(){
		$('.ajax-loader').fadeOut()
		$("#percent").html("0%");
		$(".progress-bar").css("width", "100%");
	}, 500);
}

function errorHandler(event) {
	$("#status").html("Send Data Failed");
}

function abortHandler(event) {
	$("#status").html("Send Data Aborted");
}
</script>
@endpush