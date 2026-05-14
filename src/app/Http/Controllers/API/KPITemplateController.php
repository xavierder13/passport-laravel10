<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KPITemplateController extends Controller
{
    public function index()
    {
        return response()->json(['kpi_templates' => ['a', 'b', 'c']]);
    }
}
