<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlantillaCorreo\UpdatePlantillaCorreoRequest;
use App\Models\PlantillaCorreo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PlantillaCorreoController extends Controller
{
    public function index(): View
    {
        $plantillas = PlantillaCorreo::orderBy('identificador')->get();

        return view('admin.plantillas.index', compact('plantillas'));
    }

    public function edit(PlantillaCorreo $plantilla): View
    {
        return view('admin.plantillas.edit', compact('plantilla'));
    }

    public function update(UpdatePlantillaCorreoRequest $request, PlantillaCorreo $plantilla): RedirectResponse
    {
        $plantilla->update($request->validated());

        return redirect()
            ->route('admin.plantillas.index')
            ->with('success', "Plantilla «{$plantilla->nombre}» actualizada correctamente.");
    }
}
