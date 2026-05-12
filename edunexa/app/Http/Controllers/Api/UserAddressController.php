<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses; 
        return response()->json(['status' => true, 'data' => $addresses]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'          => 'required|string|max:255',
            'receiver_name'  => 'required|string|max:255',
            'receiver_phone' => 'nullable|string|max:255',
            'address_detail' => 'nullable|string',
            'village_id'     => 'required|string|size:26', // Validasi panjang string ULID
            'is_primary'     => 'boolean',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'note_to_driver' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($data, $request) {
            // Jika alamat ini diset primary, nonaktifkan primary lainnya milik user ini
            if ($request->is_primary) {
                auth()->user()->addresses()->update(['is_primary' => false]);
            }

            $address = auth()->user()->addresses()->create($data);
            return response()->json(['status' => true, 'data' => $address], 201);
        });
    }
}