@extends('web.master.master')

@section('content')
    <div class="main_filter bg-light py-5">
        <div class="container">
            <section class="row">
                <div class="col-12">
                    <h2 class="text-front icon-filter mb-5">Filtro</h2>
                </div>

                <div class="col-12 col-md-4">
                    <form action="" method="post" class="w-100 p-3 bg-white mb-5">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="search" class="mb-2 text-front">Comprar ou Alugar?</label>
                                <select class="selectpicker" id="search" name="filter_search" title="Escolha..."
                                        data-index="1" data-action="">
                                    <option value="buy">Comprar</option>
                                    <option value="rent">Alugar</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="category" class="mb-2 text-front">O que você quer?</label>
                                <select class="selectpicker" id="category" name="filter_category" title="Escolha..."
                                        data-index="2" data-action="">
                                    <option disabled>Selecione o filtro anterior</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="type" class="mb-2 text-front">Qual o tipo do imóvel?</label>
                                <select class="selectpicker input-large" id="type" name="filter_type" title="Escolha..."
                                        multiple
                                        data-actions-box="true" data-index="3" data-action="">
                                    <option disabled>Selecione o filtro anterior</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="search_locale" class="mb-2 text-front">Onde você quer?</label>
                                <select class="selectpicker" name="filter_neighborhood" id="neighborhood"
                                        title="Escolha..." multiple
                                        data-actions-box="true" data-index="4" data-action="">
                                    <option disabled>Selecione o filtro anterior</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Quartos</label>
                                <select class="selectpicker" name="filter_bedrooms" id="bedrooms" title="Escolha..."
                                        data-index="5" data-action="">
                                    <option disabled>Selecione o filtro anterior</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Suítes</label>
                                <select class="selectpicker" name="filter_suites" id="suites" title="Escolha..."
                                        data-index="6" data-action="">
                                    <option disabled>Selecione o filtro anterior</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Banheiros</label>
                                <select class="selectpicker" name="filter_bathrooms" id="bathrooms" title="Escolha..."
                                        data-index="7" data-action="">
                                    <option disabled>Selecione o filtro anterior</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Garagem</label>
                                <select class="selectpicker" name="filter_garage" id="garage" title="Escolha..."
                                        data-index="8" data-action="">
                                    <option disabled>Selecione o filtro anterior</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Preço Base</label>
                                <select class="selectpicker" name="filter_base" id="base" title="Escolha..."
                                        data-index="9" data-action="">
                                    <option disabled>Selecione o filtro anterior</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="bedrooms" class="mb-2 text-front">Preço Limite</label>
                                <select class="selectpicker" name="filter_limit" id="limit" title="Escolha..."
                                        data-index="10" data-action="">
                                    <option disabled>Selecione o filtro anterior</option>
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
                                            <a href="{{ route((session('sale') == true ? 'web.buyProperty' : 'web.rentProperty'), ['property' => $property->slug]) }}">
                                                <img src="{{$property->coverImage()}}" class="card-img-top"
                                                     alt="">
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <h2><a href="{{ route((session('sale') == true ? 'web.buyProperty' : 'web.rentProperty'), ['property' => $property->slug]) }}"
                                                   class="text-front">{{$property->title}}</a>
                                            </h2>
                                            <p class="main_properties_item_category">{{$property->category}}</p>
                                            <p class="main_properties_item_type">{{$property->type}} <i class="icon-location-arrow icon-notext"> {{$property->neighborhood}}</i>
                                            </p>
                                            <p class="main_properties_price text-front">R$ {{ session('sale') == true ? $property->sale_price : $property->rent_price }}</p>
                                            <a href="{{ route((session('sale') == true ? 'web.buyProperty' : 'web.rentProperty'), ['property' => $property->slug]) }}"
                                               class="btn btn-front btn-block">Ver Imóvel</a>
                                        </div>
                                        <div class="card-footer d-flex">
                                            <div class="main_properties_features col-4 text-center">
                                                <img src="frontend/assets/images/icons/bed.png" class="img-fluid" alt="">
                                                <p class="text-muted">{{ $property->bedrooms }}</p>
                                            </div>

                                            <div class="main_properties_features col-4 text-center">
                                                <img src="frontend/assets/images/icons/garage.png" class="img-fluid" alt="">
                                                <p class="text-muted">{{ $property->garage  + $property->garabe_covered}}</p>
                                            </div>

                                            <div class="main_properties_features col-4 text-center">
                                                <img src="frontend/assets/images/icons/util-area.png" class="img-fluid" alt="">
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
