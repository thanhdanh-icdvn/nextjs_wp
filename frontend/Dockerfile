# Build phase
FROM node:18-alpine as BUILDER
WORKDIR /frontend
RUN apk add --no-cache libc6-compat
COPY package.json* package-*.json ./
RUN npm install
COPY . .

# Run phase
FROM node:18-alpine as RUNNER
WORKDIR /frontend

COPY --from=BUILDER ./frontend/package.json ./package.json
COPY --from=BUILDER ./frontend/node_modules ./node_modules
COPY --from=BUILDER ./frontend/next.config.js ./next.config.js
COPY --from=BUILDER ./frontend/next-i18next.config.js ./next-i18next.config.js

COPY . .
