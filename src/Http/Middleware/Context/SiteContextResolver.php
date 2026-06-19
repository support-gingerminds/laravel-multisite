<?php

namespace Gingerminds\LaravelMultisite\Http\Middleware\Context;

use Gingerminds\LaravelMultisite\Models\Site\Site;
use Illuminate\Http\Request;

class SiteContextResolver
{
    public function resolve(Request $request): ?Site
    {
        if ($request->hasSession() && ($siteId = $request->session()->get('admin_site_id'))) {
            return Site::find((int) $siteId);
        }

        if ($siteId = $request->header('X-Site-Id')) {
            return Site::find((int) $siteId);
        }

        if ($site = Site::where('url', 'LIKE', '%' . $request->getHost() . '%')->first()) {
            return $site;
        }

        return Site::first();
    }
}
