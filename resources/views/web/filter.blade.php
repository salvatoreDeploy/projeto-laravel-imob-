@extends('web.master.master')

@section('content')
    <div class="main_filter bg-light py-5">
        <div class="container">
            <section class="row">
                <div class="col-12">
                    <h2 class="text-front icon-filter mb-5">Filtro</h2>
                </div>

                <div class="col-12 col-md-4">
                    <form action="{{ route('web.filter') }}" method="post" class="w-100 p-3 bg-white mb-5">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="search" class="mb-2 text-front">Comprar ou Alugar?</label>
                                <select class="selectpicker" id="search" name="filter_search" title="Escolha..."
                                        data-index="1" data-action="{{ route("component.main-filter.search") }}">
                                    <option value="buy" {{ session('sale') === true ? 'selected' : '' }}>Comprar</option>
                                    <option value="rent"{{ session('rent') === true ? 'selected' : '' }}>Alugar</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="category" class="mb-2 text-front">O que você quer?</label>
                                <select class="selectpicker" id="category" name="filter_category" title="Escolha..."
                                        data-index="2" data-action="{{ route("component.main-filter.category") }}">
                                    <option disabled>Selecione o filtro anterior</option>
                                    @if(!empty(session('category')))
                                        <option value="{{ session('category') }}" selected>{{ session('category') }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="type" class="mb-2 text-front">Qual o tipo do imóvel?</label>
                                <select class="selectpicker input-large" id="type" name="filter_type" title="Escolha..."
                                        multiple
                                        data-actions-box="true" data-index="3" data-action="{{ route("component.main-filter.type") }}">>
                                    <option disabled>Selecione o filtro anterior</option>
                                    @if(!empty(session('type')))
                                        <option value="{{ implode(',', session('type')) }}" selected>{{ implode(',', session('type')) }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="search_locale" class="mb-2 text-front">Onde você quer?</label>
                                <select class="selectpicker" name="filter_neighborhood" id="neighborhood"
                                        title="Escolha..." multiple
                                        data-actions-box="true" data-index="4"
                                        data-action="{{ route("component.main-filter.neighborhood") }}" multiple
                                        data-actions-box="true">
                                    <option disabled>Selecione o filtro anterior</option>
                                    @if(!empty(session('neighborhood')))
                                        <option value="{{ implode(',', session('neighborhood')) }}" selected>{{ implode(',', session('neighborhood')) }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Quartos</label>
                                <select class="selectpicker" name="filter_bedrooms" id="bedrooms" title="Escolha..."
                                        data-index="5" data-action="{{ route("component.main-filter.bedrooms") }}">
                                    <option disabled>Selecione o filtro anterior</option>
                                    @if(!empty(session('bedrooms')))
                                        <option value="{{ session('bedrooms') }}" selected>{{ session('bedrooms') }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Suítes</label>
                                <select class="selectpicker" name="filter_suites" id="suites" title="Escolha..."
                                        data-index="6" data-action="{{ route("component.main-filter.suites") }}">
                                    <option disabled>Selecione o filtro anterior</option>
                                    @if(!empty(session('suites')))
                                        <option value="{{ session('suites') }}" selected>{{ session('suites') }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Banheiros</label>
                                <select class="selectpicker" name="filter_bathrooms" id="bathrooms" title="Escolha..."
                                        data-index="7" data-action="{{ route("component.main-filter.bathrooms") }}">
                                    <option disabled>Selecione o filtro anterior</option>
                                    @if(!empty(session('bathrooms')))
                                        <option value="{{ session('bathrooms') }}" selected>{{ session('bathrooms') }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Garagem</label>
                                <select class="selectpicker" name="filter_garage" id="garage" title="Escolha..."
                                        data-index="8" data-action="{{ route('component.main-filter.garage') }}">
                                    <option disabled>Selecione o filtro anterior</option>
                                    @if(!empty(session('garage')))
                                        <option value="{{ session('garage') }}" selected>{{ session('garage') }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Preço Base</label>
                                <select class="selectpicker" name="filter_base" id="base" title="Escolha..."
                                        data-index="9" data-action="{{ route('component.main-filter.pricebase') }}">
                                    <option disabled>Selecione o filtro anterior</option>
                                    @if(!empty(session('price_base')))
                                        <option value="{{ session('price_base') }}" selected>{{ session('price_base') }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Preço Limite</label>
                                <select class="selectpicker" name="filter_limit" id="limit" title="Escolha..."
                                        data-index="10" data-action="{{ route('component.main-filter.pricelimit') }}">
                                    <option disabled>Selecione o filtro anterior</option>
                                    @if(!empty(session('price_limit')))
                                        <option value="{{ session('price_limit') }}" selected>{{ session('price_limit') }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-12 text-right mt-3 button_search">
                                <button class="btn btn-front icon-search">Pesquisar</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-12 col-md-8">

                    <section class="row main_properties">
                        @if($properties->count())
                            @foreach($properties as $property)
                                <div class="col-12 col-md-12 col-lg-6 mb-4">
                                    <article class="card main_properties_item">
                                        <div class="img-responsive-16by9">
                                            <a href="{{ route((session('sale') == true || !empty($type) && $type == 'sale' || ($property->rent == false) ? 'web.buyProperty' : 'web.rentProperty'), ['property' => $property->slug]) }}">
                                                <img src="{{$property->coverImage()}}" class="card-img-top"
                                                     alt="">
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <h2><a href="{{ route((session('sale') == true || !empty($type) && $type == 'sale' || ($property->rent == false) ? 'web.buyProperty' : 'web.rentProperty'), ['property' => $property->slug]) }}"
                                                   class="text-front">{{$property->title}}</a>
                                            </h2>
                                            <p class="main_properties_item_category">{{$property->category}}</p>
                                            <p class="main_properties_item_type">{{$property->type}} <i class="icon-location-arrow icon-notext"> {{$property->neighborhood}}</i>
                                            </p>
                                            @if(!empty($type) && $type == 'sale')
                                                <p class="main_properties_price text-front">R$ {{ $property->sale_price }}</p>
                                            @elseif(!empty($type) && $type == 'rent')
                                                <p class="main_properties_price text-front">R$ {{ $property->rent_price }} /mês</p>
                                            @else

                                            @if($property->sale == true && !empty($property->sale_price) && $property->rent == true && !empty($property->rent_price))
                                                    <p class="main_properties_price text-front">R$ {{ $property->sale_price }} ou R$ {{ $property->rent_price }} /mês</p>
                                                @elseif($property->sale == true && !empty($property->sale_price))
                                                    <p class="main_properties_price text-front">R$ {{ $property->sale_price }}</p>
                                                @elseif($property->rent == true && !empty($property->rent_price))
                                                    <p class="main_properties_price text-front">R$ {{ $property->rent_price }}</p>
                                                @else
                                                    <p class="main_properties_price text-front">Entre com nossa equipe Comercia!</p>
                                                @endif
                                            @endif

                                            <a href="{{ route((session('sale') == true || !empty($type) && $type == 'sale' || ($property->rent == false) ? 'web.buyProperty' : 'web.rentProperty'), ['property' => $property->slug]) }}"
                                               class="btn btn-front btn-block">Ver Imóvel</a>
                                        </div>
                                        <div class="card-footer d-flex">
                                            <div class="main_properties_features col-4 text-center">
                                                <img src="{{ asset("frontend/assets/images/icons/bed.png") }}" class="img-fluid" alt="">
                                                <p class="text-muted">{{ $property->bedrooms }}</p>
                                            </div>

                                            <div class="main_properties_features col-4 text-center">
                                                <img src="{{ asset('frontend/assets/images/icons/garage.png') }}" class="img-fluid" alt="">
                                                <p class="text-muted">{{ $property->garage  + $property->garabe_covered}}</p>
                                            </div>

                                            <div class="main_properties_features col-4 text-center">
                                                <img src="{{ asset("frontend/assets/images/icons/util-area.png") }}" class="img-fluid" alt="">
                                                <p class="text-muted">{{ $property->area_util }} m²</p>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 p-5 bg-white">
                                <h2 class="text-front icon-info text-center">Ooops, não encontramos nenhum imóvel para
                                    você comprar ou alugar!</h2>
                                <p class="text-center">Utiliza o filtro avançado para encontrar o lar dos seus
                                    sonhos...</p>
                            </div>
                        @endif
                    </section>
                </div>
            </section>
        </div>
    </div>
@endsection
