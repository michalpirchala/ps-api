<?php

use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

function abortNotFound(ModelNotFoundException $e, string $code, $entity, $id) {
    throw (new ApiException(404, "", $e))
        ->addError($code, "$entity with such uuid not found. [$entity \"$id\" not found.]");
}
