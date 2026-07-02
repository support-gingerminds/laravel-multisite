<?php

namespace Gingerminds\LaravelMultisite\Http\Middleware\Context;

use Gingerminds\LaravelMultisite\Models\Site\Site;
use Illuminate\Http\Request;

class SiteContextResolver
{
    public function resolve(Request $request): ?Site
    {
        $siteId = $this->resolveSiteId($request);

        if ($siteId) {
            return Site::find((int) $siteId);
        }

        return Site::where('url', 'LIKE', '%' . $request->getHost() . '%')->first() ?? Site::first();
    }

    private function resolveSiteId(Request $request): mixed
    {
        if ($request->hasSession() && ($siteId = $request->session()->get('admin_site_id'))) {
            return $siteId;
        }

        return $request->header('X-Site-Id');
    }
}
