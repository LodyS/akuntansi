<?php

namespace App\Traits;
use App\Activity;
use carbon\carbon;
date_default_timezone_set("Asia/Jakarta");
use Jenssegers\Agent\Agent;

trait ActivityTraits
{
    //
	public function logCreatedActivity($logModel,$list_data,$menu,$table_name)
	{
		$agent = new Agent();
        $browser = $agent->browser();
        $version_browser = $agent->version($browser);

        $platform = $agent->platform();
        $version_platform = $agent->version($platform);

		$changes='Insert data '.implode(', ',array_values($list_data));
		$updated_at = Carbon::now()->format('d/m/Y H:i:s');
		$request['type']='Create';
		$request['description']='Create data '.$menu.' pada '.$updated_at.'';
		$request['menu']=$menu;
		$request['data']=$list_data;
		$request['table']=$table_name;
		$request['device']=$platform.' '.$version_platform;
		$request['browser']=$browser.' '.$version_browser;
		// array_push($request,'type'=>'Create');
		$activity = activity()
		->causedBy(\Auth::user())
		->performedOn($logModel)
		->withProperties(['attributes'=>$request])
		->log($changes.' oleh '.\Auth::user()->name);
		$lastActivity = Activity::all()->last();

		return true;
	}

	public function logUpdatedActivity($list,$before,$list_changes,$menu,$table_name)
	{
		// dd($before);
		$agent = new Agent();
        $browser = $agent->browser();
        $version_browser = $agent->version($browser);

        $platform = $agent->platform();
        $version_platform = $agent->version($platform);

		$updated_at = Carbon::now()->format('d/m/Y H:i:s');
		unset($list_changes['updated_at']);
		$old_keys = [];
		$old_value_array = [];
		if(empty($list_changes)){
			$changes = 'Tidak ada atribut / nilai yang dirubah';

		}else{

			if(count($before)>0){

				foreach($before as $key=>$original){
					if(array_key_exists($key,$list_changes)){

						$old_keys[$key]=$original;
					}
				}
			}
			$old_value_array = $old_keys;
			$changes = 'Update nilai '.implode(', ',array_keys($old_value_array)).' dengan '.implode(', ',array_values($old_value_array)).' ke '.implode(', ',array_values($list_changes));
		}

		$request['data']=$list_changes;
		$request['old']=$old_value_array;
		$request['type']='Update';
		$request['description']='Update data '.$menu.' pada '.$updated_at.'';
		$request['menu']=$menu;
		$request['table']=$table_name;
		$request['device']=$platform.' '.$version_platform;
		$request['browser']=$browser.' '.$version_browser;
		// $properties = [
		// 	'attributes'=>$list_changes,
		// 	'old' =>$old_value_array,
		// 	'type'=>'Delete',
		// 	'description'=>'Update data '.$menu.' pada '.$updated_at.'',
		// ];

		$activity = activity()
		->causedBy(\Auth::user())
		->performedOn($list)
		->withProperties(['attributes'=>$request])
		->log($changes.' oleh '.\Auth::user()->name);

		return true;
	}

	public function logDeletedActivity($list,$changeLogs,$menu,$table_name)
	{
		$agent = new Agent();
        $browser = $agent->browser();
        $version_browser = $agent->version($browser);

        $platform = $agent->platform();
        $version_platform = $agent->version($platform);

		$updated_at = Carbon::now()->format('d/m/Y H:i:s');
		$attributes = $this->unsetAttributes($list);

		// $properties = [
		// 	'attributes' => $attributes->toArray(),
		// 	'type'=>'Delete',
		// 	'description'=>'Hapus data '.$menu.' pada '.$updated_at.'',
		// ];
		$request['data']=$attributes->toArray();
		$request['type']='Delete';
		$request['description']='Hapus data '.$menu.' pada '.$updated_at.'';
		$request['menu']=$menu;
		$request['table']=$table_name;
		$request['device']=$platform.' '.$version_platform;
		$request['browser']=$browser.' '.$version_browser;

		$activity = activity()
		->causedBy(\Auth::user())
		->performedOn($list)
		->withProperties(['attributes'=>$request])
		->log($changeLogs);

		return true;
	}

	public function logLoginDetails($user)
	{
		$agent = new Agent();
        $browser = $agent->browser();
        $version_browser = $agent->version($browser);

        $platform = $agent->platform();
        $version_platform = $agent->version($platform);

		$updated_at = Carbon::now()->format('d/m/Y H:i:s');
		$properties = [
			'attributes' =>['description'=>''.$user->username.' melakukan login ke sistem pada '.$updated_at,'type'=>'Login','device'=>$platform.' '.$version_platform,'browser'=>$browser.' '.$version_browser]
		];

		$changes = 'User '.$user->name.' melakukan login';

		$activity = activity()
		->causedBy(\Auth::user())
		->performedOn($user)
		->withProperties($properties)
		->log($changes);

		return true;
	}

	public function logLogoutDetails($user)
	{
		$agent = new Agent();
        $browser = $agent->browser();
        $version_browser = $agent->version($browser);

        $platform = $agent->platform();
        $version_platform = $agent->version($platform);

		$updated_at = Carbon::now()->format('d/m/Y H:i:s');
		$properties = [
			'attributes' =>['description'=>''.$user->username.' melakukan logout dari sistem pada '.$updated_at,'type'=>'Logout','device'=>$platform.' '.$version_platform,'browser'=>$browser.' '.$version_browser]
		];

		$changes = 'User '.$user->name.' melakukan logout';

		$activity = activity()
		->causedBy(\Auth::user())
		->performedOn($user)
		->withProperties($properties)
		->log($changes);

		return true;
	}

	public function menuAccess($user,$menu)
	{
		$agent = new Agent();
        $browser = $agent->browser();
        $version_browser = $agent->version($browser);

        $platform = $agent->platform();
        $version_platform = $agent->version($platform);
        
		$updated_at = Carbon::now()->format('d/m/Y H:i:s');
		$properties = [
			'attributes' =>['description'=>''.$user->name.' mengakses menu '.$menu.' pada '.$updated_at,'type'=>'Access','menu'=>$menu,'device'=>$platform.' '.$version_platform,'browser'=>$browser.' '.$version_browser]
		];

		$changes = 'User '.$user->name.' telah mengakses ke menu '.$menu.'';

		$activity = activity()
		->causedBy(\Auth::user())
		->performedOn($user)
		->withProperties($properties)
		->log($changes);

		return true;
	}

	public function unsetAttributes($model){
		unset($model->created_at);
		unset($model->updated_at);
		return $model;
	}
}