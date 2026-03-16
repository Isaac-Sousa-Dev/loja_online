<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PopulateController extends Controller
{
    public function populateBrands($type)
    {
        $response = Http::withHeaders([
            'X-Subscription-Token' => env("API_FIPE_KEY")
        ])->get("https://parallelum.com.br/fipe/api/v1/$type/marcas");
        $marcas = $response->json();

        foreach ($marcas as $marca) {

            $brandCreated = Brand::create([
                'name' => $marca['nome'],
                'codigo' => $marca['codigo'],
                'type' => $type
            ]);

            $responseModelos = Http::withHeaders([
                'X-Subscription-Token' => env("API_FIPE_KEY")
            ])->get("https://parallelum.com.br/fipe/api/v1/$type/marcas/".$marca['codigo']."/modelos");
            $modelos = $responseModelos->json();

            if(isset($modelos['anos'])){
                foreach($modelos['anos'] as $ano) {
                    DB::table('years_by_models')->insert([
                        'name' => $ano['nome'],
                        'codigo' => $ano['codigo'],
                        'brand_id' => $brandCreated['id']
                    ]);        
                }
            }


            foreach($modelos['modelos'] as $modelo) {
                DB::table('modelos')->insert([
                    'name' => $modelo['nome'],
                    'codigo' => $modelo['codigo'],
                    'brand_id' => $brandCreated['id']
                ]);

                if(isset($modelos['anos'])){
                    foreach($modelos['anos'] as $ano) {
                        $responseFipeByModelAndYear = Http::withHeaders([
                            'X-Subscription-Token' => env("API_FIPE_KEY")
                        ])->get("https://parallelum.com.br/fipe/api/v1/$type/marcas/".$marca['codigo']."/modelos/".$modelo['codigo']."/anos/".$ano['codigo']);
                        $fipeByModelAndYear = $responseFipeByModelAndYear->json();
    
                        if(!isset($fipeByModelAndYear['error'])){
                            DB::table('fipe_by_model_and_years')->insert([
                                'vehicle_type' => $fipeByModelAndYear['TipoVeiculo'],
                                'price_fipe' => $fipeByModelAndYear['Valor'],
                                'brand_name' => $fipeByModelAndYear['Marca'],
                                'brand_id' => $brandCreated['id'],
                                'model_name' => $fipeByModelAndYear['Modelo'],
                                'model_year' => $fipeByModelAndYear['AnoModelo'],
                                'fuel' => $fipeByModelAndYear['Combustivel'],
                                'codigo_fipe' => $fipeByModelAndYear['CodigoFipe'],
                                'reference_month' => $fipeByModelAndYear['MesReferencia'],
                                'fuel_acronym' => $fipeByModelAndYear['SiglaCombustivel']
                            ]);
                        }
                    }
                }


            }
        }

        return response()->json('Dados Inseridos!', 201);
    }
}
