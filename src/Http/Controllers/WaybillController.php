<?php
namespace Gentor\Speedy\Http\Controllers;

use Gentor\Speedy\Components\Client;
use Gentor\Speedy\Components\Param\Calculation;
use Gentor\Speedy\Components\Param\Pdf;
use Gentor\Speedy\Components\Param\Picking;
use Gentor\Speedy\Components\Result\BOL;
use Gentor\Speedy\Components\Result\Calculation as Result;
use Gentor\Speedy\Exceptions\SpeedyException;
use Gentor\Speedy\Http\Requests\CalculateRequest;
use Gentor\Speedy\Http\Requests\PdfRequest;
use Gentor\Speedy\Http\Requests\WaybillRequest;
use Gentor\Speedy\Speedy;

class WaybillController extends Controller
{

    public function issue(WaybillRequest $request)
    {
        $data = $request->all();
        $client = Client::createFromArray($data);

        if (!$client) {
            return null;
        }

        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user($client);

        $picking = Picking::createFromRequest($data);
        if(isset($data['receiver']['office']) && $data['receiver']['office']) {
            $picking->officeToBeCalledId = $data['receiver']['office'];
            unset($picking->receiver->address);
        }
        $waybill = $speedy->createBillOfLading($picking);

        if (!isset($waybill->return) || !$waybill->return) {
            throw new SpeedyException('Invalid bill of lading (BOL/waybill) detected.');
        }

        $waybill = BOL::createFromSoapResponse($waybill->return);

        return response()->json($waybill);

    }

    public function calculate(CalculateRequest $request)
    {
        $data = $request->all();
        $client = Client::createFromArray($data);

        if (!$client) {
            return null;
        }

        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user($client);

        $calculation = Calculation::createFromRequest($data);
        $calculation = $speedy->calculate($calculation);

        if (!isset($calculation->return) || !$calculation->return) {
            throw new SpeedyException('Invalid calculation detected.');
        }

        $result = Result::createFromSoapResponse($calculation->return);

        return response()->json($result);
    }

    public function pdf(PdfRequest $request)
    {
        $data = $request->all();
        $client = Client::createFromArray($data);

        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user($client);

        $pdf = Pdf::createFromRequest($data);
        $pdf = $speedy->createPDF($pdf);

        if (!isset($pdf->return) || !$pdf->return) {
            throw new SpeedyException('Invalid PDF detected.');
        }

        return response()->stream(function () use ($pdf) {
            $fp = fopen('php://output', 'w');
            fputs($fp, $pdf->return);
            fclose($fp);
        }, 200, ['Content-Type' => 'application/pdf']);
    }

}