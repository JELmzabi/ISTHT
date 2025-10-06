import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';

class PdfService {
    static async generateArticlePDF(article, headerImageUrl, footerImageUrl) {
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        
        // Ajouter l'en-tête
        if (headerImageUrl) {
            try {
                const headerImg = await this.loadImage(headerImageUrl);
                const headerHeight = 30;
                pdf.addImage(headerImg, 'JPEG', 10, 10, pageWidth - 20, headerHeight);
            } catch (error) {
                console.warn('Erreur chargement image header:', error);
            }
        }

        let yPosition = 50; // Position de départ après l'en-tête

        // Titre principal
        pdf.setFontSize(18);
        pdf.setFont('helvetica', 'bold');
        pdf.text('FICHE ARTICLE', pageWidth / 2, yPosition, { align: 'center' });
        yPosition += 10;

        // Informations principales
        pdf.setFontSize(12);
        pdf.setFont('helvetica', 'normal');
        
        // Première colonne
        pdf.text(`Référence: ${article.reference}`, 20, yPosition);
        pdf.text(`Désignation: ${article.designation}`, 20, yPosition + 7);
        
        // Deuxième colonne
        pdf.text(`Unité: ${article.unite_mesure}`, 110, yPosition);
        pdf.text(`TVA: ${article.taux_tva}%`, 110, yPosition + 7);
        
        yPosition += 20;

        // Description
        if (article.description) {
            pdf.setFont('helvetica', 'bold');
            pdf.text('Description:', 20, yPosition);
            pdf.setFont('helvetica', 'normal');
            
            const descriptionLines = pdf.splitTextToSize(article.description, pageWidth - 40);
            pdf.text(descriptionLines, 20, yPosition + 7);
            yPosition += 7 + (descriptionLines.length * 5);
        }

        yPosition += 5;

        // Catégorisation
        pdf.setFont('helvetica', 'bold');
        pdf.text('Catégorisation:', 20, yPosition);
        pdf.setFont('helvetica', 'normal');
        
        pdf.text(`Catégorie Principale: ${article.categorie_principale.nom}`, 30, yPosition + 7);
        pdf.text(`Catégorie: ${article.categorie.nom}`, 30, yPosition + 14);
        pdf.text(`Nature: ${article.nature_prestation.nom}`, 30, yPosition + 21);
        
        yPosition += 30;

        // Gestion des stocks
        pdf.setFont('helvetica', 'bold');
        pdf.text('Gestion des Stocks:', 20, yPosition);
        pdf.setFont('helvetica', 'normal');
        
        pdf.text(`Stock Actuel: ${article.stock_actuel}`, 30, yPosition + 7);
        pdf.text(`Seuil Minimal: ${article.seuil_minimal}`, 30, yPosition + 14);
        pdf.text(`Seuil Maximal: ${article.seuil_maximal}`, 30, yPosition + 21);
        
        yPosition += 30;

        // Images des articles
        if (article.images && article.images.length > 0) {
            pdf.setFont('helvetica', 'bold');
            pdf.text('Images de l\'article:', 20, yPosition);
            yPosition += 10;

            for (let i = 0; i < article.images.length; i++) {
                const image = article.images[i];
                
                // Vérifier si on a besoin d'une nouvelle page
                if (yPosition > pageHeight - 60) {
                    this.addFooter(pdf, footerImageUrl, pageWidth, pageHeight);
                    pdf.addPage();
                    yPosition = 20;
                }

                try {
                    const imgUrl = `/storage/${image.image_path}`;
                    const img = await this.loadImage(imgUrl);
                    
                    // Redimensionner l'image pour s'adapter à la page
                    const imgWidth = pageWidth - 40;
                    const imgHeight = (img.height * imgWidth) / img.width;
                    
                    pdf.addImage(img, 'JPEG', 20, yPosition, imgWidth, imgHeight);
                    yPosition += imgHeight + 10;
                    
                    // Légende
                    pdf.setFontSize(10);
                    pdf.text(`Image ${i + 1}${image.est_principale ? ' (Principale)' : ''}`, 20, yPosition);
                    yPosition += 5;
                    
                } catch (error) {
                    console.warn(`Erreur chargement image ${i + 1}:`, error);
                    pdf.text(`[Image ${i + 1} non disponible]`, 20, yPosition);
                    yPosition += 10;
                }
            }
        }

        // Métadonnées
        yPosition += 10;
        pdf.setFont('helvetica', 'bold');
        pdf.text('Métadonnées:', 20, yPosition);
        pdf.setFont('helvetica', 'normal');
        
        pdf.text(`ID: ${article.id}`, 30, yPosition + 7);
        pdf.text(`Créé le: ${new Date(article.created_at).toLocaleDateString('fr-FR')}`, 30, yPosition + 14);
        pdf.text(`Modifié le: ${new Date(article.updated_at).toLocaleDateString('fr-FR')}`, 30, yPosition + 21);
        pdf.text(`Statut: ${article.est_actif ? 'Actif' : 'Inactif'}`, 30, yPosition + 28);

        // Ajouter le pied de page
        this.addFooter(pdf, footerImageUrl, pageWidth, pageHeight);

        // Sauvegarder le PDF
        pdf.save(`article-${article.reference}-${new Date().toISOString().split('T')[0]}.pdf`);
    }

    static addFooter(pdf, footerImageUrl, pageWidth, pageHeight) {
        if (footerImageUrl) {
            try {
                const footerHeight = 20;
                const footerY = pageHeight - footerHeight - 10;
                pdf.addImage(footerImageUrl, 'JPEG', 10, footerY, pageWidth - 20, footerHeight);
            } catch (error) {
                console.warn('Erreur chargement image footer:', error);
            }
        }

        // Numéro de page
        const pageCount = pdf.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            pdf.setPage(i);
            pdf.setFontSize(8);
            pdf.setTextColor(128);
            pdf.text(
                `Page ${i} sur ${pageCount}`,
                pageWidth / 2,
                pageHeight - 5,
                { align: 'center' }
            );
        }
    }

    static loadImage(url) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = url;
        });
    }

    // Méthode alternative utilisant html2canvas
    static async generatePDFFromHTML(element, filename = 'document.pdf') {
        const canvas = await html2canvas(element, {
            scale: 2,
            useCORS: true,
            logging: false
        });

        const imgData = canvas.toDataURL('image/jpeg', 1.0);
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();

        const imgWidth = canvas.width;
        const imgHeight = canvas.height;
        const ratio = imgWidth / imgHeight;

        let pdfWidth = pageWidth - 20;
        let pdfHeight = pdfWidth / ratio;

        if (pdfHeight > pageHeight) {
            pdfHeight = pageHeight - 20;
            pdfWidth = pdfHeight * ratio;
        }

        pdf.addImage(imgData, 'JPEG', 10, 10, pdfWidth, pdfHeight);
        pdf.save(filename);
    }
}

export default PdfService;