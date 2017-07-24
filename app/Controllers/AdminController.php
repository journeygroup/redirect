<?php

namespace App\Controllers;

use PDO;
use App\Models\Redirect;
use Fermi\Response;
use Journey\Authentication;
use Psr\Http\Message\RequestInterface as Request;

class AdminController
{
    /**
     * Admin.
     *
     * @param  Psr\Http\Message\RequestInterface  $request  The request http object
     * @param  Psr\Http\Message\ResponseInterface $response The response http object
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function index(Request $request)
    {
        Authentication::restrict(1);
        return Response::view('admin/admin', [
            'redirects' => Redirect::orderBy('weight', 'asc')->get()
        ]);
    }

    /**
     * Admin.
     *
     * @param  Psr\Http\Message\RequestInterface  $request  The request http object
     * @param  Psr\Http\Message\ResponseInterface $response The response http object
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function query(Request $request)
    {
        $data = ['message' => 'no query executed'];
        Authentication::restrict(1);
        $queries = $request->getQueryParams('q');
        if (isset($queries['q'])) {
            $q = $queries['q'];
            $pdo = $request->getAttribute('db');
            $result = $pdo->query($q);
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
        }
        return Response::json($data);
    }

    /**
     * Add an item.
     *
     * @param  Psr\Http\Message\RequestInterface  $request  The request http object
     * @param  Psr\Http\Message\ResponseInterface $response The response http object
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function add(Request $request)
    {
        Authentication::restrict(1);
        $data = array_merge([
            'weight' => 0,
        ], $request->getParsedBody());
        Redirect::create($data);
        return Response::redirect('/admin');
    }

    /**
     * Update an item.
     *
     * @param  Psr\Http\Message\RequestInterface  $request  The request http object
     * @param  Psr\Http\Message\ResponseInterface $response The response http object
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function update(Request $request)
    {
        Authentication::restrict(1);
        $data = $request->getParsedBody();
        Redirect::findOrFail($data['id'])->update($data);
        return Response::json(['success' => 1]);
    }

    /**
     * Delete an item.
     *
     * @param  Psr\Http\Message\RequestInterface  $request  The request http object
     * @param  Psr\Http\Message\ResponseInterface $response The response http object
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function delete(Request $request)
    {
        Authentication::restrict(1);
        $data = $request->getParsedBody();
        Redirect::findOrFail($data['id'])->delete();
        return Response::json(['success' => 1]);
    }

    /**
     * Reweight.
     *
     * @param  Psr\Http\Message\RequestInterface  $request  The request http object
     * @param  Psr\Http\Message\ResponseInterface $response The response http object
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function weights(Request $request)
    {
        Authentication::restrict(1);
        $data = $request->getParsedBody();
        if (is_array($data['weights'])) {
            foreach ($data['weights'] as $weight => $id) {
                Redirect::find($id)->update(['weight' => $weight]);
            }
        }
        return Response::json(['success' => 1]);
    }
}
