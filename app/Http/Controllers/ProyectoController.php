<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto; // Asegúrate de importar el modelo Proyecto
use App\Models\Departamento; // Si necesitas usarlo en algún método
use App\Models\Adjunto;     // Si necesitas usarlo en algún método
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; // Para acceder al usuario autenticado

class ProyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Mapeo de roles a departamento_id, si has configurado config/roles.php
        $docenteId = config('roles.docente_id');
        $coordinadorId = config('roles.coordinador_id');

        // Protege los métodos con el middleware 'role' (que ya adaptamos a departamento_id)
        $this->middleware("role:Docente")->only([
            'create',
            'store',
            'misProyectos',
        ]);

        $this->middleware("role:Coordinador")->only([
            'validarProyectos',
            'terminados',
            'enCurso',
            'descargarAdjunto',
        ]);
        // ... otros roles si los tienes
    }

    /**
     * Muestra el formulario para crear un nuevo proyecto.
     * Solo accesible para docentes.
     */
    public function create()
    {
        // Si necesitas datos adicionales para el formulario, cárgalos aquí.
        // Por ejemplo, para seleccionar un departamento si no está implícito en el rol.
        $departamentos = Departamento::all(); // Asegúrate de que el modelo Departamento exista

        return view('proyectos.create', compact('departamentos'));
    }

    /**
     * Almacena un nuevo proyecto en la base de datos.
     * Solo accesible para docentes.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'objetivos' => 'required|string',
            'metodologia' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin_estimada' => 'required|date|after_or_equal:fecha_inicio',
            'adjuntos.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png|max:5000', // max 5MB por archivo
        ]);

        $proyecto = new Proyecto($request->except('adjuntos'));
        $proyecto->user_id = Auth::id(); // Asigna el ID del usuario autenticado (el docente)

        // Asigna el estado inicial
        $proyecto->estado = 'Pendiente'; // O el estado inicial que uses

        // Si el docente_id se deriva del departamento_id del usuario, 
        // podrías asignarlo aquí si tu modelo Proyecto lo requiere
        // $proyecto->docente_id = Auth::id(); // Si es diferente de user_id

        $proyecto->save();

        // Manejo de adjuntos
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {
                $path = $file->store('adjuntos_proyectos', 'public'); // Guarda en storage/app/public/adjuntos_proyectos
                Adjunto::create([
                    'proyecto_id' => $proyecto->id,
                    'nombre_original' => $file->getClientOriginalName(),
                    'ruta_almacenamiento' => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'tipo_mime' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('proyectos.mis_proyectos')->with('success', 'Proyecto creado exitosamente y pendiente de aprobación.');
    }


    /**
     * Muestra la lista de proyectos creados por el Docente autenticado.
     * Protegido por el middleware 'role:Docente'.
     */
    public function misProyectos()
    {
        // user_id es la columna en tu tabla 'proyectos' que referencia al 'usuario_id' de la tabla 'usuarios'
        $docenteUsuarioId = Auth::id();

        // Carga los proyectos donde el user_id (docente) coincide con el usuario autenticado
        $proyectos = Proyecto::where('user_id', $docenteUsuarioId)->latest()->paginate(10); 
        
        // Retorna la vista y pasa los proyectos a la misma
        return view('docentes.mis_proyectos', compact('proyectos'));
    }

    // --- Otros métodos del controlador (validarProyectos, terminados, enCurso, descargarAdjunto) ---
    // Asegúrate de que estos métodos existan si tus rutas hacen referencia a ellos.

    // Ejemplo para validarProyectos (Coordinador)
    public function validarProyectos()
    {
        // Lógica para que el coordinador vea proyectos pendientes de validación
        $proyectos = Proyecto::where('estado', 'Pendiente')->paginate(10);
        return view('coordinador.validar_proyectos', compact('proyectos'));
    }

    // Ejemplo para proyectos terminados (Coordinador)
    public function terminados()
    {
        $proyectos = Proyecto::where('estado', 'Terminado')->paginate(10);
        return view('coordinador.proyectos_terminados', compact('proyectos'));
    }

    // Ejemplo para proyectos en curso (Coordinador)
    public function enCurso()
    {
        $proyectos = Proyecto::where('estado', 'En Curso')->paginate(10);
        return view('coordinador.proyectos_en_curso', compact('proyectos'));
    }

    // Ejemplo para descargarAdjunto
    public function descargarAdjunto(Adjunto $adjunto)
    {
        if (Storage::disk('public')->exists($adjunto->ruta_almacenamiento)) {
            return Storage::disk('public')->download($adjunto->ruta_almacenamiento, $adjunto->nombre_original);
        }
        abort(404, 'Archivo no encontrado.');
    }
}