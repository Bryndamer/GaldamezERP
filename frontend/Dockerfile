FROM node:20-alpine

WORKDIR /app

# ─── Dependencias (capa cacheada) ────────────────────────────────────────────
COPY package.json package-lock.json* ./
RUN npm ci --prefer-offline

# ─── Código fuente ───────────────────────────────────────────────────────────
COPY . .

EXPOSE 5173

# Vite dev server escuchando en todas las interfaces (necesario en Docker)
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0", "--port", "5173"]
