<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Http\Requests;
use Illuminate\Support\Facades\Schema;
use DatePeriod;
use DateTime;
use DateInterval;
use Carbon\Carbon;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
date_default_timezone_set("Asia/Jakarta");
use App\Traits\ActivityTraits;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    //
	use ActivityTraits;
	public $viewDir = "activity";

	public function __construct()
	{
		$this->middleware('auth');
		// $this->middleware('permission:read-activity');
	}

	protected function view($view, $data = [])
	{
		return view($this->viewDir.".".$view, $data);
	}

	public function index()
	{
		// $this->menuAccess(Auth::user(),'Activity Log');
		$roles=Auth::user()->roles[0]->name;
		switch($roles)
		{
			case 'superadministrator':
				$data = Activity::orderby('id','desc')->paginate(10);
			break;
			default:
				$data = Activity::where('causer_id',Auth::user()->id)->orderby('id','desc')->paginate(10);
		}

		return $this->view('index',compact('data','roles'));
	}

	public function getData(Request $request)
	{
		if($request->ajax())
		{
			$roles=Auth::user()->roles[0]->name;

			
			$show=$request->input('show',null);

			switch($show)
			{
				case 'all':
				switch($roles)
				{
					case 'superadministrator':
					$data=Activity::orderby('id','desc')
					->paginate(10);
					break;
					default:
					$data=Activity::where('causer_id',Auth::user()->id)
					->orderby('id','desc')->paginate(10);
				}
				break;
				case 'by_date':
				$date_from=date('Y-m-d',strtotime($request->input('date_from',null)));
				$date_to=date('Y-m-d',strtotime($request->input('date_to',null)));

				switch($roles)
				{
					case 'superadministrator':
					$data=Activity::whereDate('created_at','>=',$date_from)
					->whereDate('created_at','<=',$date_to)
					->orderby('id','desc')
					->paginate(10);
					break;
					default:
					$data=Activity::where('causer_id',Auth::user()->id)
					->whereDate('created_at','>=',$date_from)
					->whereDate('created_at','<=',$date_to)
					->orderby('id','desc')->paginate(10);
				}
				break;
			}
			
			return $this->view('index-data',compact('data','roles'))->render();
		} 
	}

}
