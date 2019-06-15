<?php

namespace App\Http\Controllers;

use App\FavoriteVendor;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreFavoriteVendorRequest;

class FavoriteVendorsController extends Controller
{
    public function index()
    {
        $q = FavoriteVendor::where('user_id', Auth::user()->id);

        if (request('keyword')) {
            $q = $q->whereHas('vendorProfile', function ($q) {
                $q->Where('business_name', 'like', '%'.request('keyword').'%');
            });
        }

        $favoriteVendors = $q->with('vendorProfile')->paginate(env('APP_PAGINATION', 10));

        return view('dashboard.couple.favorite-vendors', compact('favoriteVendors'));
    }

    public function store(StoreFavoriteVendorRequest $request)
    {
        Auth::user()->favoriteVendors()->create([
            'vendor_id' => $request->vendor_id
        ]);

        return response()->json();
    }

    public function destroy($id)
    {
        Auth::user()->favoriteVendors()->where('vendor_id', $id)->delete();

        return response()->json();
    }
}
