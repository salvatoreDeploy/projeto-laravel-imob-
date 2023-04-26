<?php

namespace LaraDev\Http\Controllers\Web;

use Illuminate\Http\Request;
use LaraDev\Http\Controllers\Controller;
use LaraDev\Property;

class WebController extends Controller
{
    public function home()
    {
        $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Encontre o imóvel do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
            route('web.home'),
            asset('frontend/assets/images/share.png'));

        $propertiesForSale = Property::sale()->available()->limit(3)->get();
        $propertiesForRent = Property::rent()->available()->limit(3)->get();

        return view('web.home', [
            'head' => $head,
            'propertiesForSale' => $propertiesForSale,
            'propertiesForRent' => $propertiesForRent
        ]);
    }
    public function contact()
    {
        $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Quer conversar com um corretor exclusivo e ter o atendimento diferenciado em busca do seu imóvel dos sonhos? Entre em contato com nossa equipe',
            route('web.contact'),
            asset('frontend/assets/images/share.png'));

        return view('web.contact', ["head" => $head]);
    }
    public function rent()
    {
        $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Alugue agora mesmo o imóvel do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
            route('web.rent'),
            asset('frontend/assets/images/share.png'));

        $filter = new FilterController();
        $filter->clearData();

        $properties = Property::rent()->available()->get();
        return view('web.filter', ["head" => $head, "properties" => $properties, "type" => "rent"]);
    }
    public function buy()
    {
        $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Compre agora mesmo o imóvel do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
            route('web.buy'),
            asset('frontend/assets/images/share.png'));

        $filter = new FilterController();
        $filter->clearData();

        $properties = Property::sale()->available()->get();
        return view('web.filter', ["head" => $head,"properties" => $properties, "type" => "sale"]);
    }

    public function buyProperty(Request $request)
    {
        $property = Property::where('slug', $request->slug)->first();

        $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', $property->headline ?? $property->title,
            route('web.buyProperty', ["property" => $property->slug]),
            $property->coverImage());

        return view('web.property', [
            'property' => $property,
            'head' => $head,
            'type' => 'sale'
        ]);
    }

    public function rentProperty(Request $request)
    {
        $property = Property::where('slug', $request->slug)->first();

        $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', $property->headline ?? $property->title,
            route('web.rentProperty', ["property" => $property->slug]),
            $property->coverImage());

        return view('web.property', [
            'property' => $property,
            'head' => $head,
            'type' => 'rent'
        ]);
    }

    public function filter()
    {
        $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Filtrando os imóveis do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
            route('web.filter'),
            asset('frontend/assets/images/share.png'));

        $filter = new FilterController();
        $itemProperty = $filter->createQuery('id');

        foreach ($itemProperty as $property){
            $properties[] = $property->id;
        }

        if(!empty($property)){
            $properties = Property::whereIn('id', $properties)->get();
        }else{
            $properties = Property::all();
        }

        return view('web.filter', [
            'head' => $head,
            'properties' => $properties
        ]);
    }

    public function experience()
    {
        $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar os imóveis do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
            route('web.experience'),
            asset('frontend/assets/images/share.png'));

        $filter = new FilterController();
        $filter->clearData();

        $properties = Property::whereNotNull('experience')->get();

        return view('web.filter', [
            'head' => $head,
            'properties' => $properties
        ]);
    }

    public function experienceCategory(Request $request)
    {
        $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar os imóveis do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
            route('web.experience'),
            asset('frontend/assets/images/share.png'));

        $filter = new FilterController();
        $filter->clearData();

        if($request->slug == 'cobertura'){
            $properties = Property::where('experience', 'Cobertura')->get();

            $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar uma Cobertura os imóveis do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
                route('web.experienceCategory', ['category' => 'cobertura']),
                asset('frontend/assets/images/share.png'));

        }elseif ($request->slug == 'alto-padrao'){
            $properties = Property::where('experience', 'Alto Padrão')->get();

            $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar os imóveis de Alto Padrão do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
                route('web.experienceCategory', ['category' => 'alto-padrao']),
                asset('frontend/assets/images/share.png'));

        }elseif ($request->slug == 'de-frente-para-o-mar'){
            $properties = Property::where('experience', 'De Frente para o Mar')->get();

            $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar os imóveis de Frente para o Mar do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
                route('web.experienceCategory', ['category' => 'de-frente-para-o-mar']),
                asset('frontend/assets/images/share.png'));

        }elseif ($request->slug == 'condominio-fechado'){
            $properties = Property::where('experience', 'Condomínio Fechado')->get();

            $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar os imóveis de Condomínio Fechado do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
                route('web.experienceCategory', ['category' => 'condominio-fechado']),
                asset('frontend/assets/images/share.png'));

        }elseif ($request->slug == 'compacto'){
            $properties = Property::where('experience', 'Compacto')->get();

            $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar os imóveis Compacto do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
                route('web.experienceCategory', ['category' => 'compacto']),
                asset('frontend/assets/images/share.png'));

        }elseif ($request->slug == 'lojas-e-salas'){
            $properties = Property::where('experience', 'Lojas e Salas')->get();

            $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar os imóveis Lojas e Salas do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
                route('web.experienceCategory', ['category' => 'lojas-e-salas']),
                asset('frontend/assets/images/share.png'));
        }else{
            $properties = Property::whereNotNull('experience')->get();

            $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar os imóveis do seu sonho na melhor e mais completa imobiliaria de Sorocaba',
                route('web.experienceCategory'),
                asset('frontend/assets/images/share.png'));
        }

        if(empty($head)){
            $head = $this->seo->render(env('APP_NAME') . ' - ProjetosDeploy', 'Vivendo a experiencia de encontrar os imóveis do seu sonho',
                route('web.experience'),
                asset('frontend/assets/images/share.png'));
        }

        return view('web.filter', [
            'head' => $head,
            'properties' => $properties
        ]);
    }
}
