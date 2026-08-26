<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PhoneBock\StorePhoneBockRequest;
use App\Http\Requests\Admin\PhoneBock\UpdatePhoneBockRequest;
use App\Http\Requests\Admin\PhoneBock\UploadExcelRequest;
use App\Http\Resources\PhoneBock\PhoneBockResource;
use App\Imports\PhoneBockImport;
use App\Services\PhoneBock\PhoneBockService;
use Maatwebsite\Excel\Facades\Excel;

class PhoneBockController extends Controller
{
    public function __construct(
        private readonly PhoneBockService $phoneBockService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->phoneBockService->dataTable();
        return $this->dataResponseCollection(PhoneBockResource::collection($response));
    }

    public function all()
    {
        $response = $this->phoneBockService->getAll();
        return $this->dataResponseCollection(PhoneBockResource::collection($response));
    }

    public function find($id)
    {
        $response = $this->phoneBockService->find($id);
        return $this->dataResponse(PhoneBockResource::make($response));
    }

    public function store(StorePhoneBockRequest $request)
    {
        $this->phoneBockService->store($request->get("name"), $request->get("mobile"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.contact")]));
    }

    public function update(UpdatePhoneBockRequest $request)
    {
        $this->phoneBockService->update($request->get("id"), $request->get("name"), $request->get("mobile"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.contact")]));
    }
    public function uploadExcel(UploadExcelRequest $request)
    {
        Excel::import(new PhoneBockImport, $request->file('excel_file'));

        return $this->successResponse(__("action.submit", ["attr" => __("attr.contact")]));
    }
}
