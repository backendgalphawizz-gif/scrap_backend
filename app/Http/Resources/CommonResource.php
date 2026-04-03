<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->convertToString(parent::toArray($request));
    }

    protected function convertToString($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->convertToString($value);
            }
            return $data;
        }

        // only convert scalar values
        if (is_scalar($data)) {
            return (string) $data;
        }

        return $data ?? '';
    }
}