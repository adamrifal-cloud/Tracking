<?php

namespace App\Http\Controllers;

use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function index()
    {
        $vendor = Auth::user();
        $profile = $vendor->vendorProfile ?? new VendorProfile();

        return view('vendor.profile.index', compact('vendor', 'profile'));
    }

    /**
     * Update the vendor profile.
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $vendor = Auth::user();
        
        $profile = $vendor->vendorProfile()->firstOrCreate(
            ['user_id' => $vendor->id]
        );

        $profile->company_name = $request->company_name;
        $profile->company_address = $request->company_address;

        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($profile->document_path) {
                Storage::delete($profile->document_path);
            }
            
            $path = $request->file('document')->store('vendor_documents', 'public');
            $profile->document_path = $path;
            $profile->document_status = 'Pending Verification'; // Reset status when new document is uploaded
        }

        $profile->save();

        return redirect()->back()->with('success', 'Profil dan dokumen berhasil diperbarui. Status dokumen: Pending Verification.');
    }
}
