<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\BackendApiService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $api;

    public function __construct(BackendApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        $response = $this->api->getReportSummary();
        $data = $response['data'] ?? [];
        return view('reports.index', $data);
    }

    public function performance()
    {
        $response = $this->api->getReportPerformance();
        $data = $response['data'] ?? [];
        return view('reports.performance', $data);
    }

    public function growth()
    {
        $response = $this->api->getReportGrowth();
        $data = $response['data'] ?? [];
        return view('reports.growth', $data);
    }

    public function financial()
    {
        $response = $this->api->getReportFinancial();
        $data = $response['data'] ?? [];
        return view('reports.financial', $data);
    }

    public function getReportsData(Request $request)
    {
        $type = $request->get('type', 'summary');
        $method = "getReport" . ucfirst($type);
        if (method_exists($this->api, $method)) {
            $response = $this->api->$method();
        } else {
            $response = ['success' => false, 'message' => 'Invalid report type'];
        }
        return response()->json($response);
    }
}
