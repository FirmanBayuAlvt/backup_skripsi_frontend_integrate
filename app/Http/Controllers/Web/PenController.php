<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\BackendApiService;
use Illuminate\Http\Request;

class PenController extends Controller
{
    protected $api;

    public function __construct(BackendApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        return view('pens.index');
    }

    public function show($id)
    {
        $response = $this->api->getPenDetail($id);
        if (!($response['success'] ?? false)) abort(404);
        return view('pens.show', ['pen' => $response['data']]);
    }

    public function analytics($id)
    {
        $response = $this->api->getPenAnalytics($id);
        if (!($response['success'] ?? false)) abort(404);
        return view('pens.analytics', ['data' => $response['data']]);
    }

    public function getPensData(Request $request)
    {
        return response()->json($this->api->getPens($request->all()));
    }

    public function getPenDetail($id)
    {
        return response()->json($this->api->getPenDetail($id));
    }

    public function getPenAnalytics($id)
    {
        return response()->json($this->api->getPenAnalytics($id));
    }

    public function storePen(Request $request)
    {
        return response()->json($this->api->createPen($request->all()));
    }

    public function importPens(Request $request)
    {
        $file = $request->file('file');
        if (!$file) {
            return response()->json(['success' => false, 'message' => 'File tidak ditemukan']);
        }
        return response()->json($this->api->importPens($file));
    }
}
