<?php

namespace App\Policies;

use App\Models\TechnicalDescription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechnicalDescriptionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_technical::description');
    }

    public function view(User $user, TechnicalDescription $technicalDescription): bool
    {
        return $user->can('view_technical::description');
    }

    public function create(User $user): bool
    {
        return $user->can('create_technical::description');
    }

    public function update(User $user, TechnicalDescription $technicalDescription): bool
    {
        return $user->can('update_technical::description');
    }

    public function delete(User $user, TechnicalDescription $technicalDescription): bool
    {
        return $user->can('delete_technical::description');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_technical::description');
    }

    public function forceDelete(User $user, TechnicalDescription $technicalDescription): bool
    {
        return $user->can('force_delete_technical::description');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_technical::description');
    }

    public function restore(User $user, TechnicalDescription $technicalDescription): bool
    {
        return $user->can('restore_technical::description');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_technical::description');
    }

    public function replicate(User $user, TechnicalDescription $technicalDescription): bool
    {
        return $user->can('replicate_technical::description');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_technical::description');
    }

    public function import(User $user): bool
    {
        return $user->can('import_technical::description');
    }

    public function export(User $user): bool
    {
        return $user->can('export_technical::description');
    }
}
