<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mulai_perjalanan'   => 'required|date',
            'selesai_perjalanan' => 'required|date|after:mulai_perjalanan',
            'jarak_tempuh'       => 'required|numeric|min:0',
            'id_kendaraan'       => 'required|exists:kendaraan,id',
        ]);

        // Ambil data kendaraan
        $kendaraan = Kendaraan::find($request->id_kendaraan);

        if (!$kendaraan) {
            return response()->json([
                'status' => false,
                'message' => 'Data kendaraan tidak ditemukan.'
            ], 404);
        }

        // Gunakan kolom emisi_perjalanan dari tabel kendaraan
        $faktor = $kendaraan->emisi_perjalanan;

        // Hitung total emisi berdasarkan jarak * faktor emisi
        $emisi = $request->jarak_tempuh * $faktor;

        // Simpan tracking
        $tracking = Tracking::create([
            'mulai_perjalanan'   => $request->mulai_perjalanan,
            'selesai_perjalanan' => $request->selesai_perjalanan,
            'jarak_tempuh'       => $request->jarak_tempuh,
            'emisi_perjalanan'   => $emisi,
            'id_pengendara'      => Auth::id(),   // <= otomatis user login
            'id_kendaraan'       => $request->id_kendaraan,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tracking berhasil dicatat',
            'data' => $tracking
        ], 201);
    }


    // F004 – Riwayat perjalanan user login
    public function riwayat()
    {
        $userId = Auth::id();

        $riwayat = Tracking::where('id_pengendara', $userId)
            ->with('kendaraan')
            ->orderBy('mulai_perjalanan', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $riwayat
        ]);
    }
}
