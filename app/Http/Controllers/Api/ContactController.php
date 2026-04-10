<?php

namespace App\Http\Controllers\Api;

use App\Actions\ResolvePageContent;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageContentResource;

class ContactController extends Controller
{
    public function __construct(public ResolvePageContent $resolvePageContent) {}

    public function __invoke(): PageContentResource
    {
        return $this->resolvePageContent->handle('kontakt');
    }
}
