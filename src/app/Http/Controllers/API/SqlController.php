<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use DB;

class SqlController extends Controller
{
    public function sales(Request $request)
    {   
        try{

            Config::set('database.connections.SalesReports', array(
                'driver'      => 'sqlsrv',
                'host'        => '192.168.1.13',
                'port'        => '1433',
                'database'    => 'SalesReports',
                'username'    => 'sapprog105',
                'password'    => '105*Prog',
                'encrypt'     => 'false',
                'trust_server_certificate' => 'true',
            ));
            $items = DB::connection('SalesReports')
                            ->select("SELECT top 1 * from SalesMain");
                            // and b.ItemName like '%". $params['model'] ."%'
                            // and b.FrgnName like '%". $params['category'] ."%'
                            // and c.FirmName like '%". $params['brand'] ."%'
        
            return response()->json($items, 200);

        } catch (\Exception $e) {
                
            return response()->json(['error' => $e->getMessage()], 200);
        }

    }
}
