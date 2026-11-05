<?php

namespace App\Policies;

use App\Models\User;
use App\Models\productos_categorias;
use Illuminate\Auth\Access\Response;

class ProductosCategoriasPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, productos_categorias $productosCategorias): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, productos_categorias $productosCategorias): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, productos_categorias $productosCategorias): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, productos_categorias $productosCategorias): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, productos_categorias $productosCategorias): bool
    {
        return false;
    }
}
