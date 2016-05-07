<?php

namespace WWON\Restify;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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
    private $orchestrator;

    /**
     * @var string
     */
    private $version;

    /**
     * ApiController constructor
     *
     * @param string $version
     */
    public function __construct($version)
    {
        $this->orchestrator = new Orchestrator(Config::get('restify.prefix'));
        $this->version = $version;
    }

    /**
     * success method
     *
     * @param mixed $result
     * @param EmbedInstruction|null $embedInstruction
     * @param array $meta
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($result, EmbedInstruction $embedInstruction = null, array $meta = [])
    {
        if (empty($result)) {
            return response()->json('', self::HTTP_SUCCESS_NO_CONTENT);
        }

        if (is_string($result)) {
            return response()->json(['message' => $result], self::HTTP_SUCCESS);
        }

        return $this->handle($result, self::HTTP_SUCCESS, $embedInstruction, $meta);
    }

    /**
     * created method
     *
     * @param mixed $result
     * @param array $meta
     * @return \Illuminate\Http\JsonResponse
     */
    protected function created($result, array $meta = [])
    {
        return $this->handle($result, self::HTTP_CREATED, null, $meta);
    }

    /**
     * handle method
     *
     * @param null $result
     * @param int $httpCode
     * @param EmbedInstruction|null $embedInstruction
     * @param array $meta
     * @return \Illuminate\Http\JsonResponse
     */
    private function handle($result = null, $httpCode = self::HTTP_SUCCESS, EmbedInstruction $embedInstruction = null, array $meta = [])
    {
        if ($result instanceof LengthAwarePaginator) {
            /* @var LengthAwarePaginator $input */
            $items = $result->items();
        } elseif ($result instanceof Collection) {
            /* @var Collection $input */
            $items = $result->all();
        } else {
            $items = $result;
        }

        if ($result instanceof LengthAwarePaginator) {
            $meta['pagination'] = [
                'current_page' => $result->currentPage(),
                'has_next_page' => $result->hasMorePages(),
                'last_page' => $result->lastPage(),
                'per_page' => $result->perPage()
            ];
        }

        return $this->raw(
            $this->transform($items, $embedInstruction),
            $meta,
            $httpCode
        );
    }

    /**
     * transform method
     *
     * @param mixed $items
     * @param EmbedInstruction|null $embedInstruction
     * @return array
     */
    private function transform($items, EmbedInstruction $embedInstruction = null)
    {
        if (empty($embedInstruction)) {
            $embedInstruction = new EmbedInstruction();
        }

        return $this->orchestrator
            ->with($embedInstruction->availableEmbeds)
            ->embeds($embedInstruction->embeds)
            ->version($this->version)
            ->transform($items);
    }

    /**
     * raw method
     *
     * @param array $data
     * @param array $meta
     * @param int $httpCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function raw(array $data = [], array $meta = [], $httpCode = self::HTTP_SUCCESS)
    {
        return response()->json([
                'data' => $data,
                'meta' => $meta
            ],
            $httpCode
        );
    }

    /**
     * notFound method
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFound()
    {
        return $this->rawError(trans('errors.generic.not_found'), 0, self::HTTP_NOT_FOUND);
    }

    /**
     * rawError method
     *
     * @param $errors
     * @param int $errorCode
     * @param $httpCode
     * @param array $meta
     * @return \Illuminate\Http\JsonResponse
     */
    protected function rawError($errors, $errorCode = 0, $httpCode, array $meta = [])
    {
        return response()->json([
            'errors' => $errors,
            'error_code' => $errorCode,
            'meta' => $meta
        ],
            $httpCode
        );
    }

}