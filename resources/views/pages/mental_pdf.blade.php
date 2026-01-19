<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calcul mental</title>
    <style>
        @page { size: A4; margin: 18px 18px 18px 18px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 0; }
        .header-table { width: 100%; margin-bottom: 8px; }
        .header-table td { font-size: 12px; padding: 2px 4px; }
        .title { font-size: 20px; font-weight: bold; text-align: center; }
        .subtitle { font-size: 13px; text-align: center; margin-bottom: 2px; }
        .calcul-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .calcul-table td { padding: 8px 8px 8px 0; font-size: 12px; height: 26px; vertical-align: bottom; }
        .calc-eq { display: inline-block; min-width: 38px; text-align: right; }
        .answer-line { border-bottom: 1px dotted #333; display: inline-block; min-width: 28px; height: 10px; }
        .score-box { border: 1px solid #333; padding: 6px 18px; font-size: 15px; display: inline-block; margin-top: 18px; }
        .footer-row { width: 100%; text-align: center; margin-top: 40px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width:40%">Nom / prénom : ____________________</td>
            <td style="width:20%; text-align:center; font-size:18px; font-weight:bold;">{{ $calculs->count() }} calculs</td>
            <td style="width:40%; text-align:right;">Date : ____/____/______</td>
        </tr>
        <!-- Ligne sous-titre supprimée -->
    </table>
    @php
        if (!function_exists('format_nb_pdf')) {
            function format_nb_pdf($v, $dec) {
                $v = (string)$v;
                if (strpos($v, '.') !== false) {
                    $v = number_format((float)$v, $dec, ',', ' ');
                    $v = rtrim(rtrim($v, '0'), ',');
                    if ($v === '' || $v === '-') $v = '0';
                }
                return $v === '' || $v === '-' ? '0' : $v;
            }
        }
        $cols = 5;
        $rows = 20;
        $calculsArr = $calculs->values();
        $dec = isset($_GET['decimal_places']) ? intval($_GET['decimal_places']) : 2;
    @endphp
        <table class="calcul-table">
            <tbody>
            @for ($i = 0; $i < $rows; $i++)
                <tr>
                    @for ($j = 0; $j < $cols; $j++)
                        @php $idx = $i + $j * $rows; @endphp
                        <td>
                            @if(isset($calculsArr[$idx]))
                                @php
                                    $op = $calculsArr[$idx]['op'] ?? 'mul';
                                    $opSymbol = match($op) {
                                        'add' => '+',
                                        'sub' => '-',
                                        'div' => '÷',
                                        default => '×',
                                    };
                                @endphp
                                <span class="calc-eq">
                                    {{ format_nb_pdf($calculsArr[$idx]['a'], (fmod($calculsArr[$idx]['a'],1) != 0 ? $dec : 0)) }} {{ $opSymbol }} {{ format_nb_pdf($calculsArr[$idx]['b'], (fmod($calculsArr[$idx]['b'],1) != 0 ? $dec : 0)) }} =
                                </span> <span class="answer-line">&nbsp;</span>
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
            </tbody>
        </table>
        </tbody>
    </table>
    <div class="footer-row">
        <span class="score-box">Score final : ____ / {{ $calculs->count() }}</span>
    </div>
</body>
</html>
