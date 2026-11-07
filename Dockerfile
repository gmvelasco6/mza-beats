FROM node:22-alpine@sha256:0e1754758c2df4cfa3e68d59e2e1b7374bb7fd9ea6328eaaacf15cc934af8202

ENV NODE_ENV=production
WORKDIR /app

# Primero las dependencias: si el código cambia, no se vuelven a descargar
COPY package.json package-lock.json ./
RUN npm ci --omit=dev

COPY servidor ./servidor
COPY publico ./publico

# La aplicación no corre como root
USER node

EXPOSE 3000

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD node -e "fetch('http://127.0.0.1:' + (process.env.PORT || 3000) + '/salud').then((r) => process.exit(r.ok ? 0 : 1)).catch(() => process.exit(1))"

CMD ["node", "servidor/index.js"]
