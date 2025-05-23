<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;




class PersonaController extends Controller
{


    public function index()
    {
        $personas = Persona::all();

        return Inertia::render('Personas/Index', [
            'personas' => $personas,
        ]);
    }


    public function crear()
    {
        return Inertia::render('Personas/Form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'primer_nombre'     => 'required|string|max:100',
        'segundo_nombre'    => 'nullable|string|max:100',
        'primer_apellido'   => 'required|string|max:100',
        'segundo_apellido'  => 'required|string|max:100',
        'apellido_casada'   => 'nullable|string|max:100',
        'tipo_documento'    => 'required|in:cui,pasaporte',
        'cui'               => 'nullable|unique:personas',
        'pasaporte'         => 'nullable|unique:personas',
        'fecha_nacimiento'  => 'required|date',
        'telefono'          => 'required|string|max:20',
        'email'             => 'required|email|unique:personas',
        'sexo'              => 'required|in:masculino,femenino',
        'estado_civil'      => 'required|string|max:50',
        'direccion'         => 'required|string',
        'documento'     => 'required|file|mimes:pdf|max:1024',
        ], [
            'primer_nombre.required' => 'El primer nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'tipo_documento.required' => 'Debe seleccionar el tipo de documento.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'documento.required' => 'Debe adjuntar un archivo PDF.',
            'documento.mimes' => 'El documento debe estar en formato PDF.',
            'documento.max' => 'El archivo no debe superar 1MB.',
        ]);

        if ($validator->fails()) {
                return redirect()->back()
                ->withErrors($validator)
                ->withInput();
                }

        try {
            DB::beginTransaction();

            $persona = new Persona();
            $persona->primer_nombre = $request->primer_nombre;
            $persona->segundo_nombre = $request->segundo_nombre;
            $persona->primer_apellido = $request->primer_apellido;
            $persona->segundo_apellido = $request->segundo_apellido;
            $persona->apellido_casada = $request->apellido_casada;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->cui = $request->cui;
            $persona->pasaporte = $request->pasaporte;
            $persona->fecha_nacimiento = $request->fecha_nacimiento;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->sexo = $request->sexo;
            $persona->estado_civil = $request->estado_civil;
            $persona->direccion = $request->direccion;

            // Guardar archivo PDF
            if ($request->hasFile('documento')) {
                $documento = $request->file('documento');
                $extension = $documento->getClientOriginalExtension();

                if ($extension !== 'pdf') {
                    return redirect()->back()
                    ->withErrors(['documento' => 'El archivo debe ser en formato .pdf'])
                    ->withInput();
                }

                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->format('m');
                $filename = 'persona_' . Str::slug($request->primer_nombre . '_' . now()->timestamp) . '.pdf';
                $folderPath = "{$year}/{$month}";

                $ruta = Storage::disk('public')->putFileAs($folderPath, $documento, $filename);
                $persona->documento = $ruta;
            }

            $persona->save();

            DB::commit();

            return redirect()->route('personas.index')->with('success', 'Persona registrada correctamente.');


        } catch (\Exception $e) {
            Log::error('Error al guardar persona: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al registrar la persona.'])->withInput();

        }

    }


    public function editar(Persona $persona)
    {
        return Inertia::render('Personas/Form', [
            'persona' => $persona,
        ]);
    }






    public function actualizar(Request $request, Persona $persona)

    {


        $validator = Validator::make($request->all(), [
            'primer_nombre'     => 'required|string|max:100',
            'segundo_nombre'    => 'nullable|string|max:100',
            'primer_apellido'   => 'required|string|max:100',
            'segundo_apellido'  => 'required|string|max:100',
            'apellido_casada'   => 'nullable|string|max:100',
            'tipo_documento'    => 'required|in:cui,pasaporte',
            'cui'               => Rule::requiredIf($request->tipo_documento === 'cui'),
            'pasaporte'         => Rule::requiredIf($request->tipo_documento === 'pasaporte'),
            'fecha_nacimiento'  => 'required|date',
            'telefono'          => 'required|string|max:20',
            'email' => ['required','email', Rule::unique('personas')->ignore($persona->id),],
            'sexo'              => 'required|in:masculino,femenino',
            'estado_civil'      => 'required|string|max:50',
            'direccion'         => 'required|string',
            'documento'     => 'required|file|mimes:pdf|max:1024',
            ], [
                'primer_nombre.required' => 'El primer nombre es obligatorio.',
                'primer_apellido.required' => 'El primer apellido es obligatorio.',
                'tipo_documento.required' => 'Debe seleccionar el tipo de documento.',
                'email.email' => 'El correo electrónico no tiene un formato válido.',
                'documento.required' => 'Debe adjuntar un archivo PDF.',
                'documento.mimes' => 'El documento debe estar en formato PDF.',
                'documento.max' => 'El archivo no debe superar 1MB.',
            ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }


        try {
            DB::beginTransaction();

            $data = $validator->validated();

            // Si hay nuevo PDF, reemplaza el anterior
            if ($request->hasFile('documento')) {
                if ($persona->documento) {
                    Storage::disk('public')->delete($persona->documento);
                }

                $pdf = $request->file('documento');
                $filename = 'persona_' . Str::slug($request->primer_nombre . '_' . now()->timestamp) . '.pdf';
                $folderPath = Carbon::now()->format('Y/m');
                $ruta = Storage::disk('public')->putFileAs($folderPath, $pdf, $filename);
                $data['documento'] = $ruta;
            }

            $persona->update($data);

            DB::commit();
            return redirect()->route('personas.index')->with('success', 'Persona actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al actualizar la persona.'])->withInput();
        }
    }


    public function aprobar(Persona $persona)
    {
        try {
            DB::beginTransaction();
            $persona->estado = 'aprobado';
            $persona->save();
            DB::commit();

            return redirect()->route('personas.index')->with('success', 'Persona aprobada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al aprobar la persona.']);
        }
    }

    public function rechazar(Persona $persona)
    {
        try {
            DB::beginTransaction();
            $persona->estado = 'rechazado';
            $persona->save();
            DB::commit();

            return redirect()->route('personas.index')->with('success', 'Persona rechazada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error al rechazar la persona.']);
        }
    }


    //Solo para tecnico  solo registros pendientes
    public function revisiones()
    {
        $personas = Persona::where('estado', 'pendiente')->get();

        return Inertia::render('Personas/Index', [
            'personas' => $personas,
        ]);
    }


}
