<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Http\Requests\UrlRequest;

class UrlController extends Controller
{
    public function index()
    {
        $urls = Url::get();
        return view('urls.index', compact('urls'));
    }

    public function store(UrlRequest $request)
    {
        if(request()->ajax()){
            $url = Url::create($request->validated());
            return response()->json([
                'url' => $url
            ]);
        }
        abort(404);
    }

    public function show(Url $url)
    {
        return redirect($url->original);
    }
}
