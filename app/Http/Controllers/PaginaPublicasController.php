<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\MediaProyecto;
use App\Models\User;

class PaginaPublicasController extends Controller
{
    /**
     * Mostrar un inmueble público (open/{id})
     * Solo muestra el proyecto sin datos de usuario específico
     */
    public function show($id)
    {
        // Buscar el proyecto con sus relaciones
        $proyecto = Proyecto::with(['mediaProyectos', 'linkProyectos'])
                        ->find($id);

        if (!$proyecto) {
            abort(404, 'Proyecto no encontrado');
        }

        return view('paginaspublicas.show', [
            'proyecto' => $proyecto,
            'medias' => $proyecto->mediaProyectos
        ]);
    }

    /**
     * Mostrar un inmueble público con usuario específico (open/{id}/usuario/{userId})
     * Mismo proyecto pero con datos de usuario personalizados
     */
    public function showWithUser($proyectoId, $userId)
    {
        // Buscar el proyecto (plantilla base)
        $proyecto = Proyecto::with(['mediaProyectos'])
                        ->find($proyectoId);

        if (!$proyecto) {
            abort(404, 'Proyecto no encontrado');
        }

        // Buscar el usuario (para mostrar sus datos de contacto)
        $usuario = User::find($userId);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        // Datos personalizados
        $proyectoPersonalizado = (object) [
            // Datos base del proyecto
            'id' => $proyecto->id,
            'nombre' => $proyecto->nombre,
            'descripcion' => $proyecto->descripcion,
            'url_imagen' => $proyecto->url_imagen,
            'link_proyecto' => $proyecto->link_proyecto,

            // Personalizar descripción con datos del usuario
            'descripcion_personalizada' => str_replace(
                ['{nombre_usuario}', '{empresa_usuario}'],
                [$usuario->name, $usuario->empresa],
                $proyecto->descripcion
            )
        ];

        // Obtener ReCaptcha public key para el formulario
        $recaptchaPublicKey = env('RECAPTCHA_PUBLIC', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');

        return view('paginaspublicas.show-with-user', [
            'proyecto' => $proyectoPersonalizado,
            'proyecto_base' => $proyecto, // Mantener referencia al proyecto original
            'medias' => $proyecto->mediaProyectos,
            'usuario' => $usuario,
            'recaptcha_public_key' => $recaptchaPublicKey // Pasamos la clave al frontend
        ]);
    }

    /**
     * Mostrar perfil público de usuario
     */
    public function showUserProfile($id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        // Obtener todos los proyectos disponibles (plantillas)
        $proyectos = Proyecto::with(['mediaProyectos'])
                        ->orderBy('created_at', 'desc')
                        ->limit(8)
                        ->get();

        // Para cada proyecto, crear una URL personalizada para este usuario
        $proyectosConLinks = $proyectos->map(function($proyecto) use ($usuario) {
            return (object) [
                'id' => $proyecto->id,
                'nombre' => $proyecto->nombre,
                'descripcion' => $proyecto->descripcion,
                'url_imagen' => $proyecto->url_imagen,
                'url_personalizada' => route('public.projects.withUser', [
                    'proyecto' => $proyecto->id,
                    'usuario' => $usuario->id
                ])
            ];
        });

        return view('paginaspublicas.user-profile', [
            'usuario' => $usuario,
            'totalProyectos' => $proyectos->count(),
            'proyectosRecientes' => $proyectosConLinks->take(4)
        ]);
    }

    /**
     * Listar todos los proyectos públicos (plantillas)
     */
    public function index()
    {
        $proyectos = Proyecto::with(['mediaProyectos'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(12);

        return view('paginaspublicas.index', [
            'proyectos' => $proyectos
        ]);
    }

    /**
     * API para datos de contacto
     */
    public function getContactInfo($proyectoId, $userId = null)
    {
        try {
            $proyecto = Proyecto::find($proyectoId);

            if (!$proyecto) {
                throw new \Exception('Proyecto no encontrado');
            }

            $data = [
                'proyecto' => [
                    'id' => $proyecto->id,
                    'nombre' => $proyecto->nombre,
                    'imagen' => $proyecto->url_imagen,
                    'descripcion' => $proyecto->descripcion
                ],
                'contacto' => []
            ];

            // Si hay userId, incluir datos del usuario
            if ($userId) {
                $usuario = User::find($userId);

                if ($usuario) {
                    $data['contacto'] = [
                        'id' => $usuario->id,
                        'nombre' => $usuario->name,
                        'email' => $usuario->email,
                        'telefono' => $usuario->phone,
                        'empresa' => $usuario->empresa,
                        'avatar' => $usuario->foto_perfil,
                        'estado' => $usuario->estado,
                        'ciudad' => $usuario->ciudad
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Método para redirección o página de éxito después de enviar lead
     * (Opcional - puedes usar este para mostrar una página de agradecimiento)
     */
    public function leadSuccess($leadId = null)
    {
        return view('paginaspublicas.lead-success', [
            'lead_id' => $leadId,
            'message' => '¡Gracias por tu interés! Nos pondremos en contacto contigo pronto.'
        ]);
    }

    /**
     * Buscar proyectos por término (para búsqueda en la página de listado)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $proyectos = Proyecto::with(['mediaProyectos'])
                        ->where('nombre', 'like', "%{$query}%")
                        ->orWhere('descripcion', 'like', "%{$query}%")
                        ->orderBy('created_at', 'desc')
                        ->paginate(12);

        return view('paginaspublicas.index', [
            'proyectos' => $proyectos,
            'search_query' => $query
        ]);
    }

    /**
     * API para obtener proyectos recientes (para widgets, sidebar, etc.)
     */
    public function getRecentProjects($limit = 6)
    {
        try {
            $proyectos = Proyecto::with(['mediaProyectos'])
                            ->orderBy('created_at', 'desc')
                            ->limit($limit)
                            ->get()
                            ->map(function($proyecto) {
                                return [
                                    'id' => $proyecto->id,
                                    'nombre' => $proyecto->nombre,
                                    'descripcion' => $proyecto->descripcion,
                                    'url_imagen' => $proyecto->url_imagen,
                                    'created_at' => $proyecto->created_at->format('d/m/Y'),
                                    'url' => route('public.projects.show', $proyecto->id)
                                ];
                            });

            return response()->json([
                'success' => true,
                'data' => $proyectos,
                'count' => $proyectos->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener proyectos'
            ], 500);
        }
    }
}
