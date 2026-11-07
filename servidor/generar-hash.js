/**
 * Genera el hash de una contraseña que llega por stdin. No la guarda ni la muestra.
 *
 *   printf '%s' "$CLAVE" | docker exec -i mzabeats-web node servidor/generar-hash.js
 */
import { text } from 'node:stream/consumers';
import { claveHash } from './lib/claves.js';

console.log(await claveHash(await text(process.stdin)));
