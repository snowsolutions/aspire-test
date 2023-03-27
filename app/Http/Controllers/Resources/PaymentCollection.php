<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentCollection extends ResourceCollection
{
    public $collection = PaymentResource::class;

    /**
     * Transform the resource into an array
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'payments' => $this->collection,
        ];
    }
}
