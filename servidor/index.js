/**
 * Punto de entrada: arranca el servidor y lo apaga prolijamente.
 */
import { crearApp } from './app.js';
import { ENTORNO } from './config.js';
import { grupo } from './lib/db.js';

const servidor = crearApp().listen(ENTORNO.puerto, () => {
  console.log(`MzaBeats escuchando en el puerto ${ENTORNO.puerto}`);
});

function apagar() {
  servidor.close(async () => {
    await grupo.end();
    process.exit(0);
  });
  servidor.closeAllConnections();
}

process.on('SIGTERM', apagar);
process.on('SIGINT', apagar);
