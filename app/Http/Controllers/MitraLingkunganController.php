<?php

namespace App\Http\Controllers;

use App\Models\MitraLingkungan;
use Illuminate\Http\Request;

class MitraLingkunganController extends Controller
{
    // GET: semua mitra
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Data Mitra Lingkungan',
            'data' => MitraLingkungan::all()
        ]);
        // return MitraLingkungan::with('rekening')->get();
    }

    // POST: tambah mitra
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'deskripsi' => 'required',
            'fokus_area' => 'required',
            'url' => 'nullable|url'
        ]);

        $mitra = MitraLingkungan::create($validated);

        return response()->json([
            'message' => 'Mitra berhasil ditambahkan',
            'data' => $mitra
        ], 201);
    }

    // GET: detail mitra
    public function show($id)
    {
        return MitraLingkungan::with('rekening')->findOrFail($id);
    }

    // PUT: update mitra
    public function update(Request $request, $id)
    {
        $mitra = MitraLingkungan::findOrFail($id);

        $validated = $request->validate([
            'nama_mitra' => 'nullable|string|max:255',
            'deskripsi' => 'required',
            'fokus_area' => 'required',
            'url' => 'nullable|url'
        ]);

        $mitra->update($validated);

        return response()->json([
            'message' => 'Mitra berhasil diperbarui',
            'data' => $mitra
        ]);
    }

    // DELETE: hapus mitra
    public function destroy($id)
    {
        $mitra = MitraLingkungan::findOrFail($id);
        $mitra->delete();

        return response()->json([
            'message' => 'Mitra berhasil dihapus'
        ]);
    }
}
