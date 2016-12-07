<?php
namespace Gentor\Speedy\Http\Controllers;

use Input;
use Gentor\Speedy\Components\Client;
use Gentor\Speedy\Components\Param\Language;
use Gentor\Speedy\Components\Result\OfficeEx;
use Gentor\Speedy\Components\Result\Site;
use Gentor\Speedy\Speedy;

class OfficeController extends Controller
{

    const MIN_AUTOCOMPLETE_LENGTH = 3;

    public function index()
    {
    }

    public function show($id)
    {
        $client = Client::createFromArray(Input::all());

        if (!$client) {
            return ['results' => [], 'more' => false];
        }

        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user($client);

        $settlement = Input::get('settlement');

        if ((string)(int)$settlement != $settlement) {
            $site = Site::find($settlement);

            if ($site instanceof Site) {
                $settlement = $site->id;
            }
        }

        $settlement = (int)$settlement;

        $name = htmlentities(Input::get('query'), ENT_QUOTES, 'UTF-8', false);

        if (0 >= $settlement) {
            return ['results' => [], 'more' => false];
        }

        $offices = $speedy->listOfficesEx($settlement, Language::create());

        if (!$offices || !isset($offices->return)) {
            return ['results' => [], 'more' => false];
        }

        $offices = OfficeEx::createFromSoapResponse($offices->return);

        foreach ($offices as $office) {
            if (!$office instanceof OfficeEx || $office->id != $id) {
                continue;
            }

            $entry = ['id' => $office->id, 'name' => $office->name];
            $entry['ref'] = $office->name;

            return $entry;
        }

        return ['results' => [], 'more' => false];
    }

    public function autocomplete()
    {
        $client = Client::createFromArray(Input::all());

        if (!$client) {
            return ['results' => [], 'more' => false];
        }

        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user($client);

        $settlement = Input::get('settlement');

        if ((string)(int)$settlement != $settlement) {
            $site = Site::find($settlement);

            if ($site instanceof Site) {
                $settlement = $site->id;
            }
        }

        $settlement = (int)$settlement;

        $name = htmlentities(Input::get('query'), ENT_QUOTES, 'UTF-8', false);

        if (0 >= $settlement) {
            return ['results' => [], 'more' => false];
        }

        $offices = $speedy->listOfficesEx($settlement, Language::create(), $name);

        if (!$offices || !isset($offices->return)) {
            return ['results' => [], 'more' => false];
        }

        $result = [];
        $offices = OfficeEx::createFromSoapResponse($offices->return);

        foreach ($offices as $office) {
            if (!$office instanceof OfficeEx) {
                continue;
            }

            $entry = ['id' => $office->id, 'name' => $office->name];
            $entry['ref'] = $office->name;

            $result[] = (object)$entry;
        }

        return [
            'results' => $result,
            'more' => false,
        ];
    }

}