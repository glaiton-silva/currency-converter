@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Coluna da Esquerda: Formulário de Conversão -->
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">{{ __('Converter Moeda') }}</div>
                <div class="card-body">
                    <form id="convert-form" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="currency" class="form-label">Moeda de Destino</label>
                            <select class="form-control" name="currency" id="currency">
                                <!-- As opções serão preenchidas dinamicamente -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Valor para Conversão (BRL)</label>
                            <input type="number" class="form-control" name="amount" id="amount" min="1000" max="100000" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Forma de Pagamento</label>
                            <select class="form-control" name="payment_method" id="payment_method">
                                <option value="boleto">Boleto</option>
                                <option value="credit_card">Cartão de Crédito</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" id="convert-button">Converter</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Coluna da Direita: Resultados -->
        <div class="col-md-6">
            <div id="result" class="d-none mt-4">
                <div class="card">
                    <div class="card-header">{{ __('Resultado da Conversão') }}</div>
                    <div class="card-body">
                        <p class="card-text" id="text_source_currency"></p>
                        <p class="card-text" id="text_target_currency"></p>
                        <p class="card-text" id="text_amount"></p>
                        <p class="card-text" id="text_payment_method"></p>
                        <p class="card-text" id="text_rate_used"></p>
                        <p class="card-text" id="text_converted_amount"></p>
                        <p class="card-text" id="text_payment_fee"></p>
                        <p class="card-text" id="text_conversion_fee"></p>
                        <p class="card-text" id="text_net_amount"></p>
                        <form id="email-form" method="POST">
                            @csrf
                            <input type="hidden" name="quotation_id" id="quotation_id">
                            <button type="submit" class="btn btn-primary">Enviar por e-mail</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
            let allowedCurrencies = [];

            // Primeiro fetch para pegar as combinações permitidas
            fetch('https://economia.awesomeapi.com.br/json/available')
                .then(response => response.json())
                .then(data => {
                    for (let pair in data) {
                        if (pair.startsWith('BRL-')) {
                            let currency = pair.replace('BRL-', '');
                            allowedCurrencies.push(currency);
                        }
                    }

                    // Segundo fetch para pegar as traduções
                    return fetch('https://economia.awesomeapi.com.br/json/available/uniq');
                })
                .then(response => response.json())
                .then(data => {
                    let currencySelect = document.getElementById('currency');
                    allowedCurrencies.forEach(currency => {
                        if (data[currency]) {
                            let option = document.createElement('option');
                            option.value = currency;
                            option.text = `${currency} - ${data[currency]}`;
                            currencySelect.appendChild(option);
                        }
                    });
                })
                .catch(error => {
                    console.error('Erro ao buscar as combinações de moedas:', error);
                });
        });

    document.getElementById('convert-form').addEventListener('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let convertButton = document.getElementById('convert-button');
        
        convertButton.disabled = true;
        convertButton.innerText = 'Convertendo...';

        fetch('/convert', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            convertButton.disabled = false;
            convertButton.innerText = 'Converter';

            if (data.success) {
                document.getElementById('text_source_currency').innerText = `Moeda de origem: BRL`;
                document.getElementById('text_target_currency').innerText = `Moeda de destino: ${data.currency}`;
                document.getElementById('text_amount').innerText = `Valor para conversão: R$ ${data.original_amount}`;
                document.getElementById('text_payment_method').innerText = `Forma de pagamento: ${data.payment_method}`;
                document.getElementById('text_rate_used').innerText = `Valor ${data.currency} usado para conversão: $ ${data.rate}`;
                document.getElementById('text_converted_amount').innerText = `Valor comprado ${data.currency}: $ ${data.converted_amount}`;
                document.getElementById('text_payment_fee').innerText = `Taxa de pagamento: R$ ${data.fees.paymentFee}`;
                document.getElementById('text_conversion_fee').innerText = `Taxa de conversão: R$ ${data.fees.conversionFee}`;
                document.getElementById('text_net_amount').innerText = `Valor utilizado para conversão descontando as taxas: R$ ${data.net_amount}`;
                document.getElementById('quotation_id').value = data.quotation_id;
                document.getElementById('result').classList.remove('d-none');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: data.message,
                });
                document.getElementById('result').classList.add('d-none');
            }
        })
        .catch(error => {
            convertButton.disabled = false;
            convertButton.innerText = 'Converter';
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Algo deu errado. Por favor, tente novamente.',
            });
            document.getElementById('result').classList.add('d-none');
        });
    });

    document.getElementById('email-form').addEventListener('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let emailButton = this.querySelector('button[type="submit"]');
        
        emailButton.disabled = true;
        emailButton.innerText = 'Enviando...';

        fetch('/quotations/email', {
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

            Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? 'Sucesso' : 'Erro',
                text: data.message,
            });
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
</script>
@endsection

