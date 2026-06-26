<?php

namespace App\Services;

class LocationService
{
    private $bekasiPolygon = [
        [107.083223, -6.286109],
        [107.097336, -6.264045],
        [107.101355, -6.241407],
        [107.093863, -6.231385],
        [107.086643, -6.222214],
        [107.070794, -6.183552],
        [107.066807, -6.158052],
        [107.062690, -6.134642],
        [107.046246, -6.115988],
        [107.022301, -6.112793],
        [106.992077, -6.112267],
        [106.955071, -6.120802],
        [106.941306, -6.127023],
        [106.942428, -6.166127],
        [106.944138, -6.198941],
        [106.953185, -6.218134],
        [106.950742, -6.233923],
        [106.956606, -6.249712],
        [106.983976, -6.250685],
        [107.083223, -6.286109]
    ];

    public function isPointInPolygon($point, $polygon)
    {
        $x = $point[0];
        $y = $point[1];
        $inside = false;

        $count = count($polygon);

        for ($i = 0, $j = $count - 1; $i < $count; $j = $i++) {
            $xi = $polygon[$i][0];
            $yi = $polygon[$i][1];
            $xj = $polygon[$j][0];
            $yj = $polygon[$j][1];

            $intersect = (($yi > $y) != ($yj > $y)) &&
                ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }

    public function isInBekasi($lat, $lng)
    {
        return $this->isPointInPolygon([$lng, $lat], $this->bekasiPolygon);
    }

    public function getPolygon()
    {
        return $this->bekasiPolygon;
    }
    
}