@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <h5>{{ __('Welcome to the Currency Converter Dashboard!') }}</h5>
                    <p>{{ __('Here you can manage your currency conversions, view your conversion history, and update conversion fees.') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">{{ __('Últimas Conversões') }}</div>
                        <div class="card-body">
                            <p>{{ __('Confira suas últimas conversões de moeda abaixo.') }}</p>
                            @if($latestConversions->isEmpty())
                            <div class="alert alert-info" role="alert">
                                Nenhuma conversão encontrada.
                            </div>
                            @else
                            <ul class="list-group">
                                @foreach ($latestConversions as $conversion)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $conversion->created_at->format('d/m/Y H:i:s') }}</strong><br>
                                        <span>Valor para conversão: R$ {{ number_format($conversion->amount, 2, ',', '.') }}</span><br>
                                        <span>Forma de pagamento: {{ $conversion->payment_method == 'boleto' ? 'Boleto' : 'Cartão de Crédito' }}</span><br>
                                        <span>Valor {{ $conversion->currency }} usado para conversão: $ {{ number_format($conversion->conversion_rate, 2, ',', '.') }}</span><br>
                                        <span>Valor comprado {{ $conversion->currency }}: $ {{ number_format($conversion->converted_amount, 2, ',', '.') }}</span><br>
                                        <span>Taxa de pagamento: R$ {{ number_format($conversion->payment_fee, 2, ',', '.') }}</span><br>
                                        <span>Taxa de conversão: R$ {{ number_format($conversion->conversion_fee, 2, ',', '.') }}</span><br>
                                        <span>Valor utilizado para conversão descontando as taxas: R$ {{ number_format($conversion->net_amount, 2, ',', '.') }}</span>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $conversion->currency }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            <a href="{{ route('quotations.history') }}" class="btn btn-primary mt-3">{{ __('Ver Histórico Completo') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">{{ __('Conversion Information') }}</div>
                        <div class="card-body">
                            <p>{{ __('Use the currency converter to easily convert BRL to various currencies. Here are some features:') }}</p>
                            <ul>
                                <li>{{ __('Real-time exchange rates') }}</li>
                                <li>{{ __('Detailed conversion fees') }}</li>
                                <li>{{ __('Email notifications for conversions') }}</li>
                                <li>{{ __('Historical data tracking') }}</li>
                            </ul>
                            <a href="{{ route('quotations.index') }}" class="btn btn-primary mt-3">{{ __('Start a New Conversion') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection