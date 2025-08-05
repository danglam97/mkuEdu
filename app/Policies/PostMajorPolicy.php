<?php

namespace App\Policies;

use App\Enums\Post\PostStatus;
use App\Models\Post;
use App\Models\User;
use App\Models\PostMajor;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostMajorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_post::major');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PostMajor $postMajor): bool
    {
        return $user->can('view_post::major');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_post::major');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PostMajor $postMajor): bool
    {
        return $user->can('update_post::major');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PostMajor $postMajor): bool
    {
        return $user->can('delete_post::major');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_post::major');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, PostMajor $postMajor): bool
    {
        return $user->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, PostMajor $postMajor): bool
    {
        return $user->can('{{ Restore }}');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, PostMajor $postMajor): bool
    {
        return $user->can('{{ Replicate }}');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('{{ Reorder }}');
    }
    public function approve(User $user, Post $post): bool
    {
        return $user->can('approve_post::major')
            && in_array($post->status, [
                PostStatus::Pending->value,
                PostStatus::Waiting->value,
            ]);
    }

    public function refuse(User $user, Post $post): bool
    {
        return $user->can('refuse_post::major')
            && in_array($post->status, [
                PostStatus::Pending->value,
                PostStatus::Waiting->value,
            ]);
    }
}
