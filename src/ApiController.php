<?php

namespace WWON\Restify;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Config;
use WWON\Dynamo\Orchestrator;

abstract class ApiController extends BaseController
{

    use DispatchesJobs, ValidatesRequests;

    const HTTP_SUCCESS = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_SUCCESS_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var Orchestrator
     */
    protected $orchestrator;

    /**
     * ApiController constructor
     */
    public function __construct()
    {
        $this->orchestrator = new Orchestrator(Config::get('api.prefix'));
    }

    public function handle()
    {
        //
    }

}