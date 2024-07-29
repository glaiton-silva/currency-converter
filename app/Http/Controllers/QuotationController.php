<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuotationMail;
use App\Models\ConversionFee;

class QuotationController extends Controller
{
    /**
     * Exibe a página principal do conversor de moedas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('quotations.index');
    }

    /**
     * Converte a moeda e calcula o valor final com as taxas aplicadas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function convert(Request $request)
    {
        // Valida os dados recebidos conforme as regras de negócio
        $request->validate([
            'currency' => 'required',
            'amount' => 'required|numeric|min:1000|max:100000',
            'payment_method' => 'required|in:boleto,credit_card'
        ]);

        try {
           
            // Obtém a taxa de conversão da API
            $conversionRate = $this->getConversionRate($request->currency);
                
            // Calcula o valor convertido aplicando as taxas
            $convertedAmount = $this->calculateConversion($request->amount, $request->payment_method, $conversionRate);

            // Calcula as taxas
            $fees = $this->calculateFees($request->amount, $request->payment_method);
            $formattedFees = [
                'paymentFee' => number_format($fees['paymentFee'], 2, ',', '.'),
                'conversionFee' => number_format($fees['conversionFee'], 2, ',', '.')
            ];
            $netAmount = $request->amount - $fees['paymentFee'] - $fees['conversionFee'];

            // Calcule a taxa inversa e salve como conversion_rate
            $inverseRate = 1 / $conversionRate;

            // Salva a cotação no banco de dados
            $quotation = new Quotation();
            $quotation->user_id = Auth::id();
            $quotation->currency = $request->currency;
            $quotation->amount = $request->amount;
            $quotation->payment_method = $request->payment_method;
            $quotation->converted_amount = $convertedAmount;
            $quotation->payment_fee = $fees['paymentFee'];
            $quotation->conversion_fee = $fees['conversionFee'];
            $quotation->net_amount = $netAmount;
            $quotation->conversion_rate = $inverseRate;
            $quotation->save();

            return response()->json([
                'success' => true,
                'original_amount' => number_format($request->amount, 2, ',', '.'),
                'converted_amount' => number_format($convertedAmount, 2, ',', '.'),
                'rate' => number_format($inverseRate, 2, ',', '.'),
                'fees' => $formattedFees,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method === 'boleto' ? 'Boleto' : 'Cartão de Crédito',
                'net_amount' => number_format($netAmount, 2, ',', '.'),
                'quotation_id' => $quotation->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Algo deu errado. Por favor, tente novamente.',
            ]);
        }
    }

    /**
     * Obtém a taxa de conversão de BRL para a moeda de destino.
     *
     * @param  string  $currency
     * @return float
     */
    private function getConversionRate($currency)
    {
        $response = file_get_contents("https://economia.awesomeapi.com.br/json/last/BRL-{$currency}");
        $data = json_decode($response, true);

        // Verifica se a moeda de destino está presente na resposta
        if (!isset($data["BRL{$currency}"])) {
            throw new \Exception('Moeda de destino inválida');
        }

        return $data["BRL{$currency}"]["bid"];
    }

    /**
     * Calcula o valor convertido aplicando as taxas de pagamento e conversão.
     *
     * @param  float  $amount
     * @param  string  $paymentMethod
     * @param  float  $rate
     * @return float
     */
    private function calculateConversion($amount, $paymentMethod, $rate)
    {
        $fees = $this->calculateFees($amount, $paymentMethod);
        $amountAfterFees = $amount - $fees['paymentFee'] - $fees['conversionFee'];
        return $amountAfterFees * $rate;
    }

    /**
     * Calcula as taxas de pagamento e conversão com base no valor e no método de pagamento.
     *
     * @param  float  $amount
     * @param  string  $paymentMethod
     * @return array
     */
    private function calculateFees($amount, $paymentMethod)
    {
        // Obtém as taxas do banco de dados
        $fee = ConversionFee::first();

        // Taxas de pagamento
        $paymentFee = $paymentMethod == 'boleto' ? $fee->payment_fee_boleto * $amount : $fee->payment_fee_credit_card * $amount;
        
        // Taxas de conversão
        $conversionFee = $amount < 3000 ? $fee->conversion_fee_below_3000 * $amount : $fee->conversion_fee_above_3000 * $amount;

        return [
            'paymentFee' => $paymentFee,
            'conversionFee' => $conversionFee,
        ];
    }

    /**
     * Envia a cotação por email para o usuário autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request)
    {
        try {
            $quotation = Quotation::find($request->quotation_id);
            Mail::to(Auth::user()->email)->send(new QuotationMail($quotation));
            return response()->json(['success' => true, 'message' => 'Email enviado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Algo deu errado. Por favor, tente novamente.']);
        }
    }

    /**
     * Exibe o histórico de cotações do usuário autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        $quotations = Quotation::where('user_id', Auth::id())->orderby('created_at', 'desc')->get();
        return view('quotations.history', compact('quotations'));
    }
}

