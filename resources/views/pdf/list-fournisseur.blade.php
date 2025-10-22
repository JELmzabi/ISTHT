<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Les fournisseurs </title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body class="m-0 p-0 text-black text-sm leading-snug relative min-h-screen flex flex-col">

@foreach($chunks as $chunk)
        @php $fournisseurs = $chunk @endphp

    <!-- Main Content -->
    <div class="flex-1 p-5 mt-[135px]">

        <!-- TITLE -->
        <div class="text-center font-bold text-4xl uppercase underline mb-4">
            Liste des fournisseurs
        </div>
        
        <!-- ARTICLES TABLE -->
        <div class=" mb-4">
            <table class="w-full border border-black border-collapse text-[10px]">
                <thead class="bg-gray-200 font-bold">
                    <tr>
                        <th class="border border-black p-1">Nom</th>
                        <th class="border border-black p-1">Contact</th>
                        <th class="border border-black p-1">Telephone</th>
                        <th class="border border-black p-1">Email</th>
                        <th class="border border-black p-1">Ville</th>
                        <th class="border border-black p-1">Raison sociale</th>
                        <th class="border border-black p-1">ICE</th>
                        <th class="border border-black p-1">Est actif</th>
                        <th class="border border-black p-1">Ajouté à</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($fournisseurs as $fournisseur)
                        <tr>
                            <td class="border border-black text-center p-1">{{ $fournisseur->nom }}</td>
                            <td class="border border-black text-center p-1">{{ $fournisseur->contact ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $fournisseur->telephone ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $fournisseur->email ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $fournisseur->ville ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $fournisseur->raison_sociale ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $fournisseur->ice ?? '-----' }}</td>
                            <td class="border border-black text-center p-1">{{ $fournisseur->est_actif ? 'Oui' : 'Non' }}</td>
                            <td class="border border-black text-center p-1">{{ $fournisseur->created_at->toDateString() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    @unless($loop->last)
        @pageBreak
    @endunless
@endforeach

</body>
</html>
