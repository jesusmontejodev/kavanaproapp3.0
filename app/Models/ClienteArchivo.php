<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ClienteArchivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_cliente',
        'url_archivo',
        'nombre_archivo',
    ];

    // Agrega esto para que los atributos dinámicos funcionen
    protected $appends = [
        'extension',
        'icono',
        'color',
        'ruta_completa',
        'tamano_formateado',
        'mime_type',
        'es_visualizable'
    ];

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    // Acceso al usuario a través del cliente
    public function usuario()
    {
        return $this->cliente ? $this->cliente->user : null;
    }

    // Métodos de ayuda
    public function getExtensionAttribute()
    {
        return pathinfo($this->nombre_archivo, PATHINFO_EXTENSION);
    }

    public function getIconoAttribute()
    {
        $extension = strtolower($this->extension);

        $iconos = [
            'pdf' => 'fas fa-file-pdf text-red-500',
            'jpg' => 'fas fa-file-image text-green-500',
            'jpeg' => 'fas fa-file-image text-green-500',
            'png' => 'fas fa-file-image text-green-500',
            'gif' => 'fas fa-file-image text-green-500',
            'doc' => 'fas fa-file-word text-blue-500',
            'docx' => 'fas fa-file-word text-blue-500',
            'xls' => 'fas fa-file-excel text-green-600',
            'xlsx' => 'fas fa-file-excel text-green-600',
            'txt' => 'fas fa-file-alt text-gray-500',
            'zip' => 'fas fa-file-archive text-purple-500',
            'rar' => 'fas fa-file-archive text-purple-500',
        ];

        return $iconos[$extension] ?? 'fas fa-file text-gray-400';
    }

    public function getColorAttribute()
    {
        $extension = strtolower($this->extension);

        $colores = [
            'pdf' => 'red',
            'jpg' => 'green',
            'jpeg' => 'green',
            'png' => 'green',
            'gif' => 'green',
            'doc' => 'blue',
            'docx' => 'blue',
            'xls' => 'green',
            'xlsx' => 'green',
            'txt' => 'gray',
            'zip' => 'purple',
            'rar' => 'purple',
        ];

        return $colores[$extension] ?? 'gray';
    }

    /**
     * Verificar permisos para el usuario autenticado
     */
    public function usuarioPuedeAcceder($userId = null)
    {
        if (!$this->cliente) return false;

        return $this->cliente->id_user == ($userId ?? auth()->id());
    }

    /**
     * Verificar permisos para eliminar
     */
    public function usuarioPuedeEliminar($userId = null)
    {
        return $this->usuarioPuedeAcceder($userId);
    }

    /**
     * Obtener la ruta absoluta del archivo
     */
    public function getRutaCompletaAttribute()
    {
        return storage_path('app/' . $this->url_archivo);
    }

    /**
     * Verificar si el archivo existe en storage
     */
    public function archivoExiste()
    {
        try {
            return Storage::disk('local')->exists($this->url_archivo);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtener tamaño del archivo formateado
     */
    public function getTamanoFormateadoAttribute()
    {
        try {
            $bytes = Storage::disk('local')->size($this->url_archivo);

            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } else {
                return $bytes . ' bytes';
            }
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Obtener MIME type del archivo
     */
    public function getMimeTypeAttribute()
    {
        try {
            return Storage::disk('local')->mimeType($this->url_archivo);
        } catch (\Exception $e) {
            return 'application/octet-stream';
        }
    }

    /**
     * Verificar si es visualizable en el navegador
     */
    public function getEsVisualizableAttribute()
    {
        $mime = $this->mime_type;
        $extension = strtolower($this->extension);

        // Archivos que se pueden mostrar en el navegador
        $visualizables = [
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
            'text/plain',
            'text/html',
        ];

        return in_array($mime, $visualizables) ||
               in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'txt', 'html']);
    }

    // ELIMINA ESTE MÉTODO DEL MODELO - Debe estar en el Controlador
    // public function download($id)
    // {
    //     // Este método no debe estar aquí
    // }
}
