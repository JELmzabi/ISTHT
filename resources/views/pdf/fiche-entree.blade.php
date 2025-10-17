<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bon de Commande </title>
    @vite(['resources/css/app.css'])
</head>

<body class="m-0 p-0 text-black text-sm leading-snug relative min-h-screen flex flex-col">

    <!-- Main Content -->
    <div class="flex-1 p-5">

        <!-- HEADER -->
        @include('pdf.header')

        <!-- TITLE -->
        <div class="text-center font-bold text-lg uppercase underline mb-4">
            Bon de commande N° {{ $bonCommande->reference }}
        </div>


        <!-- ARTICLES TABLE -->
        <div class="overflow-x-auto mb-4">
            <table class="w-full border border-black border-collapse text-xs">
                <thead class="bg-gray-200 font-bold">
                    <tr>
                        <th class="border border-black p-1">Code d'article</th>
                        <th class="border border-black p-1">Désignation</th>
                        <th class="border border-black p-1">Unité</th>
                        <th class="border border-black p-1">Quantité</th>
                        <th class="border border-black p-1">Prix unitaire</th>
                        <th class="border border-black p-1">TVA appliquée</th>
                        <th class="border border-black p-1">Montant total</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>




        <!-- Signatures -->
        <div class="grid grid-cols-3 gap-8 text-center" style="margin-top: 36px;">
            <div>
                <div class="font-bold text-base">Le magasinier</div>
                <div class="border-t border-black h-10 mt-8"></div>
            </div>
            <div>
                <div class="font-bold text-base">L'économe</div>
                <div class="border-t border-black h-10 mt-8"></div>
            </div>
            <div>
                <div class="font-bold text-base">Le directeur</div>
                <div class="border-t border-black h-10 mt-8"></div>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    @include('pdf.footer')

</body>
</html>
