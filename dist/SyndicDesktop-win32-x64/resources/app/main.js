const { app, BrowserWindow } = require('electron');

function createWindow() {
    const mainWindow = new BrowserWindow({
        width: 1200,
        height: 800,
        webPreferences: {
            nodeIntegration: false
        }
    });

    mainWindow.loadURL('http://127.0.0.1:8000/login'); // Change selon ton projet Symfony
}

app.whenReady().then(createWindow);
