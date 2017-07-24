<?php

namespace App\Controllers;

use Fermi\Response;
use Journey\Authentication;
use Psr\Http\Message\RequestInterface as Request;

class LoginController
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
        return Response::view('auth/login', []);
    }

    /**
     * Perform a login.
     *
     * @param  Psr\Http\Message\RequestInterface  $request  The request http object
     * @param  Psr\Http\Message\ResponseInterface $response The response http object
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function login(Request $request)
    {
        $auth = new Authentication();
        $data = $request->getParsedBody();
        if ($auth->authenticate($data['username'], $data['password'])) {
            return Response::redirect('/admin');
        }
        return Response::view('auth/login', [
            'message' => 'Incorrect username or password'
        ]);
    }

    /**
     * Perform a logout.
     *
     * @param  Psr\Http\Message\RequestInterface  $request  The request http object
     * @param  Psr\Http\Message\ResponseInterface $response The response http object
     * @return Psr\Http\Message\ResponseInterface
     */
    public static function logout(Request $request)
    {
        Authentication::unauthenticate();
        return Response::redirect('/login');
    }
}
