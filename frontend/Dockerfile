FROM node:18-alpine as BUILDER
WORKDIR /frontend
RUN apk add --no-cache libc6-compat
COPY package.json* package-*.json ./
RUN npm install

FROM node:18-alpine as RUNNER
WORKDIR /frontend
COPY --from=BUILDER /frontend/package.json ./package.json
COPY --from=BUILDER /frontend/node_modules ./node_modules

COPY . .


