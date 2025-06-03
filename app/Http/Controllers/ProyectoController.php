<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto; // Asegúrate de importar el modelo Proyecto
use App\Models\Departamento; // Si necesitas usarlo en algún método
use App\Models\Adjunto;     // Si necesitas usarlo en algún método
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Para acceder al usuario autenticado

class ProyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Protege los métodos solo para el rol 'Docente'
        $this->middleware("role:Docente")->only([
            'create',
            'store',
            'misProyectos',
            // 'edit', // Ya no protegemos con middleware de rol si la comprobación manual es más granular
            // 'update', // Idem
            'subirDocumento',
            // 'eliminarAdjunto', // Idem
        ]);

        // Protege los métodos solo para el rol 'Coordinador'
        $this->middleware("role:Coordinador")->only([
            'indexCoordinador',
            'validarProyectos',
            'terminados',
            'enCurso',
            'aprobar',
            'rechazar',
        ]);

        // El método 'show' debe ser accesible tanto para Docentes como para Coordinadores (y Admin).
        // La autorización granular se maneja con la comprobación manual dentro del método.
        $this->middleware("role:Docente|Coordinador|Administrador")->only(['show']);

        // Descargar adjunto puede ser accesible por varios roles (Docente, Coordinador, Admin).
        // La autorización granular se maneja con la comprobación manual dentro del método.
        $this->middleware("role:Docente|Coordinador|Administrador")->only(['descargarAdjunto']);

        // NOTA: Para 'edit', 'update' y 'eliminarAdjunto', si la comprobación manual
        // ya incluye Administrador y el creador, no es necesario un middleware de rol aquí.
        // Si quieres que SOLO docentes (que sean creadores) Y administradores puedan editar/actualizar,
        // la condición del 'if' se encarga de eso.
    }

    /**
     * Muestra el formulario para crear un nuevo proyecto.
     * Solo accesible para docentes.
     */
    public function create()
    {
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
            'objetivos' => 'nullable|string',
            'metodologia' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin_estimada' => 'required|date|after_or_equal:fecha_inicio',
            'presupuesto' => 'required|numeric|min:0',
            'estado' => 'required|string|in:Activo,Inactivo,Borrador,Pendiente de Validación', // Validar que el estado sea uno de los permitidos por el el formulario
            'departamento_id' => 'required|exists:departamentos,departamento_id',
            'adjuntos.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png|max:5000',
        ]);

        $proyecto = new Proyecto($request->except('adjuntos'));
        $proyecto->creador_id = Auth::id(); // Asigna el ID del usuario autenticado (el docente)
        
        // Asigna la fecha de creación manualmente y toma el estado del request
        $proyecto->fecha_creacion = now(); 
        $proyecto->estado = $request->estado; // Toma el estado del formulario

        $proyecto->save();

        // Manejo de adjuntos
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {
                $path = $file->store('adjuntos_proyectos', 'public');
                Adjunto::create([
                    'proyecto_id' => $proyecto->proyecto_id,
                    'nombre_original' => $file->getClientOriginalName(),
                    'ruta_almacenamiento' => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'tipo_mime' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('proyectos.mis_proyectos')->with('success', 'El proyecto se ha creado exitosamente.');
    }

    /**
     * Muestra la lista de proyectos creados por el Docente autenticado.
     * Protegido por el middleware 'role:Docente'.
     */
    public function misProyectos()
    {
        $docenteUsuarioId = Auth::id();
        // Carga ansiosa las relaciones 'adjuntos' y 'departamento' para la vista de mis proyectos.
        $proyectos = Proyecto::with(['adjuntos', 'departamento'])
                             ->where('creador_id', $docenteUsuarioId)
                             ->orderBy('fecha_creacion', 'desc')
                             ->paginate(10);

        return view('docentes.mis_proyectos', compact('proyectos'));
    }

    /**
     * Muestra una lista general de proyectos para la supervisión del Coordinador.
     * Esta es la vista principal de "Proyectos" para el Coordinador.
     * @return \Illuminate\View\View
     */
    public function indexCoordinador()
    {
        // Carga ansiosa las relaciones 'creador' (el docente), 'departamento' y 'adjuntos'
        // para que todos los datos estén disponibles en la vista sin consultas adicionales.
        $proyectos = Proyecto::with(['creador', 'departamento', 'adjuntos'])
                             ->orderBy('fecha_creacion', 'desc')
                             ->paginate(10);

        return view('coordinador.proyectos.index', compact('proyectos'));
    }

    // Ejemplo para validarProyectos (Coordinador)
    public function validarProyectos()
    {
        // También carga las relaciones necesarias aquí si esta vista las va a mostrar
        $proyectos = Proyecto::with(['creador', 'departamento', 'adjuntos'])
                             ->where('estado', 'Pendiente')
                             ->orderBy('fecha_creacion', 'desc')
                             ->paginate(10);
        return view('coordinador.validar_proyectos', compact('proyectos'));
    }

    // Ejemplo para proyectos terminados (Coordinador)
    public function terminados()
    {
        // También carga las relaciones necesarias aquí si esta vista las va a mostrar
        $proyectos = Proyecto::with(['creador', 'departamento', 'adjuntos'])
                             ->where('estado', 'Terminado')
                             ->orderBy('fecha_creacion', 'desc')
                             ->paginate(10);
        return view('coordinador.proyectos_terminados', compact('proyectos'));
    }

    // Ejemplo para proyectos en curso (Coordinador)
    public function enCurso()
    {
        // También carga las relaciones necesarias aquí si esta vista las va a mostrar
        $proyectos = Proyecto::with(['creador', 'departamento', 'adjuntos'])
                             ->where('estado', 'En Curso')
                             ->orderBy('fecha_creacion', 'desc')
                             ->paginate(10);
        return view('coordinador.proyectos_en_curso', compact('proyectos'));
    }

    // Ejemplo para descargarAdjunto
    public function descargarAdjunto(Adjunto $adjunto)
    {
        // Comprobación manual de permiso para descargar adjunto
        // Si el usuario autenticado es el creador del proyecto o es Administrador/Coordinador
        if (Auth::id() !== $adjunto->proyecto->creador_id && !Auth::user()->hasAnyRole(['Administrador', 'Coordinador'])) {
            abort(403, 'No tienes permiso para descargar este adjunto.');
        }

        if (Storage::disk('public')->exists($adjunto->ruta_almacenamiento)) {
            return Storage::disk('public')->download($adjunto->ruta_almacenamiento, $adjunto->nombre_original);
        }
        abort(404, 'Archivo no encontrado.');
    }

    /**
     * Muestra los detalles de un proyecto específico.
     */
    public function show(Proyecto $proyecto)
    {
        // Comprobación manual de permiso para ver el proyecto
        // Si el usuario autenticado NO es el creador del proyecto
        // Y NO es Administrador
        // Y NO es Coordinador
        // Entonces deniega el acceso.
        if (Auth::id() !== $proyecto->creador_id && !Auth::user()->hasAnyRole(['Administrador', 'Coordinador'])) {
            abort(403, 'No tienes permiso para ver este proyecto.');
        }

        $proyecto->load(['adjuntos', 'creador', 'departamento']);
        return view('proyectos.show', compact('proyecto'));
    }

    /**
     * Muestra el formulario para editar un proyecto existente.
     */
    public function edit(Proyecto $proyecto)
    {
        // Comprobación manual de permiso para editar
        // Si el usuario autenticado NO es el creador del proyecto
        // Y NO es Administrador
        // Entonces deniega el acceso.
        if (Auth::id() !== $proyecto->creador_id && !Auth::user()->hasAnyRole(['Administrador'])) { 
            abort(403, 'No tienes permiso para editar este proyecto.');
        }

        // Carga ansiosa la relación 'adjuntos' y 'departamento'
        $proyecto->load(['adjuntos', 'departamento']); 

        $departamentos = Departamento::all();
        return view('proyectos.edit', compact('proyecto', 'departamentos'));
    }

    /**
     * Actualiza un proyecto existente en la base de datos.
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        // Comprobación manual de permiso para actualizar
        // Si el usuario autenticado NO es el creador del proyecto
        // Y NO es Administrador
        // Entonces deniega el acceso.
        if (Auth::id() !== $proyecto->creador_id && !Auth::user()->hasAnyRole(['Administrador'])) { 
            abort(403, 'No tienes permiso para actualizar este proyecto.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'objetivos' => 'nullable|string',
            'metodologia' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin_estimada' => 'required|date|after_or_equal:fecha_inicio',
            'presupuesto' => 'required|numeric|min:0',
            'estado' => 'required|string|in:Activo,Inactivo,Borrador,Pendiente de Validación,Aprobado,Rechazado,En Curso,Terminado', // Ajusta los estados válidos para la actualización
            'departamento_id' => 'required|exists:departamentos,departamento_id',
            'adjuntos.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png|max:5000',
        ]);

        $proyecto->update($request->except('adjuntos'));

        // Manejo de adjuntos para actualización (puedes añadir o reemplazar)
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {
                $path = $file->store('adjuntos_proyectos', 'public');
                Adjunto::create([
                    'proyecto_id' => $proyecto->proyecto_id,
                    'nombre_original' => $file->getClientOriginalName(),
                    'ruta_almacenamiento' => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'tipo_mime' => $file->getMimeType(),
                ]);
            }
        }
        return redirect()->route('proyectos.mis_proyectos')->with('success', 'Proyecto actualizado exitosamente.');
    }

    /**
     * Marca un proyecto como aprobado. Solo para Coordinadores.
     */
    public function aprobar(Proyecto $proyecto)
    {
        // Comprobación manual para aprobar
        if (!Auth::user()->hasRole('Coordinador')) { // O un rol superior si aplica
            abort(403, 'No tienes permiso para aprobar proyectos.');
        }
        $proyecto->estado = 'Aprobado';
        $proyecto->save();

        return redirect()->back()->with('success', 'Proyecto aprobado correctamente.');
    }

    /**
     * Marca un proyecto como rechazado. Solo para Coordinadores.
     */
    public function rechazar(Proyecto $proyecto)
    {
        // Comprobación manual para rechazar
        if (!Auth::user()->hasRole('Coordinador')) { // O un rol superior si aplica
            abort(403, 'No tienes permiso para rechazar proyectos.');
        }
        $proyecto->estado = 'Rechazado';
        $proyecto->save();

        return redirect()->back()->with('success', 'Proyecto rechazado.');
    }

    /**
     * Elimina un adjunto de un proyecto.
     */
    public function eliminarAdjunto(Adjunto $adjunto)
    {
        // Comprobación manual de permiso para eliminar adjunto
        // Si el usuario autenticado NO es el creador del proyecto al que pertenece el adjunto
        // Y NO es Administrador
        // Entonces deniega el acceso.
        if (Auth::id() !== $adjunto->proyecto->creador_id && !Auth::user()->hasAnyRole(['Administrador'])) {
            abort(403, 'No tienes permiso para eliminar este adjunto.');
        }

        if (Storage::disk('public')->exists($adjunto->ruta_almacenamiento)) {
            Storage::disk('public')->delete($adjunto->ruta_almacenamiento);
        }

        $adjunto->delete();

        return back()->with('success', 'Adjunto eliminado exitosamente.');
    }
}