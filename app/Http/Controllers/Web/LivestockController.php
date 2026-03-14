<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\BackendApiService;
use Illuminate\Http\Request;

class LivestockController extends Controller
{
    protected $api;

    public function __construct(BackendApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        return view('livestocks.index');
    }

    public function show($id)
    {
        $response = $this->api->getLivestockDetail($id);
        if (!($response['success'] ?? false)) {
            abort(404);
        }
        return view('livestocks.show', ['livestock' => $response['data']]);
    }

    public function getLivestocksData(Request $request)
    {
        return response()->json($this->api->getLivestocks($request->all()));
    }

    public function getLivestockDetail($id)
    {
        return response()->json($this->api->getLivestockDetail($id));
    }

    public function storeLivestock(Request $request)
    {
        return response()->json($this->api->createLivestock($request->all()));
    }

    public function recordWeight(Request $request, $id)
    {
        return response()->json($this->api->recordWeight($id, $request->all()));
    }

    public function importLivestocks(Request $request)
    {
        $file = $request->file('file');
        if (!$file) {
            return response()->json(['success' => false, 'message' => 'File tidak ditemukan']);
        }
        return response()->json($this->api->importLivestocks($file));
    }
}
