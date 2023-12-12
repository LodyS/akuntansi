<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models;

class AutocompleteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function search($method, Request $r)
    {
        return $this->$method($r);
    }

    //autocomplete goes here
    private function permission($r)
    {
        $q = $r->input("q");
        $query = Models\Permission::select(DB::raw("permissions.*"))
            ->where("name", "like", "%$q%")
            ->limit(20);
        $results = $query->get();
        return response()->json($results->toArray());
    }
    private function parent($r)
    {
        $q = $r->input("q");
        $query = Models\Menu::select(DB::raw("menus.*"))
            ->where("name", "like", "%$q%")
            ->limit(20);
        $results = $query->get();
        return response()->json($results->toArray());
    }
    private function role($r)
    {
        $q = strtoupper($r->input("q"));
        $query = \App\Role::select(DB::raw("*"))
            ->where("name", "like", "%$q%")
            ->limit(20);
        $results = $query->get();
        return response()->json($results->toArray());
    }
    private function pelanggan($r)
    {
        $q = strtoupper($r->input("q"));
        $query = \App\Models\Pelanggan::select(DB::raw("*"))
            ->where("nama", "like", "%$q%")
            ->orWhere("kode", "like", "%$q%")
            ->limit(20);
        $results = $query->get();
        return response()->json($results->toArray());
    }
    private function provinsi($r)
    {
        $q = strtoupper($r->input("q"));
        $query = \App\Models\Provinsi::where("provinsi", "like", "%$q%")->where('flag_aktif','Y')->limit(20);
        $results = $query->get();
        return response()->json($results->toArray());
    }
    private function kabupaten($r)
    {
        $q = strtoupper($r->input("q"));
        $query = \App\Models\Kabupaten::selectRaw('kabupaten.id, CONCAT(kabupaten.kabupaten, " - ", provinsi.provinsi) AS kabupaten')
        ->where("kabupaten.kabupaten", "like", "%$q%")->where('kabupaten.flag_aktif','Y')
        ->join('provinsi', 'provinsi.id', 'kabupaten.id_provinsi')
        ->limit(20);
        $results = $query->get();
        // dd($results);
        return response()->json($results->toArray());
    }
    private function kecamatan($r)
    {
        $q = strtoupper($r->input("q"));
        $query = \App\Models\Kecamatan::selectRaw('kecamatan.id, CONCAT(kecamatan.kecamatan, " - ", kabupaten.kabupaten) AS kecamatan')
        ->where("kecamatan.kecamatan", "like", "%$q%")->where('kecamatan.flag_aktif','Y')
        ->join('kabupaten', 'kabupaten.id', 'kecamatan.id_kabupaten')
        ->limit(20);
        $results = $query->get();
        // dd($results);
        return response()->json($results->toArray());
    }
}
