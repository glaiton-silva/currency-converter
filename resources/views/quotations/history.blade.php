@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Histórico de Cotações</h1>
    <div class="row">
        @if($quotations->isEmpty())
            <div class="col-12 mt-4">
                <div class="alert alert-info" role="alert">
                    Nenhuma cotação encontrada.
                </div>
            </div>
        @else
            @foreach ($quotations as $quotation)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $quotation->created_at->format('d/m/Y H:i:s') }}</h5>
                            <p class="card-text">Moeda de origem: BRL</p>
                            <p class="card-text">Moeda de destino: {{ $quotation->currency }}</p>
                            <p class="card-text">Valor para conversão: R$ {{ number_format($quotation->amount, 2, ',', '.') }}</p>
                            <p class="card-text">Forma de pagamento: {{ $quotation->payment_method == 'boleto' ? 'Boleto' : 'Cartão de Crédito' }}</p>
                            <p class="card-text">Valor {{ $quotation->currency }} usado para conversão: $ {{ number_format($quotation->conversion_rate, 2, ',', '.') }}</p>
                            <p class="card-text">Valor comprado {{ $quotation->currency }}: $ {{ number_format($quotation->converted_amount, 2, ',', '.') }}</p>
                            <p class="card-text">Taxa de pagamento: R$ {{ number_format($quotation->payment_fee, 2, ',', '.') }}</p>
                            <p class="card-text">Taxa de conversão: R$ {{ number_format($quotation->conversion_fee, 2, ',', '.') }}</p>
                            <p class="card-text">Valor utilizado para conversão descontando as taxas: R$ {{ number_format($quotation->net_amount, 2, ',', '.') }}</p>
                            <form class="email-form" method="POST">
                                @csrf
                                <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">
                                <button type="submit" class="btn btn-primary">Enviar por e-mail</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.email-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let emailButton = this.querySelector('button[type="submit"]');

            emailButton.disabled = true;
            emailButton.innerText = 'Enviando...';

            fetch('{{ route('quotations.sendEmail') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                .then(response => response.json())
                .then(data => {
                    emailButton.disabled = false;
                    emailButton.innerText = 'Enviar por e-mail';

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: data.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: data.message,
                        });
                    }
                })
                .catch(error => {
                    emailButton.disabled = false;
                    emailButton.innerText = 'Enviar por e-mail';

                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Algo deu errado. Por favor, tente novamente.',
                    });
                });
        });
    });
</script>
@endsection