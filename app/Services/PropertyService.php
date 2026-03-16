<?php

namespace App\Services;

use App\Models\Property;

class PropertyService {

    private $propertyModel;
    public function __construct(Property $propertyModel)
    {
        $this->propertyModel = $propertyModel;
    }

    public function insert($data, $productId)
    {
        $dataForProperty = [
            'product_id' => $productId, 
            'year_of_manufacture' => $data['year_of_manufacture'],
            'fuel' => $data['fuel'],
            'license_plate' => $data['license_plate'],
            'miliage' => $data['miliage'],
            'exchange' => $data['exchange'] ?? 0,
            'bodywork' => $data['bodywork'],
            'accept_exchange' => $data['accept_exchange'],
            'review_done' => $data['review_done'],
            'color' => $data['color'],
            'chassis' => $data['chassis'] ?? null,
            'engine' => $data['engine'] ?? null,
            'reindeer' => $data['reindeer'] ?? null
        ];

        Property::create($dataForProperty);
    }


    public function update($data, $productId)
    {
        $dataForProperty = [
            'product_id' => $productId, 
            'year_of_manufacture' => $data['year_of_manufacture'],
            'fuel' => $data['fuel'],
            'license_plate' => $data['license_plate'],
            'miliage' => $data['miliage'],
            'exchange' => $data['exchange'] ?? 0,
            'bodywork' => $data['bodywork'],
            'accept_exchange' => $data['accept_exchange'],
            'review_done' => $data['review_done'],
            'color' => $data['color'],
            'chassis' => $data['chassis'],
            'engine' => $data['engine'],
            'reindeer' => $data['reindeer'],
        ];
        
        $this->propertyModel->where('product_id', $productId)->first()->update($dataForProperty);
        
    }
}