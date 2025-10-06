<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bon de Réception {{ $bonReception->numero }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
            line-height: 1.2;
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
        .document-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .document-number {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .date-section {
            text-align: right;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .fournisseur-section {
            margin-bottom: 30px;
            padding: 15px 0;
        }
        .fournisseur-label {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .fournisseur-line {
            border-bottom: 2px dotted #000;
            min-height: 25px;
            font-size: 16px;
            font-weight: bold;
            padding: 5px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 12px;
        }
        .table th {
            background-color: #e0e0e0;
            font-weight: bold;
            border: 2px solid #000;
            padding: 12px 8px;
            text-align: center;
            font-size: 12px;
        }
        .table td {
            border: 1px solid #000;
            padding: 10px 8px;
            text-align: left;
            font-size: 12px;
        }
        .signatures-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature-box {
            width: 30%;
        }
        .signature-label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
        .total-section {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            padding: 10px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        /* Style spécifique pour l'alignement des colonnes */
        .col-code { width: 15%; }
        .col-designation { width: 35%; }
        .col-quantite { width: 10%; }
        .col-prix { width: 15%; }
        .col-tva { width: 15%; }
        .col-total { width: 15%; }
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
        <!-- Titre du document -->
        <div class="document-title">
            Bon de réception
        </div>

        <!-- Numéro du document -->
        <div class="document-number">
            N° {{ $bonReception->numero }}
        </div>

        <!-- Date -->
        <div class="date-section">
            Date : {{ \Carbon\Carbon::parse($bonReception->date_reception)->format('d/m/Y') }}
        </div>

        <!-- Section Fournisseur comme dans le document -->
        <div class="fournisseur-section">
            <div class="fournisseur-label">Fournisseur</div>
            <div class="fournisseur-line">
                {{ $bonReception->fournisseur->raison_sociale ?? $bonReception->fournisseur->nom ?? '........................................' }}
            </div>
        </div>

        <!-- Tableau des articles -->
        <table class="table">
            <thead>
                <tr>
                    <th class="col-code text-center">Code<br>d'article</th>
                    <th class="col-designation text-center">Désignation</th>
                    <th class="col-quantite text-center">Quantité</th>
                    <th class="col-prix text-center">Prix<br>unitaire</th>
                    <th class="col-tva text-center">Montant de<br>TVA appliqué</th>
                    <th class="col-total text-center">Prix<br>total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalHT = 0;
                    $totalTVA = 0;
                    $totalTTC = 0;
                    $totalQuantite = 0;
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
                        <td class="text-center">{{ $ligne->article->reference ?? 'N/A' }}</td>
                        <td>{{ $ligne->article->designation ?? 'Article non trouvé' }}</td>
                        <td class="text-center">{{ number_format($ligne->quantite_receptionnee, 2) }}</td>
                        <td class="text-right">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} DH</td>
                        <td class="text-right">{{ number_format($montantTVA, 2, ',', ' ') }} DH</td>
                        <td class="text-right">{{ number_format($montantTTC, 2, ',', ' ') }} DH</td>
                    </tr>
                @endforeach
                
                <!-- Ligne des totaux -->
                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td colspan="2" class="text-right">Total</td>
                    <td class="text-center">{{ number_format($totalQuantite, 2) }}</td>
                    <td class="text-right">{{ number_format($totalHT, 2, ',', ' ') }} DH</td>
                    <td class="text-right">{{ number_format($totalTVA, 2, ',', ' ') }} DH</td>
                    <td class="text-right">{{ number_format($totalTTC, 2, ',', ' ') }} DH</td>
                </tr>
            </tbody>
        </table>

        <!-- Section des signatures -->
        <div class="signatures-section">
            <div class="signature-box">
                <div class="signature-label">Le magasinier</div>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <div class="signature-label">L'économe</div>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Le directeur</div>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>
</body>
</html>
