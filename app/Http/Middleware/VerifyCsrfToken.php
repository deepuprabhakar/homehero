<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/*', 
        'admin/proposal-edit-get-parts', 
        'admin/proposal-edit-get-parts-2',
        'admin/proposal-edit-get-steps'
    ];
}
