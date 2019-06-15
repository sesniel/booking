<?php

namespace App\Http\Controllers;

use App\Couple;
use App\Repositories\CoupleRepo;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateCoupleRequest;

class CouplesController extends Controller
{
    public function edit($coupleId)
    {
        $profile = Couple::whereId($coupleId)->with([
            'ceremonyVenue',
            'bookedVenue',
            'receptionVenue'
        ])->firstOrFail([
            'id', 'title', 'desc', 'ceremony_venue_id', 'reception_venue_id',
            'booked_venue_id', 'profile_avatar', 'profile_cover',
        ]);

        if (Auth::user()->cannot('edit', $profile)) {
            abort(403);
        }

        $this->shareEditFlag();
        $this->shareLocations();
        $this->shareProfileGallery($profile);
        $this->shareCoupleActiveJobs($profile);

        return view(sprintf('profiles.edit-%s', Auth::user()->account), compact('profile'));
    }

    public function show($coupleId)
    {
        $profile = Couple::whereId($coupleId)->with([
            'ceremonyVenue',
            'bookedVenue',
            'receptionVenue'
        ])->firstOrFail([
            'id', 'title', 'desc', 'ceremony_venue_id', 'reception_venue_id',
            'booked_venue_id', 'profile_avatar', 'profile_cover',
        ]);

        $this->shareEditFlag('editOff');
        $this->shareProfileGallery($profile);
        $this->shareCoupleActiveJobs($profile);

        return view('profiles.show-couple', ['profile' => $profile]);
    }

    public function update(UpdateCoupleRequest $request, Couple $couple)
    {
        if (Auth::user()->cannot('update', $couple)) {
            abort(403);
        }

        $coupleRepo = new CoupleRepo;
        $coupleRepo->update($request->all() + ['coupleId' => $couple->id]);
        $coupleRepo->updateProfileAvatar($request->profile_avatar, $couple);
        $coupleRepo->updateProfileCover($request->profile_cover, $couple);

        if ($request->isJson() || $request->wantsJson()) {
            return response()->json();
        }

        return response()->json();
    }
}
