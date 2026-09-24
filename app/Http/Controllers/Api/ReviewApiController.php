<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ReviewApiController extends Controller
{
    /**
     * GET /api/reviews
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Review::query();

            // Filter berdasarkan pool_id jika dikirim
            if ($request->filled('pool_id')) {
                $query->where('pool_id', $request->pool_id);
            }

            $reviews = $query
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($review) use ($request) {
                    return [
                        'id' => $review->id,
                        'pool_id' => $review->pool_id,
                        'nama_pengunjung' => $review->nama_pengunjung,
                        'rating' => $review->rating,
                        'komentar' => $review->komentar,

                        // Path foto dari database
                        'foto' => $review->foto,

                        // URL foto untuk Flutter
                        'foto_url' => $review->foto
                            ? $request->getSchemeAndHttpHost()
                                . '/storage/'
                                . $review->foto
                            : null,

                        'balasan_admin' => $review->balasan_admin,
                        'status' => $review->status,
                        'created_at' => $review->created_at,
                        'updated_at' => $review->updated_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Data review berhasil diambil',
                'data' => $reviews,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * POST /api/reviews
     */
    public function store(Request $request): JsonResponse
    {
        try {

            // ==========================================
            // VALIDASI DATA
            // ==========================================

            $validated = $request->validate([
                'pool_id' => 'required|string|max:255',
                'nama_pengunjung' => 'required|string|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'komentar' => 'nullable|string',

                // Foto maksimal 5 MB
                'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);


            // ==========================================
            // UPLOAD FOTO
            // ==========================================

            $fotoPath = null;

            if ($request->hasFile('foto')) {

                $fotoPath = $request->file('foto')->store(
                    'reviews',
                    'public'
                );
            }


            // ==========================================
            // SIMPAN REVIEW
            // ==========================================

            $review = Review::create([
                'pool_id' => $validated['pool_id'],
                'nama_pengunjung' => $validated['nama_pengunjung'],
                'rating' => $validated['rating'],
                'komentar' => $validated['komentar'] ?? null,
                'balasan_admin' => null,
                'status' => 'Menunggu',
            ]);


            // ==========================================
            // SIMPAN PATH FOTO KE DATABASE
            // ==========================================

            if ($fotoPath !== null) {

                $review->foto = $fotoPath;
                $review->save();

                // Ambil ulang data terbaru dari database
                $review->refresh();
            }


            // ==========================================
            // BUAT FOTO URL
            // ==========================================

            $fotoUrl = null;

            if ($review->foto) {

                $fotoUrl = $request->getSchemeAndHttpHost()
                    . '/storage/'
                    . $review->foto;
            }


            // ==========================================
            // RESPONSE BERHASIL
            // ==========================================

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dikirim',

                'data' => [
                    'id' => $review->id,
                    'pool_id' => $review->pool_id,
                    'nama_pengunjung' => $review->nama_pengunjung,
                    'rating' => $review->rating,
                    'komentar' => $review->komentar,

                    'foto' => $review->foto,

                    'foto_url' => $fotoUrl,

                    'balasan_admin' => $review->balasan_admin,
                    'status' => $review->status,
                    'created_at' => $review->created_at,
                    'updated_at' => $review->updated_at,
                ],
            ], 201);


        } catch (ValidationException $e) {

            // ==========================================
            // ERROR VALIDASI
            // ==========================================

            return response()->json([
                'success' => false,
                'message' => 'Data review tidak valid',
                'errors' => $e->errors(),
            ], 422);


        } catch (\Exception $e) {

            // ==========================================
            // ERROR SERVER
            // ==========================================

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * GET /api/reviews/{id}
     */
    public function show($id): JsonResponse
    {
        try {

            // ==========================================
            // CARI REVIEW
            // ==========================================

            $review = Review::find($id);

            if (!$review) {

                return response()->json([
                    'success' => false,
                    'message' => 'Review tidak ditemukan',
                ], 404);
            }


            // ==========================================
            // FOTO URL
            // ==========================================

            $fotoUrl = null;

            if ($review->foto) {

                $fotoUrl = request()->getSchemeAndHttpHost()
                    . '/storage/'
                    . $review->foto;
            }


            // ==========================================
            // RESPONSE
            // ==========================================

            return response()->json([
                'success' => true,
                'message' => 'Detail review berhasil diambil',

                'data' => [
                    'id' => $review->id,
                    'pool_id' => $review->pool_id,
                    'nama_pengunjung' => $review->nama_pengunjung,
                    'rating' => $review->rating,
                    'komentar' => $review->komentar,

                    'foto' => $review->foto,

                    'foto_url' => $fotoUrl,

                    'balasan_admin' => $review->balasan_admin,
                    'status' => $review->status,
                    'created_at' => $review->created_at,
                    'updated_at' => $review->updated_at,
                ],
            ]);


        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}