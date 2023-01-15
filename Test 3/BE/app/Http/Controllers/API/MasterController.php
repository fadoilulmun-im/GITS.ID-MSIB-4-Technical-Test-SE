<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Validator;
use Schema;
use DB;
use App;

abstract class MasterController extends Controller
{
    abstract public function model();
    abstract public function rules() : array;
    abstract public function searchable() : array;

    public function getColumns()
    {
        return Schema::getColumnListing(App::make($this->model())->getTable());
    }

    public function select(){
        return $this->model()::select('*');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $per_page = $req->per_page ? intval($req->per_page) : $this->model()::count();
        $order = $req->order ? $req->order : 'DESC';
        $sortby = $req->sortby ? $req->sortby : 'id';

        $data = $this->select();
        if($req->filled('search')){
            $data->where(function($q) use($req) {
                foreach($this->searchable() as $k => $v){
                    if($k > 0){
                        $q->orWhere($v, 'ILIKE', '%'. $req->search .'%');
                    }else{
                        $q->where($v, 'ILIKE', '%'. $req->search .'%');
                    }
                }
            });
        }

        return setResponse($data->orderBy($sortby, $order)->paginate($per_page));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), $this->rules());
        if ($validator->fails()) {
            return setResponse(
                $validator->errors(),
                'Please fill in the form correctly',
                400,
            );
        }

        DB::transaction(function () use($req) {
            $this->model()::create($req->only($this->getColumns()));
        });

        return setResponse(null, null, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = intval($id);
        $data = $this->select()->where(App::make($this->model())->getTable() . '.id', $id)->first();
        if(!$data){
            return setResponse(null, null, 404);
        }

        return setResponse($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $id = intval($id);
        $data = $this->model()::where(App::make($this->model())->getTable() . '.id', $id)->first();
        if(!$data){
            return setResponse(null, null, 404);
        }

        $validator = Validator::make($req->all(), $this->rules());
        if ($validator->fails()) {
            return setResponse(
                $validator->errors(),
                'Please fill in the form correctly',
                400,
            );
        }

        DB::transaction(function () use($req, $data) {
            foreach($req->only($this->getColumns()) as $k => $v){
                $data->$k = $v;
            }
            $data->save();
        });

        return setResponse($data, 'Successfully updated data', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = intval($id);
        $data = $this->model()::where(App::make($this->model())->getTable() . '.id', $id)->first();
        if(!$data){
            return setResponse(null, null, 404);
        }

        DB::transaction(function () use($data) {
            $data->delete();
        });

        return setResponse($data, 'Successfully deleted data', 200);
    }
}
