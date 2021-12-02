<?php


namespace App\Normalizer;

use Symfony\Component\HttpFoundation\Response;

class NotFoundHttpExceptionNormalizer extends AbstractNormalizer
{
    public function normalize(\Exception $exception)
    {

        $result['code'] = Response::HTTP_NOT_FOUND;

        $result['body'] = [
            'code' => Response::HTTP_NOT_FOUND,
            'message' => $exception->getMessage()
        ];

        if ($exception->getMessage() === "App\Entity\User object not found by the @ParamConverter annotation.") {
            $result['body'] = [
                'code' => Response::HTTP_NOT_FOUND,
                'message' => "The user you are trying to access does not exist."
            ];
        } elseif ($exception->getMessage() === "App\Entity\Product object not found by the @ParamConverter annotation.") {
            $result['body'] = [
                'code' => Response::HTTP_NOT_FOUND,
                'message' => "The product you are trying to access does not exist."
            ];
        } elseif ($exception->getMessage() === "App\Entity\Client object not found by the @ParamConverter annotation.") {
            $result['body'] = [
                'code' => Response::HTTP_NOT_FOUND,
                'message' => "The client you are trying to access does not exist."
            ];
        }

        return $result;

    }
}
