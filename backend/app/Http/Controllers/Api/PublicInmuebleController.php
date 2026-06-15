<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\InmuebleResource;
use App\Models\Category;
use App\Models\Inmueble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PublicInmuebleController extends Controller
{
    /**
     * GET /api/inmuebles
     * Listado público paginado de inmuebles disponibles.
     * Filtros: precio_min, precio_max, categoria_id, tipo, habitaciones_min
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Inmueble::with(['category'])
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

        if ($request->filled('habitaciones_min')) {
            $query->where('habitaciones', '>=', (int) $request->habitaciones_min);
        }

        return InmuebleResource::collection($query->paginate(12));
    }

    /**
     * GET /api/inmuebles/{inmueble}
     * Detalle de un inmueble disponible.
     */
    public function show(Inmueble $inmueble): InmuebleResource|JsonResponse
    {
        if ($inmueble->estado !== 'disponible') {
            return response()->json(['message' => 'Inmueble no disponible.'], 404);
        }

        return new InmuebleResource($inmueble->load('category'));
    }

    /**
     * GET /api/categorias
     * Listado de categorías para los selectores de filtro del frontend.
     */
    public function categorias(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::orderBy('name')->get());
    }
}
