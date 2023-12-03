<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Resources\SalesmanCollection;
use App\Http\Resources\SalesmanResource;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\Salesman;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;
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

    public function store(Request $request)
    {
        $validated = $this->_getValidatedData($request, ['prosight_id' => 'required|size:5|unique:salesmen']);

        try {
            DB::beginTransaction();
            $salesman = new Salesman($validated);
            $salesman->save();
            $salesman->saveTitles(array_merge($validated['titles_before'], $validated['titles_after']));
            DB::commit();
            return new SalesmanResource($salesman);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(string $id)
    {
        try {
            return new SalesmanResource(Salesman::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            abortNotFound($e, "PERSON_NOT_FOUND", "Salesman", $id);
        }
    }

    public function update(string $id, Request $request)
    {
        try {
            $salesman = Salesman::findOrFail($id);

            DB::beginTransaction();
            $validated = $this->_getValidatedData(
                $request,
                [
                    'prosight_id' => [
                        'required',
                        'size:5',
                        Rule::unique('salesmen')->ignore($salesman),
                    ],
                ],
            );

            $salesman->update($validated);
            $salesman->saveTitles(array_merge($validated['titles_before'], $validated['titles_after']));
            DB::commit();

            return new SalesmanResource($salesman);
        } catch (ModelNotFoundException $e) {
            abortNotFound($e, "PERSON_NOT_FOUND", "Salesman", $id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(string $id)
    {
        try {
            $salesman = Salesman::findOrFail($id);
            $salesman->titles()->detach();
            $salesman->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            abortNotFound($e, "PERSON_NOT_FOUND", "Salesman", $id);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function _getValidatedData(Request $request, array $additionalRules): array
    {
        $rules = [
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:2|max:50',
            'titles_before' => 'array|distinct|min:0|max:10',
            'titles_after' => 'array|distinct|min:0|max:10',
            'titles_before.*' => [
                'min:2',
                'max:10',
                Rule::exists('titles', 'name')->where(function (Builder $query) {
                    return $query->where('type', Title::TYPE_BEFORE);
                }),
            ],
            'titles_after.*' => [
                'min:2',
                'max:10',
                Rule::exists('titles', 'name')->where(function (Builder $query) {
                    return $query->where('type', Title::TYPE_AFTER);
                }),
            ],
            'email' => 'required|email',
            'phone' => 'string',
            'gender' => 'sometimes|exists:genders,code',
            'marital_status' => 'required|exists:marital_statuses,code',
        ];

        $validated = $request->validate(array_merge($rules, $additionalRules));

        $Gender = Gender::where('code', $validated['gender'])->firstOrFail('id');
        $validated['gender_id'] = $Gender->id;

        $MS = MaritalStatus::where('code', $validated['marital_status'])->firstOrFail('id');
        $validated['marital_status_id'] = $MS->id;

        return $validated;
    }
}
