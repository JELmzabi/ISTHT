<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Cardex</title>
    @vite(['resources/css/app.css'])
    <style>
        .page-break { page-break-after: always; }
    </style>
</head>

<body class="m-0 p-0 text-black text-sm leading-snug relative min-h-screen flex flex-col">

    <!-- Main Content -->
    <div class="flex-1 p-5">

        <!-- HEADER -->
        <div class="w-full mb-4 flex">
            <div class="">
                @include('pdf.header')
            </div>

            <!-- TITLE -->
            <div>
                <div class="text-center mb-4 flex justify-center items-center gap-4">
                    CARDEX ARTICLE
                    <div class="border border-black p-2">{{ $article->designation }}</div>
                </div>
                <div class="text-center  mb-4">
                    <p><span class="">Maximum :</span> {{ $article->seuil_maximal }}</p>
                    <p><span class="">Minimum : :</span> {{ $article->seuil_minimal }}</p>
                    
                </div>
            </div>
        </div>
        

        @php
            // Split months
            $firstHalfMonths = array_slice($months, 0, 6, true); // Jan → Jun
            $secondHalfMonths = array_slice($months, 6, 6, true); // Jul → Dec
        @endphp

        <!-- FIRST TABLE (Jan → Jun) -->
        <div class=" mb-4">
            <table class="min-w-full border border-gray-300 divide-y divide-gray-300 text-xs">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="sticky left-0 bg-gray-100 z-10 border-r border-gray-300 px-2 py-1">Jour</th>
                        @foreach ($firstHalfMonths as $monthName)
                            <th class="text-center border border-gray-300 px-2 py-1" colspan="3">{{ $monthName }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="sticky left-0 bg-gray-100 z-10 border-r border-gray-300 px-2 py-1"></th>
                        @foreach ($firstHalfMonths as $monthIndex => $monthName)
                            <th class="px-1 py-1 border-r border-gray-300">Entrées</th>
                            <th class="px-1 py-1 border-r border-gray-300">Sorties</th>
                            <th class="px-1 py-1 border-r border-gray-300">Stock final</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @for ($day = 1; $day <= 31; $day++)
                        <tr>
                            <td class="sticky left-0 bg-gray-50 z-10 border border-gray-300 px-2 py-1">{{ $day }}</td>
                            @foreach ($firstHalfMonths as $monthIndex => $monthName)
                                @php $data = $cardex[$monthIndex][$day] ?? null; @endphp
                                <td class="px-1 py-1 text-right border border-gray-200">{{ $data['entrees'] ?? '' }}</td>
                                <td class="px-1 py-1 text-right border border-gray-200">{{ $data['sorties'] ?? '' }}</td>
                                <td class="px-1 py-1 text-right border border-gray-200">{{ $data['stock_final'] ?? '' }}</td>
                            @endforeach
                        </tr>
                    @endfor

                    <tr class="font-bold bg-gray-100">
                <td class="sticky left-0 bg-gray-100 z-10 border border-gray-300 px-2 py-1">TOTAL</td>
                @foreach ($firstHalfMonths as $monthIndex => $monthName)
                    @php
                        $entreeTotal = collect($cardex[$monthIndex] ?? [])->sum('entrees');
                        $sortieTotal = collect($cardex[$monthIndex] ?? [])->sum('sorties');
                        $stockFinal = collect($cardex[$monthIndex] ?? [])->last()['stock_final'] ?? 0;
                    @endphp
                    <td class="px-1 py-1 text-right border border-gray-200">{{ $entreeTotal }}</td>
                    <td class="px-1 py-1 text-right border border-gray-200">{{ $sortieTotal }}</td>
                    <td class="px-1 py-1 text-right border border-gray-200">{{ $stockFinal }}</td>
                @endforeach
            </tr>
                </tbody>
            </table>
        </div>

        <div class="page-break"></div>

        <!-- SECOND TABLE (Jul → Dec) -->
        <div class=" mb-4">
            <table class="min-w-full border border-gray-300 divide-y divide-gray-300 text-xs">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="sticky left-0 bg-gray-100 z-10 border-r border-gray-300 px-2 py-1">Jour</th>
                        @foreach ($secondHalfMonths as $monthName)
                            <th class="text-center border border-gray-300 px-2 py-1" colspan="3">{{ $monthName }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="sticky left-0 bg-gray-100 z-10 border-r border-gray-300 px-2 py-1"></th>
                        @foreach ($secondHalfMonths as $monthIndex => $monthName)
                            <th class="px-1 py-1 border-r border-gray-300">Entrées</th>
                            <th class="px-1 py-1 border-r border-gray-300">Sorties</th>
                            <th class="px-1 py-1 border-r border-gray-300">Stock final</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @for ($day = 1; $day <= 31; $day++)
                        <tr>
                            <td class="sticky left-0 bg-gray-50 z-10 border border-gray-300 px-2 py-1">{{ $day }}</td>
                            @foreach ($secondHalfMonths as $monthIndex => $monthName)
                                @php $data = $cardex[$monthIndex][$day] ?? null; @endphp
                                <td class="px-1 py-1 text-right border border-gray-200">{{ $data['entrees'] ?? '' }}</td>
                                <td class="px-1 py-1 text-right border border-gray-200">{{ $data['sorties'] ?? '' }}</td>
                                <td class="px-1 py-1 text-right border border-gray-200">{{ $data['stock_final'] ?? '' }}</td>
                            @endforeach
                        </tr>
                    @endfor

                    <tr class="font-bold bg-gray-100">
                <td class="sticky left-0 bg-gray-100 z-10 border border-gray-300 px-2 py-1">TOTAL</td>
                @foreach ($secondHalfMonths as $monthIndex => $monthName)
                    @php
                        $entreeTotal = collect($cardex[$monthIndex] ?? [])->sum('entrees');
                        $sortieTotal = collect($cardex[$monthIndex] ?? [])->sum('sorties');
                        $stockFinal = collect($cardex[$monthIndex] ?? [])->last()['stock_final'] ?? 0;
                    @endphp
                    <td class="px-1 py-1 text-right border border-gray-200">{{ $entreeTotal }}</td>
                    <td class="px-1 py-1 text-right border border-gray-200">{{ $sortieTotal }}</td>
                    <td class="px-1 py-1 text-right border border-gray-200">{{ $stockFinal }}</td>
                @endforeach
            </tr>
                    
                </tbody>
            </table>
        </div>

    </div>

    <!-- FOOTER -->
    @include('pdf.footer')

</body>
</html>
