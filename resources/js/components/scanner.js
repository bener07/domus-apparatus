import { Modal } from "./manager";
import { Users } from "../utilities/users";
import QrScanner from "qr-scanner";

let qrScannerInstance = null; // Store the QR scanner instance globally

// Function to build the scanner HTML
function buildScanner() {
    return `
        <div class="text-center">
            <h1>Leia o QR Code com o seu dispositivo</h1>
            <video id="qr-video" style="width: 100%; height: auto;"></video>
        </div>`;
}

// Function to handle QR scanning
function handleScan() {
    const videoElement = document.getElementById('qr-video');
    if (!videoElement) {
        console.error('Video element not found!');
        return;
    }

    // Initialize the QR scanner
    qrScannerInstance = new QrScanner(
        videoElement,
        result => {
            console.log('QR Code scanned:', result);
            // Pass the scanned result to the submission handler
            handleScannerSubmission(result);
        },
        {
            highlightScanRegion: true,
            highlightCodeOutline: true,
            maxScansPerSecond: 4,
        }
    );

    // Start the scanner
    qrScannerInstance.start()
        .then(() => {
            console.log('QR Scanner started successfully.');
        })
        .catch(error => {
            console.error('Failed to start QR scanner:', error);
            alert('Failed to access the camera. Please ensure you have granted camera permissions.');
        });
}

// Function to handle the scanned result
function handleScannerSubmission(result) {
    if (!result) {
        alert('Nenhum QR Code foi escaneado. Por favor, tente novamente.');
        return;
    }

    // Process the scanned result (e.g., send it to the server)
    console.log('Scanned result:', result);
    alert(`QR Code escaneado: ${result}`);

    // Stop the scanner
    if (qrScannerInstance) {
        qrScannerInstance.stop();
        qrScannerInstance = null;
    }

    // Close the modal
    modalManager.close();
}

// Initialize Modal Manager
const modalManager = new Modal(
    '', // Modal ID (optional)
    buildScanner(), // Modal content
    'Entregar Requisição', // Modal title
    handleScan, // Called when the modal opens
    handleScannerSubmission // Called when the modal is submitted
);

// Function to open the scanner modal
export function deliveryScanner() {
    modalManager.build();
}