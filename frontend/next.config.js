/* eslint-disable @typescript-eslint/no-var-requires */
const path = require('path')
const { i18n } = require('./next-i18next.config')

/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  i18n,
  trailingSlash: true,
  async redirects() {
    return [
      {
        source: '/page/1/',
        destination: '/',
        permanent: true,
      },
      {
        source: '/category/:slug/page/1/',
        destination: '/category/:slug/',
        permanent: true,
      },
      {
        source: '/tag/:slug/page/1/',
        destination: '/tag/:slug/',
        permanent: true,
      },
      {
        source: '/author/:slug/page/1/',
        destination: '/author/:slug/',
        permanent: true,
      },
    ]
  },
  images: {
    domains: ['localhost', 'secure.gravatar.com'],
  },
  sassOptions: {
    includePaths: [
      path.join(__dirname, 'styles'),
      path.join(__dirname, 'layout'),
      path.join(__dirname, 'components')
    ],
  },
}

module.exports = nextConfig
