import clsx from 'clsx'
import Link from 'next/link'

import styles from './PageLink.module.scss'

export const PageLink = ({
  href,
  page,
  children,
  className,
}: PageLinkProps) => (
  <Link
    href={page === 1 ? href : `${href}page/${page}/`}
    className={clsx(styles.pageLink, className)}
  >
    {children}
  </Link>
)
