<?php

namespace Gingerminds\LaravelMultisite\Http\Middleware\Context;

use Closure;
use Gingerminds\LaravelMultisite\Models\Site\Site;
use Gingerminds\LaravelMultisite\Services\Context\SiteContext;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveSiteContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $site = $this->resolveSite($request);

        if (!$site instanceof Site) {
            abort(404, 'No site context resolved.');
        }

        app(SiteContext::class)->set($site);

        return $next($request);
    }

    protected function resolveSite(Request $request): ?Site
    {
        if ($request->hasSession() && ($siteId = $request->session()->get('admin_site_id'))) {
            return Site::query()
                ->whereKey($siteId)
                ->first();
        }

        if ($siteId = $request->header('X-Site-Id')) {
            return Site::query()
                ->whereKey($siteId)
                ->first();
        }

        $site = Site::query()
            ->where('url', 'LIKE', '%' . $request->getHost() . '%')
            ->first();

        if ($site) {
            return $site;
        }

        $site = Site::query()->first();

        if ($site) {
            return $site;
        }

        return null;
    }
}
