<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MercadoPagoService;

class CheckoutController extends Controller
{
    protected $mp;

    public function __construct(MercadoPagoService $mp)
    {
        $this->mp = $mp;
    }

    public function show()
    {
        $publicKey = config('services.mercadopago.public_key');
        return view('checkout', compact('publicKey'));
    }

    public function pixPayment(Request $req)
    {
        $data = $req->validate([
            'amount'      => 'required|numeric',
            'description' => 'required|string',
            'payer_email' => 'required|email',
        ]);

        $result = $this->mp->criarPagamentoPix($data);
        return response()->json($result);
    }

    public function cardPayment(Request $req)
    {
        $data = $req->validate([
            'amount'           => 'required|numeric',
            'description'      => 'required|string',
            'token'            => 'required|string',
            'installments'     => 'required|integer',
            'payment_method_id'=> 'required|string',
            'payer_email'      => 'required|email',
            'doc_type'         => 'required|string',
            'doc_number'       => 'required|string',
        ]);

        $status = $this->mp->criarPagamentoCartao($data);
        return response()->json(['status' => $status]);
    }
}
