<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Company;
use App\Models\SharedArtist;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArtistSharingController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:company_admin');
        $this->middleware('company.scope');
    }

    /**
     * Show artist sharing page
     */
    public function index()
    {
        $title = 'Artist Sharing';
        $companyId = Auth::user()->company_id;

        // Artists I own and can share
        $myArtists = Artist::where('company_id', $companyId)
            ->with(['user', 'sharedWith'])
            ->paginate(15, ['*'], 'my');

        // Artists shared with me
        $sharedWithMe = SharedArtist::where('shared_with_company_id', $companyId)
            ->where('status', 'accepted')
            ->with(['artist.user', 'ownerCompany'])
            ->paginate(15, ['*'], 'shared');

        // Pending share requests I received
        $pendingRequests = SharedArtist::where('shared_with_company_id', $companyId)
            ->where('status', 'pending')
            ->with(['artist.user', 'ownerCompany'])
            ->get();

        // All companies (for sharing dropdown)
        $companies = Company::where('id', '!=', $companyId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('dashboard.pages.company.artist-sharing', compact(
            'title',
            'myArtists',
            'sharedWithMe',
            'pendingRequests',
            'companies'
        ));
    }

    /**
     * Share artist with another company
     */
    public function shareArtist(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'company_id' => 'required|exists:companies,id',
            'notes' => 'nullable|string|max:500'
        ]);

        // Verify artist belongs to this company
        $artist = Artist::where('id', $request->artist_id)
            ->where('company_id', $companyId)
            ->firstOrFail();

        // Cannot share with self
        if ($request->company_id == $companyId) {
            return back()->with('error', 'Cannot share artist with your own company');
        }

        DB::beginTransaction();
        try {
            $shared = SharedArtist::create([
                'artist_id' => $artist->id,
                'owner_company_id' => $companyId,
                'shared_with_company_id' => $request->company_id,
                'status' => 'pending',
                'notes' => $request->notes,
                'shared_at' => now()
            ]);

            ActivityLog::log(
                'created',
                $shared,
                'Artist shared with another company',
                [
                    'artist_id' => $artist->id,
                    'shared_with_company' => $request->company_id
                ]
            );

            DB::commit();

            return back()->with('success', 'Artist sharing request sent successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to share artist: ' . $e->getMessage());
        }
    }

    /**
     * Accept artist sharing request
     */
    public function acceptShare(SharedArtist $sharedArtist)
    {
        $companyId = Auth::user()->company_id;

        // Verify this company is the recipient
        if ($sharedArtist->shared_with_company_id !== $companyId) {
            abort(403, 'Unauthorized');
        }

        if ($sharedArtist->status !== 'pending') {
            return back()->with('error', 'This sharing request is no longer pending');
        }

        DB::beginTransaction();
        try {
            $sharedArtist->update([
                'status' => 'accepted',
                'accepted_at' => now()
            ]);

            ActivityLog::log(
                'updated',
                $sharedArtist,
                'Artist sharing request accepted',
                ['shared_artist_id' => $sharedArtist->id]
            );

            DB::commit();

            return back()->with('success', 'Artist sharing request accepted!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to accept request: ' . $e->getMessage());
        }
    }

    /**
     * Reject artist sharing request
     */
    public function rejectShare(SharedArtist $sharedArtist)
    {
        $companyId = Auth::user()->company_id;

        // Verify this company is the recipient
        if ($sharedArtist->shared_with_company_id !== $companyId) {
            abort(403, 'Unauthorized');
        }

        if ($sharedArtist->status !== 'pending') {
            return back()->with('error', 'This sharing request is no longer pending');
        }

        DB::beginTransaction();
        try {
            $sharedArtist->update([
                'status' => 'rejected'
            ]);

            ActivityLog::log(
                'updated',
                $sharedArtist,
                'Artist sharing request rejected',
                ['shared_artist_id' => $sharedArtist->id]
            );

            DB::commit();

            return back()->with('success', 'Artist sharing request rejected');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }

    /**
     * Revoke artist sharing (owner removes access)
     */
    public function revokeShare(SharedArtist $sharedArtist)
    {
        $companyId = Auth::user()->company_id;

        // Verify this company is the owner
        if ($sharedArtist->owner_company_id !== $companyId) {
            abort(403, 'Unauthorized');
        }

        if ($sharedArtist->status === 'revoked') {
            return back()->with('error', 'This sharing has already been revoked');
        }

        DB::beginTransaction();
        try {
            $sharedArtist->update([
                'status' => 'revoked',
                'revoked_at' => now()
            ]);

            ActivityLog::log(
                'updated',
                $sharedArtist,
                'Artist sharing revoked by owner',
                ['shared_artist_id' => $sharedArtist->id]
            );

            DB::commit();

            return back()->with('success', 'Artist sharing revoked successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to revoke sharing: ' . $e->getMessage());
        }
    }
}
