<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bon de Réception {{ $bonReception->numero }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-0 p-0 text-black text-sm leading-snug relative min-h-screen flex flex-col">

    <!-- Main Content -->
    <div class="flex-1 p-5">

        <!-- HEADER -->
        @include('pdf.header')

        <!-- DOCUMENT INFO -->
        <div class="mb-6">
            <div class="text-center font-bold text-lg underline uppercase py-2">Bon de réception N° {{ $bonReception->numero }}</div>
        </div>

        <!-- Fournisseur -->
        <div class="mb-6">
            <div class="font-bold text-base mb-1">Fournisseur: {{ $bonReception->fournisseur->raison_sociale ?? $bonReception->fournisseur->nom ?? '........................................' }}</div>
            <div class="font-bold">
                Date : {{ \Carbon\Carbon::parse($bonReception->date_reception)->format('d/m/Y') }}
            </div>
        </div>

        <!-- Articles Table -->
        <div class="overflow-x-auto mb-6">
            <table class="w-full border border-black border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-200 text-center font-bold">
                        <th class=" border border-black p-1">Code d'article</th>
                        <th class=" border border-black p-1">Désignation</th>
                        <th class="border border-black p-1">Quantité</th>
                        <th class=" border border-black p-1">Prix unitaire</th>
                        <th class=" border border-black p-1">Montant TVA</th>
                        <th class=" border border-black p-1">Prix total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalHT = $totalTVA = $totalTTC = $totalQuantite = 0;
                    @endphp

                    @foreach($bonReception->lignesReception as $ligne)
                        @php
                            $montantHT = $ligne->quantite_receptionnee * $ligne->prix_unitaire;
                            $montantTVA = $montantHT * ($ligne->taux_tva / 100);
                            $montantTTC = $montantHT + $montantTVA;

                            $totalHT += $montantHT;
                            $totalTVA += $montantTVA;
                            $totalTTC += $montantTTC;
                            $totalQuantite += $ligne->quantite_receptionnee;
                        @endphp
                        <tr>
                            <td class="border border-black text-center p-1">{{ $ligne->article->reference ?? 'N/A' }}</td>
                            <td class="border border-black p-1">{{ $ligne->article->designation ?? 'Article non trouvé' }}</td>
                            <td class="border border-black text-center p-1">{{ number_format($ligne->quantite_receptionnee, 2) }}</td>
                            <td class="border border-black text-right p-1">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} DH</td>
                            <td class="border border-black text-right p-1">{{ number_format($montantTVA, 2, ',', ' ') }} DH</td>
                            <td class="border border-black text-right p-1">{{ number_format($montantTTC, 2, ',', ' ') }} DH</td>
                        </tr>
                    @endforeach

                    <tr class="bg-gray-200 font-bold">
                        <td colspan="5" class="border border-black text-right p-1">Total</td>
                        <td class="border border-black text-right p-1">{{ number_format($totalTTC, 2, ',', ' ') }} DH</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-3 gap-8 text-center mt-8">
            <div>
                <div class="font-bold text-base">Le magasinier</div>
                <div class="border-t border-black h-10 mt-4"></div>
            </div>
            <div>
                <div class="font-bold text-base">L'économe</div>
                <div class="border-t border-black h-10 mt-4"></div>
            </div>
            <div>
                <div class="font-bold text-base">Le directeur</div>
                <div class="border-t border-black h-10 mt-4"></div>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    @include('pdf.footer')

</body>
</html>
