<?php

namespace App\Http\Controllers\Front;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
public function index()
{
    $products = Item::all();

    
    return view('catalog', ['products' => $products]);
    
    // $item = Item::with('type','brand','image')->where('slug',$slug)->firstOrFail();
    // $siiliarItems = Item::with('type','brand','image')
    
    // ->where('id', '!=', $item->id)
    // ->get();

    //print_r(dd($item));
    // return view('catalog',[
    //     'item' => $item
        //    'similiarItems' => $similiarItems
    // ]);
}
}
