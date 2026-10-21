<?php

namespace App\Http\Controllers\Store\Location;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use App\Http\Controllers\Controller;

/**
 * Class QrCodeController
 * @package App\Http\Controllers\Store\Location
 */
class QrCodeController extends Controller
{
    /**
     * @desc A QR Code Store Locations Can Use In Their Check-In Line
     *
     * @param string $uuid
     * @return mixed
     */
    public function qrCode(string $uuid)
    {
        return (new Writer(new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd())))
            ->writeString(route('public.location', $uuid));
    }
}
