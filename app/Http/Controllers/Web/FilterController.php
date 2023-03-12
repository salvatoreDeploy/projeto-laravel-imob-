<?php

namespace LaraDev\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaraDev\Http\Controllers\Controller;

class FilterController extends Controller
{
    public function search(Request $request)
    {
        session()->remove('category');
        session()->remove('type');
        session()->remove('neighborhood');

        if ($request->search === 'buy'){
            session()->put('sale', true);
            session()->remove('rent');
            $properties = $this->createQuery('category');
        }

        if($request->search  === 'rent'){
            session()->put('rent', true);
            session()->remove('sale');
            $properties = $this->createQuery('category');
        }

        if ($properties->count()){
            foreach ($properties as $categoryProperty){
                $category[] = $categoryProperty->category;
            }
           $collect = collect($category);
           return response()->json($this->setResponse('success',$collect->unique()->toArray()));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));
    }

    private function setResponse(string $status, array $data = null, string $message = null)
    {
        return [
            'status' => $status,
            'data' => $data
        ];
    }

    public function category(Request $request)
    {
        session()->remove('type');
        session()->remove('neighborhood');

        session()->put('category', $request->search);
        $typeProperties = $this->createQuery('type');

        if ($typeProperties->count()){
            foreach ($typeProperties as $property){
                $type[] = $property->type;
            }
            $collect = collect($type);
            return response()->json($this->setResponse('success',$collect->unique()->toArray()));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));

    }

    public function type(Request $request)
    {
        session()->remove('neighborhood');

        session()->put('type', $request->search);

        $neighborhoodProperties = $this->createQuery('neighborhood');

        if ($neighborhoodProperties->count()){
            foreach ($neighborhoodProperties as $property){
                $neighbordood[] = $property->neighborhood;
            }
            $collect = collect($neighbordood);
            return response()->json($this->setResponse('success',$collect->unique()->toArray()));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));

    }

    public function neighborhood(Request $request)
    {
        session()->put('neighborhood', $request->search);

        $bedroomsProperties = $this->createQuery('bedrooms');

        if ($bedroomsProperties->count()){
            foreach ($bedroomsProperties as $property){
                $bedrooms[] = $property->bedrooms;
            }
            $collect = collect($bedrooms);
            return response()->json($this->setResponse('success',$collect->unique()->toArray()));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));
    }


    private function createQuery($field)
    {
        $sale = session('sale');
        $rent = session('rent');
        $category = session('category');
        $type = session('type');
        $neighborhood = session('neighborhood');
        $status = true;

        return DB::table('properties')
            ->when($sale, function ($query, $sale){
                return $query->where('sale', $sale);
            })
            ->when($rent, function ($query, $rent){
                return $query->where('rent', $rent);
            })
            ->when($category, function ($query, $category){
                return $query->where('category', $category);
            })
            ->when($type, function ($query, $type){
                return $query->whereIn('type', $type);
            })
            ->when($neighborhood, function ($query, $neighborhood){
                return $query->whereIn('neighborhood', $neighborhood);
            })
            ->when($status, function ($query, $status){
                return $query->where('status', $status);
            })
            ->get([$field]);
    }
}
