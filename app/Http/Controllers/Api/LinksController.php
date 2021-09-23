<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use App\Http\Resources\Link as LinkResource;

class LinksController extends Controller
{
    public function index(Link $link)
    {
        $links = $link->getAllCached();
        return LinkResource::collection($links);
    }
}
