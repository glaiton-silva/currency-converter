<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Cotação</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }
        .header h1 {
            color: #333333;
        }
        .content {
            padding: 20px 0;
        }
        .content p {
            line-height: 1.6;
            color: #666666;
        }
        .content p span {
            font-weight: bold;
            color: #333333;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detalhes da Cotação</h1>
        </div>
        <div class="content">
            <p><span>Moeda de origem:</span> BRL</p>
            <p><span>Moeda de destino:</span> {{ $quotation->currency }}</p>
            <p><span>Valor para conversão:</span> R$ {{ number_format($quotation->amount, 2, ',', '.') }}</p>
            <p><span>Forma de pagamento:</span> {{ $quotation->payment_method == 'boleto' ? 'Boleto' : 'Cartão de Crédito' }}</p>
            <p><span>Valor {{ $quotation->currency }} usado para conversão:</span> $ {{ number_format($quotation->conversion_rate, 2, ',', '.') }}</p>
            <p><span>Valor comprado {{ $quotation->currency }}:</span> $ {{ number_format($quotation->converted_amount, 2, ',', '.') }}</p>
            <p><span>Taxa de pagamento:</span> R$ {{ number_format($quotation->payment_fee, 2, ',', '.') }}</p>
            <p><span>Taxa de conversão:</span> R$ {{ number_format($quotation->conversion_fee, 2, ',', '.') }}</p>
            <p><span>Valor utilizado para conversão descontando as taxas:</span> R$ {{ number_format($quotation->net_amount, 2, ',', '.') }}</p>
        </div>
        <div class="footer">
            <p>Obrigado por usar nosso serviço de conversão de moedas!</p>
        </div>
    </div>
</body>
</html>
