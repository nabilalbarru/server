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
            'route_history'      => 'nullable|json',
        ]);

        $kendaraan = Kendaraan::find($request->id_kendaraan);

        if (!$kendaraan) {
            return response()->json([
                'status' => false,
                'message' => 'Data kendaraan tidak ditemukan.'
            ], 404);
        }

        // 2. Parse Faktor Emisi (String to Float)
        // Format DB: "100-130g CO/km" atau "0.5-0.8g CO/km"
        $rawEmission = $kendaraan->emisi_perjalanan;
        $faktor = $this->parseEmissionFactor($rawEmission);

        // 3. Hitung total emisi (Jarak (km) * Faktor (g/km))
        // Hasil dibagi 1000 agar menjadi kg CO2 (standar umum)
        $emisiKg = ($request->jarak_tempuh * $faktor) / 1000;

        // 4. Simpan tracking
        $tracking = Tracking::create([
            'mulai_perjalanan'   => $request->mulai_perjalanan,
            'selesai_perjalanan' => $request->selesai_perjalanan,
            'jarak_tempuh'       => $request->jarak_tempuh,
            'route_history'      => $request->route_history,
            'emisi_perjalanan'   => $emisiKg, // Simpan dalam KG
            'id_pengendara'      => Auth::id(),
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

    /**
     * Helper untuk mengubah string "100-130g CO/km" menjadi angka rata-rata (115.0)
     */
    private function parseEmissionFactor($emissionString)
    {
        // Hapus teks unit ("g CO/km", dll)
        $cleanString = preg_replace('/[a-zA-Z\/\s]/', '', $emissionString);
        
        // Cek apakah format range "100-130"
        if (str_contains($cleanString, '-')) {
            $parts = explode('-', $cleanString);
            if (count($parts) == 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                // Ambil rata-rata
                return ($parts[0] + $parts[1]) / 2;
            }
        }

        // Jika cuma satu angka
        if (is_numeric($cleanString)) {
            return (float) $cleanString;
        }

        return 0.0;
    }
}
