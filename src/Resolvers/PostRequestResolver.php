<?php

namespace App\Resolvers;

use Exception;
use Symfony\Component\HttpFoundation\Request;

class PostRequestResolver extends BaseRequestResolver
{
    /**
     * @throws Exception
     */
    public function checkRequestMethod(Request $request): void
    {
        if (!in_array($request->getMethod(), [Request::METHOD_POST, Request::METHOD_PUT, Request::METHOD_PATCH])) {
            throw new Exception('Неподдерживаемый тип запроса, допустимы POST, PUT, PATCH');
        }
    }

    public function getParamsFromRequest(Request $request): array
    {
        $result = $this->getRequestRouteParams($request);

        return match ($request->getContentTypeFormat()) {
            'json' => !empty($request->getContent())
                ? $this->strictConvert(array_merge($result, $request->toArray()))
                : $result,
            default => $result,
        };
    }
}
