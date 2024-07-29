@extends('layouts.app')

@section('content')
    <h1>Taxas de Conversão</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ route('conversion_fees.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="payment_fee_boleto" class="form-label">Taxa de Pagamento (Boleto)</label>
            <input type="number" step="0.0001" class="form-control" name="payment_fee_boleto" id="payment_fee_boleto" value="{{ $fee->payment_fee_boleto }}" required>
        </div>
        <div class="mb-3">
            <label for="payment_fee_credit_card" class="form-label">Taxa de Pagamento (Cartão de Crédito)</label>
            <input type="number" step="0.0001" class="form-control" name="payment_fee_credit_card" id="payment_fee_credit_card" value="{{ $fee->payment_fee_credit_card }}" required>
        </div>
        <div class="mb-3">
            <label for="conversion_fee_below_3000" class="form-label">Taxa de Conversão (Abaixo de 3000)</label>
            <input type="number" step="0.0001" class="form-control" name="conversion_fee_below_3000" id="conversion_fee_below_3000" value="{{ $fee->conversion_fee_below_3000 }}" required>
        </div>
        <div class="mb-3">
            <label for="conversion_fee_above_3000" class="form-label">Taxa de Conversão (Acima de 3000)</label>
            <input type="number" step="0.0001" class="form-control" name="conversion_fee_above_3000" id="conversion_fee_above_3000" value="{{ $fee->conversion_fee_above_3000 }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
@endsection
