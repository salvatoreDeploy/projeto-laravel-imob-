@extends('admin.master.master')

@section('content')
    <div style="flex-basis: 100%;">
        <section class="dash_content_app">
            <header class="dash_content_app_header">
                <h2 class="icon-tachometer">Dashboard</h2>
            </header>

            <div class="dash_content_app_box">
                <section class="app_dash_home_stats">
                    <article class="control radius">
                        <h4 class="icon-users">Clientes</h4>
                        <p><b>Locadores:</b> {{ $lessors }}</p>
                        <p><b>Locatários:</b> {{ $lessees }}</p>
                        <p><b>Time:</b> {{ $team }}</p>
                    </article>

                    <article class="blog radius">
                        <h4 class="icon-home">Imóveis</h4>
                        <p><b>Disponíveis:</b> {{ $propertyAvailable }}</p>
                        <p><b>Locados:</b> {{ $propertyUnavailable }}</p>
                        <p><b>Total:</b> {{ $propertiesTotal }}</p>
                    </article>

                    <article class="users radius">
                        <h4 class="icon-file-text">Contratos</h4>
                        <p><b>Pendentes:</b> {{ $contractPending }}</p>
                        <p><b>Ativos:</b> {{ $contractActive }}</p>
                        <p><b>Cancelados:</b> {{ $contractCanceled }}</p>
                        <p><b>Total:</b> {{ $contractTotal }}</p>
                    </article>
                </section>
            </div>
        </section>

        <section class="dash_content_app" style="margin-top: 40px;">
            <header class="dash_content_app_header">
                <h2 class="icon-tachometer">Últimos Contratos Cadastrados</h2>
            </header>

            <div class="dash_content_app_box">
                <div class="dash_content_app_box_stage">
                    <table id="dataTable" class="nowrap hover stripe" width="100" style="width: 100% !important;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Locador</th>
                            <th>Locatário</th>
                            <th>Negócio</th>
                            <th>Início</th>
                            <th>Vigência</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($contracts))
                            @foreach($contracts as $contract)
                                <tr>
                                    <td><a href="{{ route('admin.contracts.edit', ['$contract' => $contract->id]) }}" class="text-orange">{{ $contract->id }}</a></td>
                                    <td><a href="{{ route('admin.users.edit', ['$contract' => $contract->contractOwner->id]) }}" class="text-orange">{{ $contract->contractOwner->name}}</a></td>
                                    <td><a href="{{ route('admin.users.edit', ['user' => $contract->contractAcquirier->id]) }}" class="text-orange">{{ $contract->contractAcquirier->name}}</a></td>
                                    <td>{{ $contract->sale == true ? 'Venda' : 'Locação' }}</td>
                                    <td>{{ $contract->start_at }}</td>
                                    <td>{{ ($contract->deadline ?? '') }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="dash_content_app" style="margin-top: 40px;">
            <header class="dash_content_app_header">
                <h2 class="icon-tachometer">Últimos Imóveis Cadastrados</h2>
            </header>

            <div class="dash_content_app_box">
                <div class="dash_content_app_box_stage">
                    <div class="realty_list">
                        <div class="realty_list_item mt-1 mb-1">
                            @if(!empty($properties))
                                @foreach($properties as $property)
                                    <div class="realty_list_item mb-3">
                                        <div class="realty_list_item_actions_stats">
                                            <img
                                                src="{{ $property->coverImage() }}"
                                                alt="">
                                            <ul>
                                                @if($property->sale == true && !empty($property->sale_price))
                                                    <li>Venda: R$ {{ $property->sale_price }}</li>
                                                @endif

                                                @if($property->rent == true && !empty($property->rent_price))
                                                    <li>Aluguel: {{ $property->rent_price }}</li>
                                                @endif
                                            </ul>
                                        </div>

                                        <div class="realty_list_item_content">
                                            <h4># {{ $property->id }} {{ $property->category }}
                                                - {{ $property->type }}
                                            </h4>

                                            <div class="realty_list_item_card">
                                                <div class="realty_list_item_card_image">
                                                    <span class="icon-realty-location"></span>
                                                </div>
                                                <div class="realty_list_item_card_content">
                                                    <span class="realty_list_item_description_title">Bairro: </span>
                                                    <span
                                                        class="realty_list_item_description_content">{{ $property->neighborhood }}</span>
                                                </div>
                                            </div>

                                            <div class="realty_list_item_card">
                                                <div class="realty_list_item_card_image">
                                                    <span class="icon-realty-util-area"></span>
                                                </div>
                                                <div class="realty_list_item_card_content">
                                                    <span class="realty_list_item_description_title">Área Útil:</span>
                                                    <span class="realty_list_item_description_content">{{ $property->area_util }} m&sup2;</span>
                                                </div>
                                            </div>

                                            <div class="realty_list_item_card">
                                                <div class="realty_list_item_card_image">
                                                    <span class="icon-realty-bed"></span>
                                                </div>
                                                <div class="realty_list_item_card_content">
                                                    <span class="realty_list_item_description_title">Domitórios:</span>
                                                    <span class="realty_list_item_description_content">{{  $property->bedrooms + $property->suites }} Quartos<br><span>Sendo {{ $property->suites }} suites</span></span>
                                                </div>
                                            </div>

                                            <div class="realty_list_item_card">
                                                <div class="realty_list_item_card_image">
                                                    <span class="icon-realty-garage"></span>
                                                </div>
                                                <div class="realty_list_item_card_content">
                                                    <span class="realty_list_item_description_title">Garagem:</span>
                                                    <span class="realty_list_item_description_content">{{  $property->garage + $property->garage_covered}} Vagas<br><span>Sendo {{ $property->garage_covered }} cobertas</span></span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="realty_list_item_actions">
                                            <ul>
                                                <li class="icon-eye">1234 Visualizações</li>
                                            </ul>
                                            <div>
                                                <a href="" class="btn btn-blue icon-eye">Visualizar
                                                    Imóvel</a>
                                                <a href="{{ route('admin.properties.edit', ['property' => $property->id]) }}"
                                                   class="btn btn-green icon-pencil-square-o">Editar
                                                    Imóvel</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="no-content">Não foram encontrados registros!</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

