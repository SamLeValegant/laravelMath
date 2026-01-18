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
        .calcul-table td { padding: 2px 8px 2px 0; font-size: 12px; height: 18px; vertical-align: bottom; }
        .answer-line { border-bottom: 1px dotted #333; display: inline-block; min-width: 28px; height: 10px; }
        .score-box { border: 1px solid #333; padding: 6px 18px; font-size: 15px; display: inline-block; margin-top: 18px; }
        .footer-row { width: 100%; text-align: center; margin-top: 12px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width:40%">Nom : ____________________</td>
            <td style="width:20%; text-align:center; font-size:18px; font-weight:bold;">100 calculs en 4 minutes !</td>
            <td style="width:40%; text-align:right;">Date : ____/____/______</td>
        </tr>
        <tr>
            <td></td>
            <td class="subtitle">Tables de multiplication</td>
            <td></td>
        </tr>
    </table>
    <table class="calcul-table">
        <tbody>
        @php
            $cols = 5;
            $rows = 20;
            $calculsArr = $calculs->values();
        @endphp
        @for ($i = 0; $i < $rows; $i++)
            <tr>
                @for ($j = 0; $j < $cols; $j++)
                    @php $idx = $i + $j * $rows; @endphp
                    <td>
                        @if(isset($calculsArr[$idx]))
                            {{ $calculsArr[$idx]['a'] }} × {{ $calculsArr[$idx]['b'] }} = <span class="answer-line">&nbsp;</span>
                        @endif
                    </td>
                @endfor
            </tr>
        @endfor
        </tbody>
    </table>
    <div class="footer-row">
        <span class="score-box">Score final : ____ / 100</span>
    </div>
</body>
</html>
