/**
 * Límite de envíos por IP para los formularios públicos que no son el login.
 *
 * El login tiene su propio bloqueo en la base (modelos/intentos-login.js). El
 * registro y el formulario "¡Quiero aparecer!" no lo tenían: alguien podía
 * crear cuentas sin parar, probar nombres de usuario para ver cuáles existen
 * o llenar el panel de pedidos falsos.
 *
 * Se guarda en memoria porque el sitio corre en un solo proceso. Si se
 * reinicia, los contadores vuelven a cero, y está bien: es un freno, no un
 * registro.
 */
import { LIMITES_POR_HORA } from '../config.js';

export class Limite {
  /**
   * @param {number} maximo    Envíos permitidos dentro de la ventana
   * @param {number} ventanaMs Largo de la ventana, en milisegundos
   * @param {() => number} reloj Hora actual (las pruebas lo reemplazan)
   */
  constructor(maximo, ventanaMs, reloj = Date.now) {
    this.maximo = maximo;
    this.ventanaMs = ventanaMs;
    this.reloj = reloj;
    this.envios = new Map(); // clave → horas de los envíos recientes
  }

  recientes(clave) {
    const desde = this.reloj() - this.ventanaMs;
    const lista = (this.envios.get(clave) ?? []).filter((hora) => hora > desde);
    if (lista.length === 0) {
      this.envios.delete(clave);
    } else {
      this.envios.set(clave, lista);
    }
    return lista;
  }

  /** ¿Ya usó todos los envíos de la ventana? */
  superado(clave) {
    return this.recientes(clave).length >= this.maximo;
  }

  registrar(clave) {
    const lista = this.recientes(clave);
    lista.push(this.reloj());
    this.envios.set(clave, lista);
    // Sin esto, un ataque desde muchas IPs llenaría la memoria
    if (this.envios.size > 10000) {
      for (const otra of this.envios.keys()) {
        this.recientes(otra);
      }
    }
  }

  /** Minutos que faltan para que se libere un envío. */
  minutosDeEspera(clave) {
    const [primero] = this.recientes(clave);
    if (primero === undefined) {
      return 0;
    }
    return Math.max(1, Math.ceil((primero + this.ventanaMs - this.reloj()) / 60000));
  }
}

const UNA_HORA = 60 * 60 * 1000;

/** Cualquier envío del formulario de registro, válido o no. */
export const limiteRegistro = new Limite(LIMITES_POR_HORA.registro, UNA_HORA);

/** Pedidos guardados de "¡Quiero aparecer!". */
export const limitePedidos = new Limite(LIMITES_POR_HORA.pedidos, UNA_HORA);
