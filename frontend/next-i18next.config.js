/** @type {import('next-i18next').UserConfig} */
module.exports = {
  i18n: {
    defaultLocale: 'vi',
    locales: ['vi', 'en'],
    localeDetection: false
  },
  fallbackLng: {
    default: ['vi']
  },
  nonExplicitSupportedLngs: true,
}
