<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Cardex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: "Noto Sans", sans-serif;
        }
    </style>
</head>

<body class="m-0 p-0 text-black text-sm leading-snug relative min-h-screen flex flex-col">

    <!-- Main Content -->

    @foreach ($pages as $page)
    <div class="page mt-[160px]">
        <table class="w-full border border-gray-300 divide-y divide-gray-300 text-xs">
            <thead>
                <tr>
                    <th class="border-r border-gray-300 px-2 py-1">Jour</th>
                    @foreach ($page['months'] as $monthNumber => $monthName)
                        <th colspan="3" class="ext-center border border-gray-300 px-2 py-1">{{ $monthName }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th class="px-1 py-1 border-r border-gray-300"></th>
                    @foreach ($page['months'] as $monthNumber => $monthName)
                        <th class="px-1 py-1 border-r border-gray-300">Entrées</th>
                        <th class="px-1 py-1 border-r border-gray-300">Sorties</th>
                        <th class="px-1 py-1 border-r border-gray-300">Stock</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($page['days'] as $day)
                    <tr>
                        <td class="text-center border border-gray-300 px-2 py-1">{{ $day }}</td>
                        @foreach ($page['months'] as $monthNumber => $monthName)
                            @php $data = $cardex[$monthNumber][$day] ?? ['entrees' => '-', 'sorties' => '-', 'stock_final' => '-']; @endphp
                            <td class="px-1 py-1 text-right border border-gray-200">{{ $data['entrees'] }}</td>
                            <td class="px-1 py-1 text-right border border-gray-200">{{ $data['sorties'] }}</td>
                            <td class="px-1 py-1 text-right border border-gray-200">{{ $data['stock_final'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>

            @if (max($page['days']) >= 30)
                <tfoot>
                    <tr class="bg-gray-100 font-semibold">
                        <td class="text-center border border-gray-300 px-2 py-1">Totaux</td>
                        @foreach ($page['months'] as $monthNumber => $monthName)
                            @php
                                $totals = $monthTotals[$monthNumber] ?? [
                                    'total_entrees' => 0,
                                    'total_sorties' => 0,
                                    'stock_final' => 0
                                ];
                            @endphp
                            <td class="px-1 py-1 text-right border border-gray-300">{{ $totals['total_entrees'] }}</td>
                            <td class="px-1 py-1 text-right border border-gray-300">{{ $totals['total_sorties'] }}</td>
                            <td class="px-1 py-1 text-right border border-gray-300">{{ $totals['stock_final'] }}</td>
                        @endforeach
                    </tr>
                </tfoot>
            @endif
            
        </table>

        @pageBreak
    </div>
@endforeach


</body>
</html>
