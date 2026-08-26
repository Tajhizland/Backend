<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\PhoneBock\PhoneBockStoreDto;
use App\DTOs\PhoneBock\PhoneBockUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PhoneBock\StorePhoneBockRequest;
use App\Http\Requests\Admin\PhoneBock\UpdatePhoneBockRequest;
use App\Http\Requests\Admin\PhoneBock\UploadExcelRequest;
use App\Http\Resources\PhoneBock\PhoneBockResource;
use App\Imports\PhoneBockImport;
use App\Services\PhoneBock\PhoneBockServiceInterface;
use Maatwebsite\Excel\Facades\Excel;

class PhoneBockController extends Controller
{
    public function __construct(
        private readonly PhoneBockServiceInterface $phoneBockService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(PhoneBockResource::collection($this->phoneBockService->dataTable()));
    }

    public function all()
    {
        return $this->dataResponseCollection(PhoneBockResource::collection($this->phoneBockService->getAll()));
    }

    public function show($id)
    {
        return $this->dataResponse(PhoneBockResource::make($this->phoneBockService->find($id)));
    }

    public function store(StorePhoneBockRequest $request)
    {
        $this->phoneBockService->store(new PhoneBockStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.contact")]));
    }

    public function update($id, UpdatePhoneBockRequest $request)
    {
        $this->phoneBockService->update(new PhoneBockUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.contact")]));
    }

    public function uploadExcel(UploadExcelRequest $request)
    {
        Excel::import(new PhoneBockImport, $request->file('excel_file'));
        return $this->successResponse(__("action.submit", ["attr" => __("attr.contact")]));
    }
}
