<?php

namespace App\Http\Controllers;

use App\Models\NewsLetter;
use App\Utils\Util;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NewsLetterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:news_letters.list|news_letters.add|news_letters.edit|news_letters.delete|news_letters.status', ['only' => ['index', 'show']]);
        $this->middleware('permission:news_letters.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:news_letters.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:news_letters.delete', ['only' => ['destroy']]);
        $this->middleware('permission:news_letters.status', ['only' => ['status']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_data = NewsLetter::getTableData();
        $ajax_url = $common_data['ajax_url'];
        return view('common.index', compact('common_data', 'ajax_url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $delete = NewsLetter::find($id);
            Util::activityLog('NewsLetter', 'Deleted', $delete);
            $delete->delete();
            $response = ['status' => 'success', 'message' => __('translation.DeletedSuccessfully')];
            $status_code = 200;
        } catch (\Exception $th) {
            //Log error
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    //Get the ajax data
    public function getAjaxData()
    {
        if (request()->ajax()) {

            $query =  NewsLetter::orderBy('id', 'desc');
            $data = $query->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date('d/m/Y h:i A', strtotime($row->created_at));
                })

                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('news_letters.delete')) {
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete_record" data-url="' . route('news-letters.destroy', $row->id) . '" title="' . __('translation.Delete') . '"> <i class="fas fa-trash"></i></button>&nbsp;';
                    }
                    return $btn;
                })
                ->rawColumns(['DT_RowIndex', 'created_at', 'action'])
                ->make(true);
        }
    }
}
