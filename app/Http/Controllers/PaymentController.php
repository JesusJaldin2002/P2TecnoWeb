<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Obtener datos adicionales del servicio
        $service = Service::with('patient')->findOrFail($validated['service_id']);
        $client = $service->patient->person->name;
        $serviceType = $service->service_type;
        $servicePrice = (float) $service->price; // Asegúrate de convertir a número aquí

        // Renderizar la vista de pago
        return inertia('Payments/Pay', [
            'service_id' => $service->id,
            'amount' => $validated['amount'],
            'client' => $client,
            'service_type' => $serviceType,
            'service_price' => $servicePrice, // Convertido a número
        ]);
    }

    public function generateQR(Request $request)
    {
        $validated = $request->validate([
            'tcNombreUsuario' => 'required|string',
            'tnCiNit' => 'required|numeric',
            'tnTelefono' => 'required|numeric',
            'tnMontoClienteEmpresa' => 'required|numeric|min:0',
            'tcCorreo' => 'required|email',
            'service_id' => 'required|integer|exists:services,id',
        ]);

        try {
            $token = $this->getAccessToken();
            if (!$token) {
                Log::error('Token no obtenido en generateQR');
                return response()->json(['error' => 'No se pudo obtener el token'], 500);
            }

            $datosPago = [
                "tcCommerceID" => 'd029fa3a95e174a19934857f535eb9427d967218a36ea014b70ad704bc6c8d1c',
                "tcNroPago" => "INF513-SA-grupo04-" . $validated['service_id']."-". - rand(100000, 999999),
                "tcNombreUsuario" => $validated['tcNombreUsuario'],
                "tnCiNit" => $validated['tnCiNit'],
                "tnTelefono" => $validated['tnTelefono'],
                "tcCorreo" => $validated['tcCorreo'],
                "tcCodigoClienteEmpresa" => "9",
                "tnMontoClienteEmpresa" => $validated['tnMontoClienteEmpresa'],
                "tnMoneda" => 2,
                "tcUrlCallBack" => "https://miurl.ngrok.io/payments/callback",
                "tcUrlReturn" => "https://miurl.ngrok.io/payments/return",
                "taPedidoDetalle" => [
                    [
                        "Serial" => 1,
                        "Producto" => "Servicio ID " . $validated['service_id'],
                        "Cantidad" => 1,
                        "Precio" => $validated['tnMontoClienteEmpresa'],
                        "Descuento" => 0,
                        "Total" => $validated['tnMontoClienteEmpresa'],
                    ],
                ],
            ];

            Log::info('Datos enviados al servicio de pago:', $datosPago);

            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://serviciostigomoney.pagofacil.com.bo/api/servicio/pagoqr', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'json' => $datosPago,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if ($result['error'] === 0 && isset($result['values'])) {
                $values = explode(";", $result['values']);
                $numeroTransaccion = $values[0];
                $qrImage = "data:image/png;base64," . json_decode($values[1])->qrImage;

                return response()->json([
                    'numeroTransaccion' => $numeroTransaccion,
                    'qrImage' => $qrImage,
                ]);
            }

            Log::error('Error al generar QR', ['response' => $result]);
            return response()->json(['error' => $result['messageSistema'] ?? 'Error al generar el QR'], 500);
        } catch (\Exception $e) {
            Log::error('Excepción al generar QR', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }


    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'numeroTransaccion' => 'required|string',
        ]);

        try {
            $client = new Client();
            $response = $client->post('https://serviciostigomoney.pagofacil.com.bo/api/servicio/consultartransaccion', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'TransaccionDePago' => $validated['numeroTransaccion'],
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if ($result['error'] === 0 && $result['values']['EstadoTransaccion'] === 1) {
                // Registrar el pago en la base de datos
                Payment::create([
                    'service_id' => $request->service_id,
                    'date' => $result['values']['FechaPago'],
                    'payment_time' => $result['values']['HoraPago'],
                    'tigo_transaction_id' => $validated['numeroTransaccion'],
                    'total' => Service::find($request->service_id)->price,
                    'payment_type' => 'QR',
                ]);

                return response()->json(['message' => 'Pago verificado y registrado correctamente.']);
            }

            return response()->json(['error' => 'El pago no ha sido completado.'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getAccessToken()
    {
        $client = new Client();

        try {
            $response = $client->post('https://serviciostigomoney.pagofacil.com.bo/api/servicio/login', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'TokenService' => '51247fae280c20410824977b0781453df59fad5b23bf2a0d14e884482f91e09078dbe5966e0b970ba696ec4caf9aa5661802935f86717c481f1670e63f35d5041c31d7cc6124be82afedc4fe926b806755efe678917468e31593a5f427c79cdf016b686fca0cb58eb145cf524f62088b57c6987b3bb3f30c2082b640d7c52907',
                    'TokenSecret' => '9E7BC239DDC04F83B49FFDA5',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents(), true);

            if ($statusCode !== 200 || !isset($result['values'])) {
                Log::error('Error al obtener token: ' . json_encode($result));
                return null;
            }

            return $result['values'];
        } catch (\Exception $e) {
            Log::error('Error en la solicitud de token: ' . $e->getMessage());
            return null;
        }
    }

    public function testToken()
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return response()->json(['error' => 'No se pudo obtener el token de autenticación'], 500);
        }

        return response()->json(['token' => $token]);
    }
}
