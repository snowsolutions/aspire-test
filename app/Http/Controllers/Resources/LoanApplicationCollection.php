<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LoanApplicationCollection extends ResourceCollection
{
    public $collection = LoanApplicationResource::class;

    /**
     * Transform the resource into an array
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'loan_applications' => $this->collection
        ];
    }
}
