<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bon de Commande {{ $bonCommande->reference }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 120px;
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
        }
        .content {
            margin-top: 130px;
            margin-bottom: 90px;
            padding: 0 20px;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .supplier-info {
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        .signatures-container {
            margin-top: 40px;
            width: 100%;
        }
        .signature-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            width: 100%;
        }
        .signature-text {
            font-weight: bold;
            width: 120px;
        }
        .signature-space {
            border-top: 1px solid #000;
            flex-grow: 1;
            margin-left: 20px;
            padding-top: 5px;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding: 10px;
            background-color: #f0f0f0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        @if(file_exists(public_path('images/pdf-header.jpg')))
            <img src="{{ public_path('images/pdf-header.jpg') }}" style="width: 100%; height: 120px;">
        @else
            <div style="height: 120px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                <h2>ROYAUME DU MAROC</h2>
            </div>
        @endif
    </div>

    <!-- Pied de page -->
    <div class="footer">
        @if(file_exists(public_path('images/pdf-footer.jpg')))
            <img src="{{ public_path('images/pdf-footer.jpg') }}" style="width: 100%; height: 80px;">
        @else
            <div style="height: 80px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                <p>Institut Spécialisé de Technologie Appliquée Hôtelière et Touristique de Tanger</p>
            </div>
        @endif
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <!-- Titre -->
        <div class="title">
            Bon de commande N° {{ $bonCommande->reference }}
        </div>

        <!-- Informations fournisseur -->
        <div class="supplier-info">
            <div class="info-row">
                <span class="info-label">Fournisseur:</span>
                <span>{{ $fournisseur->nom ?? $fournisseur->raison_sociale ?? 'Non spécifié' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Adresse:</span>
                <span>{{ $fournisseur->adresse ?? 'Non spécifié' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Catégorie des articles:</span>
                <span>{{ $bonCommande->categoriePrincipale->nom ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Commande pour le:</span>
                <span>{{ \Carbon\Carbon::parse($bonCommande->date_mise_ligne)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date limite réception:</span>
                <span>{{ \Carbon\Carbon::parse($bonCommande->date_limite_reception)->format('d/m/Y') }}</span>
            </div>
        </div>

        <!-- Tableau des articles -->
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 15%">Code d'article</th>
                    <th style="width: 30%">Désignation d'article</th>
                    <th style="width: 10%">Unité de mesure</th>
                    <th style="width: 10%">Quantité</th>
                    <th style="width: 15%">Prix unitaire</th>
                    <th style="width: 10%">TVA appliquée</th>
                    <th style="width: 15%">Montant total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalHT = 0;
                    $totalTVA = 0;
                    $totalTTC = 0;
                @endphp
                
                @foreach($articles as $article)
                    @php
                        $prixHT = $article->prix_unitaire_ht ?? 0;
                        $quantite = $article->quantite_commandee ?? 0;
                        $tauxTVA = $article->taux_tva ?? 0;
                        
                        $montantHT = $prixHT * $quantite;
                        $montantTVA = $montantHT * ($tauxTVA / 100);
                        $montantTTC = $montantHT + $montantTVA;
                        
                        $totalHT += $montantHT;
                        $totalTVA += $montantTVA;
                        $totalTTC += $montantTTC;
                    @endphp
                    <tr>
                        <td>{{ $article->article->reference ?? 'N/A' }}</td>
                        <td>{{ $article->article->designation ?? 'N/A' }}</td>
                        <td>{{ $article->article->unite_mesure ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ number_format($quantite, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($prixHT, 2, ',', ' ') }} DH</td>
                        <td style="text-align: center;">{{ $tauxTVA }}%</td>
                        <td style="text-align: right;">{{ number_format($montantTTC, 2, ',', ' ') }} DH</td>
                    </tr>
                @endforeach
                
                <!-- Ligne des totaux -->
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td colspan="4" style="text-align: right;">TOTAL HT:</td>
                    <td style="text-align: right;">{{ number_format($totalHT, 2, ',', ' ') }} DH</td>
                    <td style="text-align: center;">-</td>
                    <td style="text-align: right;">{{ number_format($totalHT, 2, ',', ' ') }} DH</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td colspan="4" style="text-align: right;">TOTAL TVA:</td>
                    <td style="text-align: right;">{{ number_format($totalTVA, 2, ',', ' ') }} DH</td>
                    <td style="text-align: center;">-</td>
                    <td style="text-align: right;">{{ number_format($totalTVA, 2, ',', ' ') }} DH</td>
                </tr>
                <tr style="font-weight: bold; background-color: #e0e0e0;">
                    <td colspan="4" style="text-align: right;">TOTAL TTC:</td>
                    <td style="text-align: right;">{{ number_format($totalTTC, 2, ',', ' ') }} DH</td>
                    <td style="text-align: center;">-</td>
                    <td style="text-align: right;">{{ number_format($totalTTC, 2, ',', ' ') }} DH</td>
                </tr>
            </tbody>
        </table>

        <!-- Arrêtée la présente commande à la somme de -->
        <div style="margin-top: 20px; padding: 10px; border: 1px solid #000; background-color: #f9f9f9;">
            <strong>Arrêtée la présente commande à la somme de :</strong>
            {{ number_format($totalTTC, 2, ',', ' ') }} Dirhams
        </div>

        <!-- Signatures alignées comme dans l'image -->
        <div class="signatures-container">
            <div class="signature-line">
                <div class="signature-text">Le directeur</div>
                <div class="signature-space"></div>
            </div>
            <div class="signature-line">
                <div class="signature-text">L'économe</div>
                <div class="signature-space"></div>
            </div>
            <div class="signature-line">
                <div class="signature-text">Le responsable</div>
                <div class="signature-space"></div>
            </div>
        </div>
    </div>
</body>
</html>