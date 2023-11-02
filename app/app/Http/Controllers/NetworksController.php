<?php

namespace App\Http\Controllers;

use App\Http\Requests\NetworkRequest;
use App\Models\Networks;
use Illuminate\Http\JsonResponse;

class NetworksController extends Controller
{
    public function index(NetworkRequest $request)
    {
        $errroMsg = null;
        $data = [
            'networks' => []
        ];

        if ($request->ip !== null) {
            if (filter_var($request->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) !== false) {
                if (filter_var($request->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $network = Networks::findIpV4($request->ip);
                    if (!empty($network)) {
                        $data['networks'][] = $network;
                    }
                } elseif (filter_var($request->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    $network = Networks::findIpV6($request->ip);
                    if (!empty($network)) {
                        $data['networks'][] = $network;
                    }
                }
            } else {
                $errroMsg = 'Invalid IP address';
            }
        }

        if (empty($data['networks'])  && empty($request->ip) && $errroMsg === null) {
            $data['networks'] = Networks::with('country')->paginate(100);
        } elseif (!empty($request->ip) && $errroMsg !== null) {
            $data['error'] = $errroMsg;
        }

        if ($request->type === 'json') {
            return response()->json($data, JsonResponse::HTTP_OK);
        }

        return view('welcome',  $data);
    }
}
