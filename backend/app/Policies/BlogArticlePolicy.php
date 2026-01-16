<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BlogArticle;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogArticlePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BlogArticle');
    }

    public function view(AuthUser $authUser, BlogArticle $blogArticle): bool
    {
        return $authUser->can('View:BlogArticle');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BlogArticle');
    }

    public function update(AuthUser $authUser, BlogArticle $blogArticle): bool
    {
        return $authUser->can('Update:BlogArticle');
    }

    public function delete(AuthUser $authUser, BlogArticle $blogArticle): bool
    {
        return $authUser->can('Delete:BlogArticle');
    }

    public function restore(AuthUser $authUser, BlogArticle $blogArticle): bool
    {
        return $authUser->can('Restore:BlogArticle');
    }

    public function forceDelete(AuthUser $authUser, BlogArticle $blogArticle): bool
    {
        return $authUser->can('ForceDelete:BlogArticle');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BlogArticle');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BlogArticle');
    }

    public function replicate(AuthUser $authUser, BlogArticle $blogArticle): bool
    {
        return $authUser->can('Replicate:BlogArticle');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BlogArticle');
    }

}