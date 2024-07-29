<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConversionFee;
use Exception;

class ConversionFeeController extends Controller
{
    /**
     * Exibe a página para editar as taxas de conversão.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $fee = ConversionFee::first();
        return view('conversion_fees.edit', compact('fee'));
    }

    /**
     * Atualiza as taxas de conversão no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Valida os dados recebidos
        $request->validate([
            'payment_fee_boleto' => 'required|numeric',
            'payment_fee_credit_card' => 'required|numeric',
            'conversion_fee_below_3000' => 'required|numeric',
            'conversion_fee_above_3000' => 'required|numeric',
        ]);

        try {
            // Atualiza as taxas no banco de dados
            $fee = ConversionFee::first();
            $fee->update($request->all());

            return redirect()->route('conversion_fees.edit')->with('success', 'Taxas atualizadas com sucesso!');
        } catch (Exception $e) {
            // Captura exceção e redireciona com mensagem de erro
            return redirect()->route('conversion_fees.edit')->with('error', 'Algo deu errado. Por favor, tente novamente.');
        }
    }
}

