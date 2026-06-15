<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InmuebleResource;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Models\Inmueble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InmuebleController extends Controller
{
    /**
     * GET /api/inmuebles
     * Parámetros: precio_min, precio_max, categoria_id, tipo, page
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Inmueble::with(['category', 'agente'])
            ->where('estado', 'disponible')
            ->latest();

        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', (float) $request->precio_min);
        }

        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', (float) $request->precio_max);
        }

        if ($request->filled('categoria_id')) {
            $query->where('category_id', (int) $request->categoria_id);
        }

        if ($request->filled('tipo') && in_array($request->tipo, ['casa', 'apto', 'terreno'], true)) {
            $query->where('tipo', $request->tipo);
        }

        return InmuebleResource::collection($query->paginate(12));
    }

    /**
     * GET /api/inmuebles/{inmueble}
     */
    public function show(Inmueble $inmueble): InmuebleResource|JsonResponse
    {
        if ($inmueble->estado !== 'disponible') {
            return response()->json(['message' => 'Inmueble no disponible.'], 404);
        }

        return new InmuebleResource($inmueble->load(['category', 'agente']));
    }

    /**
     * GET /api/categorias — para el selector de filtros del frontend
     */
    public function categorias(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::orderBy('name')->get());
    }
}
