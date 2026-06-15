<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inmueble\StoreInmuebleRequest;
use App\Http\Requests\Inmueble\UpdateInmuebleRequest;
use App\Models\Category;
use App\Models\Inmueble;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InmuebleController extends Controller
{
    public function __construct(private readonly ImageService $imageService) {}

    public function index(): View
    {
        $query = Inmueble::with(['agente', 'category'])->latest();

        // Agente: solo ve sus propios inmuebles
        if (Auth::user()->isAgente()) {
            $query->where('user_id', Auth::id());
        }

        // Filtro por estado vía query string
        if ($estado = request('estado')) {
            $query->where('estado', $estado);
        }

        $inmuebles = $query->paginate(15)->withQueryString();

        return view('admin.inmuebles.index', compact('inmuebles'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.inmuebles.create', compact('categories'));
    }

    public function store(StoreInmuebleRequest $request): RedirectResponse
    {
        // Hasta 20 imágenes WebP: aumentar límites para este request
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '120');

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('fotos')) {
            $data['fotos'] = collect($request->file('fotos'))
                ->map(fn ($file) => $this->imageService->uploadAndConvert($file, 'inmuebles'))
                ->values()
                ->all();
        }

        Inmueble::create($data);

        return redirect()
            ->route('admin.inmuebles.index')
            ->with('success', 'Inmueble creado correctamente.');
    }

    public function edit(Inmueble $inmueble): View
    {
        Gate::authorize('manage-inmueble', $inmueble);

        $categories = Category::orderBy('name')->get();

        return view('admin.inmuebles.edit', compact('inmueble', 'categories'));
    }

    public function update(UpdateInmuebleRequest $request, Inmueble $inmueble): RedirectResponse
    {
        Gate::authorize('manage-inmueble', $inmueble);

        // Hasta 20 imágenes WebP: aumentar límites para este request
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '120');

        $data = $request->validated();

        if ($request->hasFile('fotos')) {
            // Eliminar fotos anteriores antes de guardar las nuevas
            $this->imageService->deleteMany($inmueble->fotos ?? []);

            $data['fotos'] = collect($request->file('fotos'))
                ->map(fn ($file) => $this->imageService->uploadAndConvert($file, 'inmuebles'))
                ->values()
                ->all();
        } else {
            unset($data['fotos']); // Conservar fotos existentes si no se envían nuevas
        }

        $inmueble->update($data);

        return redirect()
            ->route('admin.inmuebles.index')
            ->with('success', 'Inmueble actualizado correctamente.');
    }

    public function destroy(Inmueble $inmueble): RedirectResponse
    {
        Gate::authorize('manage-inmueble', $inmueble);

        // El modelo Inmueble::booted() se encarga de borrar las fotos físicas
        $inmueble->delete();

        return redirect()
            ->route('admin.inmuebles.index')
            ->with('success', 'Inmueble eliminado correctamente.');
    }
}
