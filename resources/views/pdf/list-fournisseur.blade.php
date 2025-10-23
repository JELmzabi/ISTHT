<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Les fournisseurs </title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body class="m-0 p-0 text-black text-sm leading-snug relative min-h-screen flex flex-col">

    <!-- Main Content -->
    <div class="flex-1">

        <!-- TITLE -->
        <div class="text-center font-bold text-[12pt] uppercase underline mb-4">
            Liste des fournisseurs
        </div>
        
        <!-- ARTICLES TABLE -->
        <div class=" mb-4">
            <table class="w-full border border-black border-collapse text-[8pt]">
                <thead class="bg-gray-200 font-bold">
                    <tr>
                        <th class="border border-black">Nom</th>
                        <th class="border border-black">Contact</th>
                        <th class="border border-black">Telephone</th>
                        <th class="border border-black">Email</th>
                        <th class="border border-black">Ville</th>
                        <th class="border border-black">Raison sociale</th>
                        <th class="border border-black">ICE</th>
                        <th class="border border-black">Est actif</th>
                        <th class="border border-black">Ajouté à</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($fournisseurs as $fournisseur)
                        <tr>
                            <td class="border border-black text-center">{{ $fournisseur->nom }}</td>
                            <td class="border border-black text-center">{{ $fournisseur->contact ?? '-----' }}</td>
                            <td class="border border-black text-center">{{ $fournisseur->telephone ?? '-----' }}</td>
                            <td class="border border-black text-center">{{ $fournisseur->email ?? '-----' }}</td>
                            <td class="border border-black text-center">{{ $fournisseur->ville ?? '-----' }}</td>
                            <td class="border border-black text-center">{{ $fournisseur->raison_sociale ?? '-----' }}</td>
                            <td class="border border-black text-center">{{ $fournisseur->ice ?? '-----' }}</td>
                            <td class="border border-black text-center">{{ $fournisseur->est_actif ? 'Oui' : 'Non' }}</td>
                            <td class="border border-black text-center">{{ $fournisseur->created_at->toDateString() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
