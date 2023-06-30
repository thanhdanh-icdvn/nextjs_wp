import clsx from 'clsx'
import styles from './CurrentPage.module.scss'

export const CurrentPage = ({ children, className }: CurrentPageProps) => (
  <div className={clsx(styles.currentPage, className)}>{children}</div>
)
