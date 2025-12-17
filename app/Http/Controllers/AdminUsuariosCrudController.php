<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordChangedNotification;
use Illuminate\Support\Facades\DB;

class AdminUsuariosCrudController extends Controller
{
    /**
     * Muestra la lista de usuarios con búsqueda y paginación
     */
    public function index(Request $request)
    {
        // Construir la consulta
        $query = User::with('roles')->latest();

        // Aplicar búsqueda si existe
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('empresa', 'like', "%{$search}%")
                    ->orWhere('ciudad', 'like', "%{$search}%")
                    ->orWhere('estado', 'like', "%{$search}%");
            });
        }

        // Paginar resultados
        $users = $query->paginate(20);

        return view('admin.usuarios.index', compact('users'));
    }

    /**
     * Muestra información detallada del usuario
     */
    public function show($id)
    {
        $user = User::with(['roles', 'leads', 'usuarioTareas.tarea'])->findOrFail($id);
        return view('admin.usuarios.show', compact('user'));
    }

    /**
     * Muestra formulario para editar usuario
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.usuarios.edit', compact('user'));
    }

    /**
     * Actualiza información del usuario
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'estado' => ['nullable', 'string', 'max:100'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'empresa' => ['nullable', 'string', 'max:100'],
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'estado', 'ciudad', 'empresa']));

        return redirect()->route('adminusuariosmaestro.show', $user->id)
            ->with('success', '✅ Información actualizada');
    }

    /**
     * Muestra el formulario para cambiar contraseña
     */
    public function cambiarPasswordForm($id)
    {
        $user = User::findOrFail($id);

        return view('admin.usuarios.cambiar-password', compact('user'));
    }

    /**
     * Actualiza la contraseña del usuario
     */
    public function cambiarPasswordfunction(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        $mensajeExito = '✅ Contraseña actualizada para ' . $user->name;

        return redirect()->route('adminusuariosmaestro.index')
            ->with('success', $mensajeExito);
    }

    /**
     * Genera una contraseña aleatoria segura
     */
    public function generarContraseñaAleatoria()
    {
        $longitud = 12;
        $minusculas = 'abcdefghijklmnopqrstuvwxyz';
        $mayusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeros = '0123456789';
        $simbolos = '!@#$%^&*';

        // Asegurar al menos un carácter de cada tipo
        $contraseña = $minusculas[random_int(0, 25)];
        $contraseña .= $mayusculas[random_int(0, 25)];
        $contraseña .= $numeros[random_int(0, 9)];
        $contraseña .= $simbolos[random_int(0, 7)];

        // Completar con caracteres aleatorios
        $todosCaracteres = $minusculas . $mayusculas . $numeros . $simbolos;
        for ($i = 4; $i < $longitud; $i++) {
            $contraseña .= $todosCaracteres[random_int(0, strlen($todosCaracteres) - 1)];
        }

        // Mezclar la contraseña
        $contraseña = str_shuffle($contraseña);

        return response()->json(['password' => $contraseña]);
    }

    /**
     * Activa/Desactiva usuario usando Soft Deletes
     */
    public function toggleEstado($id)
    {
        // Usar withTrashed para encontrar usuarios incluso si están "eliminados"
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            // Si está eliminado (deleted_at tiene fecha), restaurarlo
            $user->restore();
            $message = '✅ Usuario ' . $user->name . ' activado';
        } else {
            // Si está activo, "eliminarlo" (soft delete)
            $user->delete();
            $message = '✅ Usuario ' . $user->name . ' desactivado';
        }

        return back()->with('success', $message);
    }

    /**
     * Agrega un rol al usuario
     */
    public function asignarRol(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => ['required', 'in:usuario,coordinador,administrador'],
        ]);

        // Verificar si ya tiene el rol
        if ($user->hasRole($request->role)) {
            return back()->with('error', '⚠ El usuario ya tiene el rol: ' . $request->role);
        }

        $user->assignRole($request->role);

        return back()->with('success', '✅ Rol agregado: ' . $request->role);
    }

    /**
     * Remueve un rol específico del usuario
     */
    public function removerRol(Request $request, $userId, $roleId)
    {
        $user = User::findOrFail($userId);

        // Buscar el rol
        $role = Role::findOrFail($roleId);

        // Remover el rol
        $user->removeRole($role->name);

        return back()->with('success', '✅ Rol removido: ' . $role->name);
    }

    /**
     * Actualiza múltiples roles (usando checkboxes)
     */
    public function actualizarRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'roles' => ['array'],
            'roles.*' => ['in:usuario,coordinador,administrador'],
        ]);

        // Remover todos los roles primero
        $user->roles()->detach();

        // Asignar nuevos roles si hay alguno seleccionado
        if ($request->has('roles')) {
            foreach ($request->roles as $role) {
                $user->assignRole($role);
            }
            $message = '✅ Roles actualizados';
        } else {
            $message = '⚠ No se asignaron roles (usuario básico)';
        }

        return back()->with('success', $message);
    }

    public function usuarioClientes($id){

    }



}
