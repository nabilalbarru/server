<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;

class KendaraanController extends Controller
{
    // GET semua kendaraan
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Data kendaraan',
            'data' => Kendaraan::all()
        ]);
    }

    // POST tambah kendaraan
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required',
            'kategori_emisi' => 'required|in:Rendah,Sedang,Tinggi',
            // "0.5 g CO/km", "100g CO/km", "100-200g CO/km"
            'emisi_perjalanan' => ['required', 'regex:/^(\d+(\.\d+)?(-\d+(\.\d+)?)?)\s?g CO\/km$/'],
            'deskripsi_cc_kendaraan' => 'required|string|max:50',
            'icon' => 'required|string|max:100',
        ]);

        $kendaraan = Kendaraan::create([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'kategori_emisi' => $request->kategori_emisi,
            'emisi_perjalanan' => $request->emisi_perjalanan,
            'deskripsi_cc_kendaraan' => $request->deskripsi_cc_kendaraan,
            'icon' => $request->icon,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Kendaraan berhasil ditambahkan',
            'data' => $kendaraan
        ], 201);
    }

    // GET detail kendaraan
    public function show($id)
    {
        $kendaraan = Kendaraan::find($id);

        if (!$kendaraan) {
            return response()->json([
                'status' => false,
                'message' => 'Kendaraan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $kendaraan
        ]);
    }

    // PUT update kendaraan
    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::find($id);

        if (!$kendaraan) {
            return response()->json([
                'status' => false,
                'message' => 'Kendaraan tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'jenis_kendaraan' => 'required',
            'kategori_emisi' => 'required|in:Rendah,Sedang,Tinggi',
            // ✅ UPDATED REGEX (Same as store)
            'emisi_perjalanan' => ['required', 'regex:/^(\d+(\.\d+)?(-\d+(\.\d+)?)?)\s?g CO\/km$/'],
            'deskripsi_cc_kendaraan' => 'required|string|max:50',
            'icon' => 'required|string|max:100',
        ]);

        $kendaraan->update([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'kategori_emisi' => $request->kategori_emisi,
            'emisi_perjalanan' => $request->emisi_perjalanan,
            'deskripsi_cc_kendaraan' => $request->deskripsi_cc_kendaraan,
            'icon' => $request->icon
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Kendaraan berhasil diperbarui',
            'data' => $kendaraan
        ]);
    }

    // DELETE kendaraan
    public function destroy($id)
    {
        $kendaraan = Kendaraan::find($id);

        if (!$kendaraan) {
            return response()->json([
                'status' => false,
                'message' => 'Kendaraan tidak ditemukan'
            ], 404);
        }

        $kendaraan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kendaraan berhasil dihapus'
        ]);
    }
}
