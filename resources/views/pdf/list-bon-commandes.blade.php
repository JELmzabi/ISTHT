<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Les Bons de Commande </title>
    @vite(['resources/css/app.css'])
</head>

<body class="m-0 p-0 text-black text-sm leading-snug relative min-h-screen flex flex-col">

    <!-- Main Content -->
    <div class="flex-1 p-5">

        <!-- HEADER -->
        @include('pdf.header')

        <!-- TITLE -->
        <div class="text-center font-bold text-4xl uppercase underline mb-4">
            Liste des bons de commande
        </div>
        <div class="text-center font-bold text-lg mb-4">
            @if ($endDate)
                {{ 'de ' . \Carbon\Carbon::parse($startDate)->translatedFormat('F Y') . ' à ' . \Carbon\Carbon::parse($endDate)->translatedFormat('F Y') }}
            @else
                {{ 'du mois de ' . \Carbon\Carbon::parse($startDate)->translatedFormat('F Y') }}
            @endif
        </div>


        <!-- ARTICLES TABLE -->
        <div class="overflow-x-auto mb-4">
            <table class="w-full border border-black border-collapse text-[10px]">
                <thead class="bg-gray-200 font-bold">
                    <tr>
                        <th class="border border-black p-1">Référence</th>
                        <th class="border border-black p-1">Objet</th>
                        <th class="border border-black p-1">Catégorie principale</th>
                        <th class="border border-black p-1">Nature de prestation</th>
                        <th class="border border-black p-1">Fournisseur</th>
                        <th class="border border-black p-1">Statut</th>
                        <th class="border border-black p-1">Date mise en ligne</th>
                        <th class="border border-black p-1">Date limite de réception</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($bonCommandes as $bon)
                        <tr>
                            <td class="border border-black text-center p-1">{{ $bon['reference'] }}</td>
                            <td class="border border-black text-center p-1">{{ $bon['objet'] }}</td>
                            <td class="border border-black text-center p-1">{{ $bon['categorie_principale'] ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $bon['nature_prestation'] ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $bon['fournisseur'] ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $bon['statut'] ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $bon['date_mise_ligne'] ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $bon['date_limite_reception'] ?? '-----' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

    <!-- FOOTER -->
    @include('pdf.footer')

</body>
</html>
