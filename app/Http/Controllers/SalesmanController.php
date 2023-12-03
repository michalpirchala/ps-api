<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Resources\SalesmanCollection;
use App\Http\Resources\SalesmanResource;
use App\Models\Salesman;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\FlareClient\Api;

class SalesmanController extends Controller
{
    const ALLOWED_SORT_FIELD = ['created_at', 'updated_at'];

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $orderField = $request->input('sort', 'created_at');
        $order =  'ASC';
        if ($orderField[0] == '-') {
            $order =  'DESC';
            $orderField = substr($orderField, 1);
        }

        if (!in_array($orderField, self::ALLOWED_SORT_FIELD)) {
            throw (new ApiException(400))
                ->addError("BAD_REQUEST", "Sort field not allowed. Use one of: ".join(", ", self::ALLOWED_SORT_FIELD));
        }

        return new SalesmanCollection(Salesman::orderBy($orderField, $order)->paginate($perPage));
    }

    public function store()
    {

    }

    public function show(string $id)
    {
        try {
            return new SalesmanResource(Salesman::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            abortNotFound($e, "PERSON_NOT_FOUND", "Salesman", $id);
        }
    }

    public function update(string $id)
    {
        try {
            $salesman = Salesman::findOrFail($id);

            return new SalesmanResource($salesman);
        } catch (ModelNotFoundException $e) {
            abortNotFound($e, "PERSON_NOT_FOUND", "Salesman", $id);
        }
    }

    public function destroy(string $id)
    {
        try {
            $salesman = Salesman::findOrFail($id);
            $salesman->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            abortNotFound($e, "PERSON_NOT_FOUND", "Salesman", $id);
        }
    }
}
