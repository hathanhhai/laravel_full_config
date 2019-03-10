<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $response = [];
    protected $request = [];
    protected $client_prefix = '';
    protected $dataSendView = [];

    public function __construct()
    {
        $request = request();
        $this->request = $request->toArray();
        $this->request['ajax'] = $request->ajax();
        $this->request['client_method'] = $request->method();
        $this->request['admin_prefix'] = config('app.admin_prefix');
        $this->dataSendView['admin_prefix'] = config('app.admin_prefix');
        $this->dataSendView['client_prefix'] = config('app.client_prefix');
        $this->dataSendView['user'] = Auth::user();
        $this->client_prefix = $this->dataSendView['client_prefix'];
        $this->response = [
            'error' => false,
            'error_list' => [],
            'success' => true,
            'message' => '',
            'data' => []
        ];
    }

    public function convertTimeStamp($arrayList)
    {
        foreach ($arrayList as $item) {

            $item->created_at = date('d/m/Y', $item->created_at);
            if (!empty($item->applies)) {
                $this->convertTimeStamp($item->applies);
            }
        }
    }

    public function checkResponse($json)
    {
        return response()->json($json);
    }

    public function doAction()
    {
        try {
            $action = isset($this->request['action']) ? $this->request['action'] : 'none';
            $action = str_replace('_', '-', $action);
            $prefixs = explode('-', $action);

            $send_method = ucfirst(strtolower($this->request['client_method']));
            $method = "_action{$send_method}";
            $method_any = "_actionAny";
            $method_name = "";
            foreach ($prefixs as $prefix) {
                $method_name .= ucfirst($prefix);
            }
            $method .= $method_name;
            $method_any .= $method_name;
            unset($this->request['action']);

        } catch (\Exception $e) {
            return $e;
        }
        if (!empty($method) && method_exists($this, $method)) {

            return $this->{$method}();
        } else if (!empty($method_any) && method_exists($this, $method_any)) {

            return $this->{$method_any}();
        }

        if ($this->request['client_method'] == 'GET' && !$this->request['ajax']) {
            return response()->json(['message' => 'Yêu cầu không hợp lệ :( ', 'action' => $method], 404);
        }

        return response()->json(['message' => 'Yêu cầu không hợp lệ :( ', 'action' => $method], 404);
    }

    protected function jsonReponse($data = null)
    {
        $data = $data ? $data : $this->response;
        return response()->json($data);
    }
}
