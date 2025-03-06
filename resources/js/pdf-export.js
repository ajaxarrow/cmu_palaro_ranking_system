// Modern PDF Export Utility
function createModernPDFReport(options) {
    // Default configuration with ability to override
    const defaultConfig = {
        // Color Palette
        colors: {
            primary: [0, 73, 30],       // Dark Green #00491e
            secondary: [2, 104, 30],     // Bright Green #02681e
            accent: [255, 198, 1],       // Gold #ffc601
            light: [241, 245, 249],
            white: [255, 255, 255],
            lightText: [148, 163, 184],
            darkText: [30, 41, 59]
        },
        
        // Customizable elements
        title: 'Report',
        subtitle: '',
        logoPath: null,
        
        // Data sections
        summaryBoxes: [],
        tableData: [],
        
        // Table configuration
        tableConfig: {
            columns: [],
            columnStyles: {},
            headStyles: {},
            alternateRowStyles: {}
        },
        
        // Additional sections or charts
        additionalSections: [],
        
        // Styling and layout
        pageSize: 'a4',
        orientation: 'landscape'
    };
    
    // Merge default config with provided options
    const config = {
        ...defaultConfig,
        ...options,
        colors: { ...defaultConfig.colors, ...options.colors }
    };
    
    // Create PDF document
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF(config.orientation, 'mm', config.pageSize);
    
    // Utility function to create modern cover page
    function createCoverPage() {
        // Background fill
        doc.setFillColor(...config.colors.primary);
        doc.rect(0, 0, doc.internal.pageSize.width, doc.internal.pageSize.height, 'F');
        
        // Add a large swoosh design element
        doc.setFillColor(...config.colors.secondary);
        doc.roundedRect(0, doc.internal.pageSize.height - 110, doc.internal.pageSize.width, 110, 80, 0, 'F');
        
        // Title and Subtitle
        doc.setTextColor(...config.colors.white);
        doc.setFont('helvetica', 'bold');
        
        // Main Title
        doc.setFontSize(38);
        doc.text(config.title, 30, 50);
        
        // Subtitle
        if (config.subtitle) {
            doc.setFontSize(26);
            doc.text(config.subtitle, 30, 65);
        }
        
        // Optional Logo
        if (config.logoPath) {
            // Add logo logic here (you'd need to handle image loading)
            // doc.addImage(config.logoPath, 'PNG', x, y, width, height);
        }
        
        // Accent color circles
        const accentColors = [
            config.colors.accent,
            config.colors.lightText,
            config.colors.white
        ];
        
        accentColors.forEach((color, index) => {
            doc.setFillColor(...color);
            doc.circle(35 + (index * 30), 100, 10, 'F');
        });
        
        // Generation Date
        doc.setTextColor(...config.colors.lightText);
        doc.setFontSize(12);
        doc.text(`Generated: ${new Date().toLocaleDateString()}`, 30, 140);
    }
    
    // Utility function to create page header
    function createPageHeader() {
        doc.setFillColor(...config.colors.primary);
        doc.rect(0, 0, doc.internal.pageSize.width, 20, 'F');
        
        doc.setTextColor(...config.colors.white);
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text(config.title, doc.internal.pageSize.width / 2, 13, { align: 'center' });
    }
    
    // Utility function to create summary boxes
    function createSummaryBoxes() {
        const summaryStartY = 30;
        const boxWidth = 60;
        const boxHeight = 30;
        const gapBetweenBoxes = 10;
        
        config.summaryBoxes.forEach((box, index) => {
            // Calculate box position
            const xPosition = 20 + (boxWidth + gapBetweenBoxes) * index;
            
            // Set box color
            doc.setFillColor(...box.color);
            doc.roundedRect(xPosition, summaryStartY, boxWidth, boxHeight, 3, 3, 'F');
            
            // Set text color
            doc.setTextColor(...config.colors.darkText);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            
            // Add title
            doc.text(box.title, xPosition + boxWidth / 2, summaryStartY + 10, { align: 'center' });
            
            // Add value
            doc.setFontSize(18);
            doc.text(box.value, xPosition + boxWidth / 2, summaryStartY + 22, { align: 'center' });
        });
        
        return summaryStartY + boxHeight + 15;
    }
    
    // Utility function to create table
    function createModernTable(startY) {
        const defaultTableConfig = {
            theme: 'grid',
            styles: {
                fontSize: 9,
                cellPadding: 4,
                lineColor: [240, 240, 240],
                lineWidth: 0.1,
                font: 'helvetica',
            },
            headStyles: {
                fillColor: config.colors.primary,
                textColor: config.colors.white,
                fontSize: 10,
                fontStyle: 'bold',
                halign: 'center',
                cellPadding: 5,
                lineWidth: 0
            },
            alternateRowStyles: {
                fillColor: config.colors.light
            },
            margin: { top: 20, left: 10, right: 10 }
        };
        
        // Merge default config with provided table config
        const tableConfig = {
            ...defaultTableConfig,
            ...config.tableConfig,
            headStyles: { ...defaultTableConfig.headStyles, ...config.tableConfig.headStyles },
            columnStyles: { ...config.tableConfig.columnStyles }
        };
        
        doc.autoTable({
            startY: startY,
            head: [tableConfig.columns],
            body: config.tableData,
            ...tableConfig
        });
    }
    
    // Utility function to add page footer
    function addPageFooter() {
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            
            // Skip footer on the cover page
            if (i === 1) continue;
            
            // Add subtle footer line
            doc.setDrawColor(...config.colors.light);
            doc.setLineWidth(0.5);
            doc.line(10, doc.internal.pageSize.height - 15, doc.internal.pageSize.width - 10, doc.internal.pageSize.height - 15);
            
            // Add page number
            doc.setFillColor(...config.colors.light);
            doc.roundedRect(doc.internal.pageSize.width / 2 - 15, doc.internal.pageSize.height - 12, 30, 8, 4, 4, 'F');
            
            doc.setTextColor(...config.colors.darkText);
            doc.setFontSize(8);
            doc.text(
                `Page ${i} of ${pageCount}`, 
                doc.internal.pageSize.width / 2, 
                doc.internal.pageSize.height - 7,
                { align: 'center' }
            );
            
            // Add footer text
            doc.setTextColor(...config.colors.accent);
            doc.text(
                'Comprehensive Report', 
                20, 
                doc.internal.pageSize.height - 7
            );
            
            // Add title on the right
            doc.text(
                config.title, 
                doc.internal.pageSize.width - 20, 
                doc.internal.pageSize.height - 7,
                { align: 'right' }
            );
        }
    }
    
    // Generate the PDF
    function generatePDF() {
        // Create cover page
        createCoverPage();
        
        // Add a new page for content
        doc.addPage();
        
        // Create page header
        createPageHeader();
        
        // Create summary boxes
        const tableStartY = createSummaryBoxes();
        
        // Create table
        createModernTable(tableStartY);
        
        // Add page footers
        addPageFooter();
        
        return doc;
    }
    
    return {
        generate: generatePDF,
        save: (filename) => {
            const pdf = generatePDF();
            pdf.save(filename || `${config.title.replace(/\s+/g, '_')}_report.pdf`);
        }
    };
}

// Example usage in the existing export script
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.getElementById('exportPDF');
    const progressBar = document.getElementById('exportProgress');
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.getElementById('progressText');
    
    exportBtn.addEventListener('click', function() {
        progressBar.style.display = 'block';
        exportBtn.disabled = true;
        progressFill.style.width = '10%';
        progressText.textContent = 'Preparing document...';
        
        setTimeout(() => {
            // Get team name
            const teamName = document.querySelector('.fw-bold.mb-0').textContent
                .trim()
                .replace('🏆', '')
                .replace('Medal Dashboard', '')
                .trim();
            
            // Process table data
            const table = document.getElementById('saving-datatable-shesh');
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            const tableData = [];
            let currentSport = '';
            let rowSpanCount = 0;
            
            // Similar table data processing logic as before...
            // (Keep the existing processing logic from the previous script)
            
            // Prepare summary boxes
            const summaryBoxes = [
                {
                    title: 'Gold Medals',
                    value: document.querySelector('.col-md-3:nth-child(1) .fs-3.fw-bold').textContent.trim(),
                    color: [255, 198, 1]  // Gold color
                },
                {
                    title: 'Silver Medals',
                    value: document.querySelector('.col-md-3:nth-child(2) .fs-3.fw-bold').textContent.trim(),
                    color: [183, 183, 183]  // Silver color
                },
                {
                    title: 'Bronze Medals',
                    value: document.querySelector('.col-md-3:nth-child(3) .fs-3.fw-bold').textContent.trim(),
                    color: [205, 127, 50]  // Bronze color
                },
                {
                    title: 'Total Medals',
                    value: document.querySelector('.col-md-3:nth-child(4) .fs-3.fw-bold').textContent.trim(),
                    color: [0, 73, 30]  // Dark green
                }
            ];
            
            // Configure columns for the table
            const tableColumns = [
                'Sports',
                'Event Categories',
                'Gold Count',
                'Gold Weight',
                'Silver Count',
                'Silver Weight',
                'Bronze Count',
                'Bronze Weight',
                'Total Medals',
                'Total Weight'
            ];
            
            // Create PDF Report
            const pdfReport = createModernPDFReport({
                title: teamName,
                subtitle: 'Medal Dashboard',
                summaryBoxes: summaryBoxes,
                tableData: tableData,
                tableConfig: {
                    columns: tableColumns,
                    columnStyles: {
                        0: { halign: 'left', fontStyle: 'bold', cellWidth: 'auto' },
                        1: { halign: 'left', cellWidth: 'auto' },
                        // Add more column-specific styles as needed
                    }
                }
            });
            
            progressFill.style.width = '80%';
            progressText.textContent = 'Generating report...';
            
            setTimeout(() => {
                pdfReport.save();
                
                progressFill.style.width = '100%';
                progressText.textContent = 'Download complete!';
                
                setTimeout(() => {
                    progressBar.style.display = 'none';
                    exportBtn.disabled = false;
                }, 500);
            }, 500);
        }, 100);
    });
});