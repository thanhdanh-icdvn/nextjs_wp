import { createHash } from 'crypto'

function md5(text: string): string {
  return createHash('md5').update(text).digest('hex')
}

export function authorizationCookieName() {
  return `wordpress_logged_in_${md5(`http://localhost`)}`
}
