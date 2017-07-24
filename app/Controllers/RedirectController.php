<?php

namespace App\Controllers;

use App\Models\Log;
use App\Models\Redirect;
use Carbon\Carbon;
use Fermi\Response;
use Psr\Http\Message\RequestInterface as Request;

class RedirectController
{
    /**
     * Default Fermi hander.
     *
     * @param  Psr\Http\Message\RequestInterface  $request  The request http object
     * @param  Psr\Http\Message\ResponseInterface $response The response http object
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function index(Request $request)
    {
        $url = $request->getUri();
        $redirects = Redirect::orderBy('weight', 'asc')->get();
        foreach ($redirects as $redirect) {
            if (preg_match("#" . $redirect->pattern . "#", $url)) {
                $redirectTo = preg_replace("#" . $redirect->pattern . "#i", $redirect->replacement, $url);
                if ($redirectTo) {
                    $redirect->increment('redirects');
                    Log::create([
                        'host' => $request->getUri()->getHost(),
                        'url'  => $url,
                        'redirect' => $redirectTo,
                        'date' => Carbon::now()
                    ]);
                    return Response::redirect($redirectTo);
                }
            }
        }
        return Response::view('404', [], false, 404);
    }
}
