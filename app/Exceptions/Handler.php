<?php

namespace App\Exceptions;

use Facade\FlareClient\Http\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use jeremykenedy\LaravelRoles\App\Exceptions\LevelDeniedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $userLevelCheck = $exception instanceof LevelDeniedException;

        if ($userLevelCheck) {
            $level=$this->get_string_between($exception->getMessage(), "[", "]");
            $level=intval($level);
            if($level===2){
                if(Auth::user())return redirect()->route("compte.verify");
            }
            if($level<=3){
                if(Auth::user())return redirect()->route("compte.verify.conseiller");
                else return redirect()->route("login");
            }

            if ($request->expectsJson()) {
                return Response::json(array(
                    'error'    =>  403,
                    'message'   =>  'Unauthorized.'
                ), 403);
            }
            abort(403);
        }

        return parent::render($request, $exception);
    }

    private function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}
