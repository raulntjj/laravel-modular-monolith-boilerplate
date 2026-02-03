<?php

declare(strict_types=1);

namespace Modules\Shared\Interface\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponse
{
    /**
     * Resposta de sucesso genérica
     */
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Resposta de erro genérica
     */
    public static function error(
        string $message = 'An error occurred',
        mixed $errors = null,
        int $status = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Resposta de criação bem-sucedida (201 Created)
     */
    public static function created(
        mixed $data = null,
        string $message = 'Resource created successfully'
    ): JsonResponse {
        return self::success($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Resposta de não encontrado (404 Not Found)
     */
    public static function notFound(
        string $message = 'Resource not found'
    ): JsonResponse {
        return self::error($message, null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Resposta de validação falhou (422 Unprocessable Entity)
     */
    public static function validationError(
        mixed $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return self::error($message, $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Resposta de não autorizado (401 Unauthorized)
     */
    public static function unauthorized(
        string $message = 'Unauthorized'
    ): JsonResponse {
        return self::error($message, null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Resposta de proibido (403 Forbidden)
     */
    public static function forbidden(
        string $message = 'Forbidden'
    ): JsonResponse {
        return self::error($message, null, Response::HTTP_FORBIDDEN);
    }

    /**
     * Resposta de conflito (409 Conflict)
     */
    public static function conflict(
        string $message = 'Conflict',
        mixed $errors = null
    ): JsonResponse {
        return self::error($message, $errors, Response::HTTP_CONFLICT);
    }

    /**
     * Resposta de erro interno do servidor (500 Internal Server Error)
     */
    public static function serverError(
        string $message = 'Internal server error'
    ): JsonResponse {
        return self::error($message, null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Resposta sem conteúdo (204 No Content)
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Resposta paginada
     */
    public static function paginated(
        array $items,
        int $total,
        int $perPage,
        int $currentPage,
        string $message = 'Data retrieved successfully'
    ): JsonResponse {
        return self::success([
            'items' => $items,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => (int) ceil($total / $perPage),
                'from' => ($currentPage - 1) * $perPage + 1,
                'to' => min($currentPage * $perPage, $total),
            ],
        ], $message);
    }
}
