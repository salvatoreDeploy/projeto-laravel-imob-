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
        session()->remove('bedrooms');
        session()->remove('suites');
        session()->remove('bathrooms');
        session()->remove('garage');

        if ($request->search === 'buy') {
            session()->put('sale', true);
            session()->remove('rent');
            $properties = $this->createQuery('category');
        }

        if ($request->search === 'rent') {
            session()->put('rent', true);
            session()->remove('sale');
            $properties = $this->createQuery('category');
        }

        if ($properties->count()) {
            foreach ($properties as $categoryProperty) {
                $category[] = $categoryProperty->category;
            }
            $collect = collect($category);
            return response()->json($this->setResponse('success', $collect->unique()->toArray()));

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
        session()->remove('bedrooms');
        session()->remove('suites');
        session()->remove('bathrooms');
        session()->remove('garage');

        session()->put('category', $request->search);
        $typeProperties = $this->createQuery('type');

        if ($typeProperties->count()) {
            foreach ($typeProperties as $property) {
                $type[] = $property->type;
            }
            $collect = collect($type);
            return response()->json($this->setResponse('success', $collect->unique()->toArray()));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));

    }

    public function type(Request $request)
    {
        session()->remove('neighborhood');
        session()->remove('bedrooms');
        session()->remove('suites');
        session()->remove('bathrooms');
        session()->remove('garage');

        session()->put('type', $request->search);

        $neighborhoodProperties = $this->createQuery('neighborhood');

        if ($neighborhoodProperties->count()) {
            foreach ($neighborhoodProperties as $property) {
                $neighbordood[] = $property->neighborhood;
            }
            $collect = collect($neighbordood);
            return response()->json($this->setResponse('success', $collect->unique()->toArray()));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));

    }

    public function neighborhood(Request $request)
    {
        session()->remove('bedrooms');
        session()->remove('suites');
        session()->remove('bathrooms');
        session()->remove('garage');

        session()->put('neighborhood', $request->search);

        $bedroomsProperties = $this->createQuery('bedrooms');

        if ($bedroomsProperties->count()) {
            foreach ($bedroomsProperties as $property) {
                if ($property->bedrooms === 0 || $property->bedrooms === 1) {
                    $bedrooms[] = $property->bedrooms . ' Quarto';
                } else {
                    $bedrooms[] = $property->bedrooms . ' Quartos';
                }

            }
            $bedrooms[] = 'Indiferente';

            $collect = collect($bedrooms)->unique()->toArray();
            sort($collect);
            return response()->json($this->setResponse('success', $collect));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));
    }

    public function bedrooms(Request $request)
    {
        session()->remove('suites');
        session()->remove('bathrooms');
        session()->remove('garage');

        session()->put('bedrooms', $request->search);

        $bedroomsProperties = $this->createQuery('suites');

        if ($bedroomsProperties->count()) {
            foreach ($bedroomsProperties as $property) {
                if ($property->suites === 0 || $property->suites === 1) {
                    $suites[] = $property->suites . ' Suite';
                } else {
                    $suites[] = $property->suites . ' Suites';
                }

            }
            $suites[] = 'Indiferente';

            $collect = collect($suites)->unique()->toArray();
            sort($collect);

            return response()->json($this->setResponse('success', $collect));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));
    }

    public function suites(Request $request)
    {
        session()->put('suites', $request->search);

        session()->remove('bathrooms');
        session()->remove('garage');

        $suitesProperties = $this->createQuery('bathrooms');

        if ($suitesProperties->count()) {
            foreach ($suitesProperties as $property) {
                if ($property->bathrooms === 0 || $property->bathrooms === 1) {
                    $bathrooms[] = $property->bathrooms . ' Banheiro';
                } else {
                    $bathrooms[] = $property->bathrooms . ' Banheiros';
                }

            }

            $bathrooms[] = 'Indiferente';

            $collect = collect($bathrooms)->unique()->toArray();

            sort($collect);

            return response()->json($this->setResponse('success', $collect));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));
    }

    public function bathrooms(Request $request)
    {
        session()->put('bathrooms', $request->search);

        session()->remove('garage');

        $bathroomsProperties = $this->createQuery('garage,garage_covered');

        if ($bathroomsProperties->count()) {
            foreach ($bathroomsProperties as $property) {

                $property->garage = $property->garage + $property->garage_covered;

                if ($property->garage === 0 || $property->garage === 1) {
                    $garage[] = $property->garage . ' Garagem';
                } else {
                    $garage[] = $property->garage . ' Garagens';
                }

            }

            $garage[] = 'Indiferente';

            $collect = collect($garage)->unique()->toArray();

            sort($collect);

            return response()->json($this->setResponse('success', $collect));

        }
        return response()->json($this->setResponse('fail', [], 'Ooops, não foi possivel retornar nenhum dado para essa pesquisa!! '));
    }

    public function garage(Request $request)
    {
        session()->put('garage', $request->search);

        if(session('sale')){
            $priceBaseProperties = $this->createQuery('sale_price as price');
        }else{
            $priceBaseProperties = $this->createQuery('rent_price as price');
        }

        if ($priceBaseProperties->count()) {
            foreach ($priceBaseProperties as $property) {
                $price[] = 'R$ ' . number_format($property->price, 2, ',', '.');
            }

            $collect = collect($price)->unique()->toArray();

            sort($collect);

            return response()->json($this->setResponse('success', $collect));

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
        $bedrooms = session('bedrooms');
        $suites = session('suites');
        $bathrooms = session('bathrooms');
        $garage = session('garage');
        $status = true;

        return DB::table('properties')
            ->when($sale, function ($query, $sale) {
                return $query->where('sale', $sale);
            })
            ->when($rent, function ($query, $rent) {
                return $query->where('rent', $rent);
            })
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($type, function ($query, $type) {
                return $query->whereIn('type', $type);
            })
            ->when($neighborhood, function ($query, $neighborhood) {
                return $query->whereIn('neighborhood', $neighborhood);
            })
            ->when($bedrooms, function ($query, $bedrooms) {
                if ($bedrooms == 'Indiferente') {
                    return $query;
                }
                $bedrooms = (int)$bedrooms;

                return $query->where('bedrooms', $bedrooms);
            })
            ->when($suites, function ($query, $suites) {
                if ($suites == 'Indiferente') {
                    return $query;
                }
                $suites = (int)$suites;

                return $query->where('suites', $suites);
            })
            ->when($bathrooms, function ($query, $bathrooms) {
                if ($bathrooms == 'Indiferente') {
                    return $query;
                }
                $bathrooms = (int)$bathrooms;

                return $query->where('bathrooms', $bathrooms);
            })
            ->when($garage, function ($query, $garage) {
                if ($garage == 'Indiferente') {
                    return $query;
                }
                $garage = (int)$garage;

                return $query->whereRaw('garage + garage_covered = ? OR garage = ? OR garage_covered = ?', [$garage, $garage, $garage]);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get(explode(',', $field));
    }
}
