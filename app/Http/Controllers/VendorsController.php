<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\Location;
use App\Expertise;
use App\FavoriteVendor;
use Illuminate\Http\Request;
use App\Repositories\VendorRepo;
use Illuminate\Support\Facades\Auth;
use App\Search\JobPost\Filters\Locations;
use App\Http\Requests\UpdateVendorRequest;

class VendorsController extends Controller
{
    public function index()
    {
        $favoriteVendors = $this->shareFavoriteVendors();

        if (request('expertise') && !is_array(request('expertise'))) {
            $param = request('expertise');
            request()->merge(['expertise' => array($param)]);
        }

        $vendors = Vendor::where(function ($q) {
            if (request('expertise')) {
                $expertiseIds = Expertise::whereIn('name', request('expertise'))->get()->pluck('id');
                $q->whereHas('expertise', function ($q) use ($expertiseIds) {
                    $q->whereIn('expertises.id', $expertiseIds);
                });
            }
            if (request('locations') && !in_array('All Around Australia', request('locations'))) {
                $locationIds = Location::whereIn('name', request('locations'))->get()->pluck('id');
                $q->whereHas('locations', function ($q) use ($locationIds) {
                    $q->whereIn('locations.id', $locationIds);
                });
            }
        })->paginate(env('APP_PAGINATION', 10));

        $this->shareLocationsByState();
        $this->shareExpertises();

        return view('vendor-search.index', compact('vendors', 'favoriteVendors'));
    }

    public function edit($vendorId)
    {
        $profile = Vendor::whereId($vendorId)->with([
            'locations',
            'expertise'
        ])->firstOrfail();

        if (Auth::user()->cannot('edit', $profile)) {
            abort(403);
        }

        $this->shareEditFlag();
        $this->shareExpertises();
        $this->shareLocationsByState();
        $this->shareProfileGallery($profile);

        return view(sprintf('profiles.edit-%s', Auth::user()->account), compact('profile'));
    }

    public function show($vendorId)
    {
        $profile = Vendor::whereId($vendorId)->with([
            'location:id,name',
            'expertise'
        ])->firstOrfail();

        $this->shareEditFlag('editOff');
        $this->shareExpertises();
        $this->shareLocations();
        $this->shareProfileGallery($profile);

        if (Auth::user()) {
            $isFavorite = FavoriteVendor::where('user_id', Auth::user()->id)
            ->where('vendor_id', $profile->id)->exists();
        } else {
            $isFavorite = false;
        }

        return view('profiles.show-vendor', compact('profile', 'isFavorite'));
    }

    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        if (Auth::user()->cannot('update', $vendor)) {
            abort(403);
        }

        $vendorRepo = new VendorRepo;
        $vendorRepo->update($request->all() + ['vendorId' => $vendor->id]);
        $vendorRepo->updateProfileAvatar($request->profile_avatar, $vendor);
        $vendorRepo->updateProfileCover($request->profile_cover, $vendor);
        $vendorRepo->updateTC($request->tc, Auth::user());

        if ($request->isJson() || $request->wantsJson()) {
            return response()->json();
        }

        return redirect(sprintf('vendors/%s', $vendor->id))
            ->with('success', 'Your profile was updated successfully!');
    }
}
